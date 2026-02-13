<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ImageUpload;
use App\Models\OnDemandCategory;
use App\Models\OnDemandService;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\BaseController as BaseController;
use DB;

class OnDemandServiceController extends BaseController
{
    public function category(Request $request)
    {
        $onDemandCategory = OnDemandCategory::where('status', 1)
        ->orderByRaw('name COLLATE utf8mb4_unicode_ci')
        ->get();

        return $this->sendResponse($onDemandCategory, 'On Demand Category retrieved successfully.');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $onDemandServices = OnDemandService::with(['onDemandCategory', 'serviceProvider'])->where('status', 1)->get();

        foreach ($onDemandServices as $key => $onDemandService) {
            if (!is_null($onDemandService->onDemandCategory)) {
                $onDemandService->on_demand_category_name = $onDemandService->onDemandCategory->name;
                unset($onDemandService->on_demand_category);
            }

            if (!empty($onDemandService->images)) {
                $newImages = [];
                $images = explode(" || ", $onDemandService->images);
                foreach ($images as $value) {
                    $newImages[] = $value;
                }

                $onDemandService->images = $newImages[0] ? $newImages[0] : '';
                $onDemandService->city_name = $onDemandService->city->name ?? '';
                $onDemandService->state_name = $onDemandService->state->name ?? '';
                $onDemandService->country_name = $onDemandService->country->name ?? '';

                unset($onDemandService->city);
                unset($onDemandService->state);
                unset($onDemandService->country);
            }
        }

        return $this->sendResponse($onDemandServices, 'On Demand Services retrieved successfully.');
    }

    public function filter(Request $request)
    {
        $query = OnDemandService::query();

        if($request->user_id){
            $query = $query->where('user_id', $request->user_id);
        }

        if($request->on_demand_categories_id){
            $query = $query->where('on_demand_categories_id', $request->on_demand_categories_id);
        }

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        } elseif ($request->start_date) {
            $query->where('created_at', '>=', $request->start_date);
        } elseif ($request->end_date) {
            $query->where('created_at', '<=', $request->end_date);
        }

        if ($request->from_price && $request->to_price) {
            $query->whereBetween('amount', [$request->from_price, $request->to_price]);
        } elseif ($request->from_price) {
            $query->where('amount', '>=', $request->from_price);
        } elseif ($request->to_price) {
            $query->where('amount', '<=', $request->to_price);
        }

        if($request->city_id){
            $query = $query->where('city_id', $request->city_id);
        }

        if($request->state_id){
            $query = $query->where('state_id', $request->state_id);
        }

        if($request->country_id){
            $query = $query->where('country_id', $request->country_id);
        }

        if ($request->search) {
            $query = $query->where('name', 'like', '%'.$request->search.'%')
                                ->orWhereHas('onDemandCategory', function ($subQuery) use ($request) {
                                      $subQuery->where('name', 'like', '%' . $request->search . '%');
                                  });
        }

        $onDemandServices = $query->where('on_demand_services.status', 1)
                    ->with(['onDemandCategory', 'serviceProvider'])
                    ->select('on_demand_services.*')
                    ->get();

        foreach ($onDemandServices as $key => $onDemandService) {
            if (!is_null($onDemandService->onDemandCategory)) {
                $onDemandService->on_demand_category_name = $onDemandService->onDemandCategory->name;
                unset($onDemandService->onDemandCategory);
            }

            if (!empty($onDemandService->images)) {
                $newImages = [];
                $images = explode(" || ", $onDemandService->images);
                foreach ($images as $value) {
                    $newImages[] = $value;
                }

                $onDemandService->images = $newImages[0] ? $newImages[0] : '';
                $onDemandService->city_name = $onDemandService->city->name ?? '';
                $onDemandService->state_name = $onDemandService->state->name ?? '';
                $onDemandService->country_name = $onDemandService->country->name ?? '';

                unset($onDemandService->city);
                unset($onDemandService->state);
                unset($onDemandService->country);
            }
        }

        return $this->sendResponse($onDemandServices, 'On Demand Services retrieved successfully.');
    }

    public function byUser($user)
    {
        $onDemandServices = OnDemandService::with(['onDemandCategory', 'serviceProvider'])->where('user_id', $user)->where('status', 1)->get();

        foreach ($onDemandServices as $key => $onDemandService) {
            if (!is_null($onDemandService->onDemandCategory)) {
                $onDemandService->On_Demand_Category_name = $onDemandService->onDemandCategory->name;
                unset($onDemandService->onDemandCategory);
            }

            if (!empty($onDemandService->images)) {
                $newImages = [];
                $images = explode(" || ", $onDemandService->images);
                foreach ($images as $value) {
                    $newImages[] = $value;
                }

                $onDemandService->images = $newImages[0] ? $newImages[0] : '';
                $onDemandService->city_name = $onDemandService->city->name ?? '';
                $onDemandService->state_name = $onDemandService->state->name ?? '';
                $onDemandService->country_name = $onDemandService->country->name ?? '';

                unset($onDemandService->city);
                unset($onDemandService->state);
                unset($onDemandService->country);
            }
        }

        return $this->sendResponse($onDemandServices, 'On Demand Service retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'user_id' => 'required',
            'on_demand_categories_id' => 'required',
            'name' => 'required',
            'phone' => 'required',
            'amount' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors()->first(), []);
        }

        $images = [];
        if (isset($input['images']) && !empty($input['images'])) {
            $count = count($input['images']);
            if ($count < 6) {
                foreach ($input['images'] as $value) {
                    $images[] = ImageUpload::upload('uploads/OnDemandService/', $value);
                }
            }else{
                return $this->sendError('images must be maximum 5', []);
            }
        }

        $input['images'] = implode(' || ', $images);

        $onDemandService = OnDemandService::create($input);

        unset($onDemandService->on_demand_categories_id);

        if (!empty($onDemandService->images)) {
            $newImages = [];
            $images = explode(" || ", $onDemandService->images);
            foreach ($images as $value) {
                $newImages[] = $value;
            }

            $onDemandService->images = $newImages[0] ? $newImages[0] : '';
        }

        $onDemandService->city_name = $onDemandService->city->name ?? '';
        $onDemandService->state_name = $onDemandService->state->name ?? '';
        $onDemandService->country_name = $onDemandService->country->name ?? '';

        unset($onDemandService->city);
        unset($onDemandService->state);
        unset($onDemandService->country);
        $onDemandService->on_demand_category_name = OnDemandCategory::where('id', $request->on_demand_categories_id)->value('name') ?? NULL;

        $name = $onDemandService->serviceProvider ? $onDemandService->serviceProvider->name : '';
        $title = 'New '.$onDemandService->name.' Service add.';

        createNotification($title, 'OnDemandService', $onDemandService->id, 'created');

        return $this->sendResponse($onDemandService, 'Artist created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $onDemandService = OnDemandService::with(['onDemandCategory', 'serviceProvider'])->find($id);

        if (!is_null($onDemandService)) {
            if (!is_null($onDemandService->onDemandCategory)) {
                $onDemandService->on_demand_category_name = $onDemandService->onDemandCategory->name;
                unset($onDemandService->onDemandCategory);
                $onDemandService->city_name = $onDemandService->city->name ?? '';
                $onDemandService->state_name = $onDemandService->state->name ?? '';
                $onDemandService->country_name = $onDemandService->country->name ?? '';

                unset($onDemandService->city);
                unset($onDemandService->state);
                unset($onDemandService->country);
            }
            return $this->sendResponse($onDemandService, 'On Demand Service retrieved successfully.');
        }else{
            return $this->sendError('On Demand Service not found.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $input = $request->all();

        $input = $request->all();
        $validator = Validator::make($input, [
            'user_id' => 'required',
            'on_demand_categories_id' => 'required',
            'name' => 'required',
            'phone' => 'required',
            'amount' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors()->first(), []);
        }
        $newImages = 0;
        $oldImages = 0;
        if (isset($input['images']) && !empty($input['images'])) {
            $newImages = count($input['images']);
        }
        if (isset($request->all_image) && !empty($request->all_image)) {
            $oldImages = count($request->all_image);
        }

        if (($newImages + $oldImages) > 6) {
            return $this->sendError('images must be maximum 5', []);
        }

        if (isset($input['images']) && !empty($input['images'])) {
            $images = [];
            foreach ($input['images'] as $value) {
                $images[] = ImageUpload::upload('uploads/OnDemandService/', $value);
            }
            $input['images'] = implode(' || ', $images);
        }
        if (isset($request->all_image) && !empty($request->all_image)) {
            $images = [];
            $url = url('/')."/";
            foreach ($request->all_image as $value) {
                if (!empty($value)) {
                    $images[] =  str_replace($url, "", $value);
                }
            }
            $images = implode(' || ', $images);
            if (isset($input['images']) && !empty($input['images'])) {
                if (!empty($images)) {
                    $input['images'] = $input['images'].' || '.$images;
                }
            }else{
                if (!empty($images)) {
                    $input['images'] = $images;
                }
            }
        }

        $onDemandService = OnDemandService::find($id);
        if ($onDemandService) {

            $onDemandService->update($input);

            unset($onDemandService->on_demand_categories_id);

            if (!empty($onDemandService->images)) {
                $newImages = [];
                $images = explode(" || ", $onDemandService->images);
                foreach ($images as $value) {
                    $newImages[] = $value;
                }

                $onDemandService->images = $newImages[0] ? $newImages[0] : '';
            }
            $onDemandService->city_name = $onDemandService->city->name ?? '';
            $onDemandService->state_name = $onDemandService->state->name ?? '';
            $onDemandService->country_name = $onDemandService->country->name ?? '';

            unset($onDemandService->city);
            unset($onDemandService->state);
            unset($onDemandService->country);
            $onDemandService->on_demand_category_name = OnDemandCategory::where('id', $request->on_demand_categories_id)->value('name') ?? NULL;

            $name = $onDemandService->serviceProvider ? $onDemandService->serviceProvider->name : '';
            $title = 'New '.$onDemandService->name.' Service add.';

            createNotification($title, 'OnDemandService', $onDemandService->id, 'update');

            return $this->sendResponse($onDemandService, 'On Demand Service updated successfully.');
        }else{
            return $this->sendError('On Demand Service not found.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $onDemandService = OnDemandService::find($id);
        if (!is_null($onDemandService)) {

            $onDemandService->delete();
            return $this->sendResponse([], 'On Demand Service Deleted successfully.');

        }else{
            return $this->sendError('On Demand Service not found.');
        }
    }
}
