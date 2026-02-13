<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FranchiseBusiness;
use App\Models\FranchiseCategory;
use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Support\Facades\Validator;
use App\Models\City;
use App\Models\State;
use App\Models\Country;
use App\Models\ImageUpload;

class FranchiseBusinessController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $franchiseBusinesses = FranchiseBusiness::with(['owner', 'franchiseCategory', 'city', 'state', 'country'])
                   ->where('status', 1)
                   ->orderBy('created_at', 'desc')
                   ->get();

        foreach ($franchiseBusinesses as $key => $franchiseBusiness) {
            $franchiseBusiness->franchise_category_name = $franchiseBusiness->franchiseCategory->name;
            $franchiseBusiness->city_name = $franchiseBusiness->city->name ?? '';
            $franchiseBusiness->state_name = $franchiseBusiness->state->name ?? '';
            $franchiseBusiness->country_name = $franchiseBusiness->country->name ?? '';

            unset($franchiseBusiness->franchiseCategory);
            unset($franchiseBusiness->city);
            unset($franchiseBusiness->state);
            unset($franchiseBusiness->country);
        }

        return $this->sendResponse($franchiseBusinesses, 'Franchise retrieved successfully.');
    }

    public function filter(Request $request)
    {
        $query = FranchiseBusiness::query();

        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->franchise_categories_id) {
            $query->where('franchise_categories_id', $request->franchise_categories_id);
        }

        if ($request->industry_experience) {
            $query->where('industry_experience', '<=', $request->industry_experience);
        }

        if ($request->investment) {
            $query->where('investment', '<=', $request->investment);
        }

        if ($request->city_id) {
            $query->where('city_id', $request->city_id);
        }

        if ($request->state_id) {
            $query->where('state_id', $request->state_id);
        }

        if ($request->country_id) {
            $query->where('country_id', $request->country_id);
        }

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                        ->orWhereHas('franchiseCategory', function ($subQuery) use ($request) {
                              $subQuery->where('name', 'like', '%' . $request->search . '%');
                          });
        }

        $franchiseBusinesses = $query->where('status', 1)
            ->with(['owner', 'franchiseCategory'])
            ->orderBy('created_at', 'desc')
            ->get();


        foreach ($franchiseBusinesses as $key => $franchiseBusiness) {

            $franchiseBusiness->city_name = $franchiseBusiness->city->name ?? '';
            $franchiseBusiness->state_name = $franchiseBusiness->state->name ?? '';
            $franchiseBusiness->country_name = $franchiseBusiness->country->name ?? '';

            $franchiseBusiness->franchise_category_name = $franchiseBusiness->franchiseCategory->name ?? '';
            unset($franchiseBusiness->franchiseCategory);

            $franchiseBusiness_images = "";
            if (!empty($franchiseBusiness->images)) {
                $newImages = [];
                $images = explode(" || ", $franchiseBusiness->images);
                foreach ($images as $value) {
                    $newImages[] = $value;
                }

                $franchiseBusiness->images = $newImages[0] ? $newImages[0] : '';
            }
        }

        return $this->sendResponse($franchiseBusinesses, 'Product retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'user_id' => 'required',
            'franchise_categories_id' => 'required',
            'name' => 'required',
            'city_id' => 'required',
            'state_id' => 'required',
            'country_id' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors()->first(), []);
        }

        $images = [];
        if (isset($input['images']) && !empty($input['images'])) {
            foreach ($input['images'] as $value) {
                $images[] = ImageUpload::upload('uploads/franchise/images/', $value);
            }
        }

        $input['images'] = implode(' || ', $images);

        $franchiseBusiness = FranchiseBusiness::create($input);

        unset($franchiseBusiness->franchise_categories_id);

        if (!empty($franchiseBusiness->images)) {
            $newImages = [];
            $images = explode(" || ", $franchiseBusiness->images);
            foreach ($images as $value) {
                $newImages[] = $value;
            }

            $franchiseBusiness->images = $newImages[0] ? $newImages[0] : '';
        }

        $franchiseBusiness->franchise_category_name = FranchiseCategory::where('id', $request->franchise_categories_id)->value('name') ?? NULL;

        $franchiseBusiness->city_name = $franchiseBusiness->city->name ?? '';
        $franchiseBusiness->state_name = $franchiseBusiness->state->name ?? '';
        $franchiseBusiness->country_name = $franchiseBusiness->country->name ?? '';

        $name = $franchiseBusiness->owner ? $franchiseBusiness->owner->name : '';
        $title = 'New '.$franchiseBusiness->name.' Franchise add.';

        createNotification($title, 'FranchiseBusiness', $franchiseBusiness->id, 'created');

        return $this->sendResponse($franchiseBusiness, 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $franchiseBusiness = FranchiseBusiness::with(['owner', 'franchiseCategory', 'city', 'state', 'country'])
                   ->where('status', 1)
                   ->where('id', $id)
                   ->first();

        if (!is_null($franchiseBusiness)) {
            $franchiseBusiness->franchise_category_name = $franchiseBusiness->franchiseCategory->name;
            $franchiseBusiness->city_name = $franchiseBusiness->city->name ?? '';
            $franchiseBusiness->state_name = $franchiseBusiness->state->name ?? '';
            $franchiseBusiness->country_name = $franchiseBusiness->country->name ?? '';

            unset($franchiseBusiness->franchiseCategory);
            unset($franchiseBusiness->city);
            unset($franchiseBusiness->state);
            unset($franchiseBusiness->country);

            return $this->sendResponse($franchiseBusiness, 'Product retrieved successfully.');

        }else{
            return $this->sendError('Product not found.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'user_id' => 'required',
            'franchise_categories_id' => 'required',
            'name' => 'required',
            'city_id' => 'required',
            'state_id' => 'required',
            'country_id' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors()->first(), []);
        }

        if (isset($input['images']) && !empty($input['images'])) {
            $images = [];
            foreach ($input['images'] as $value) {
                $images[] = ImageUpload::upload('uploads/franchise/images/', $value);
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

        $franchiseBusiness = FranchiseBusiness::find($id);
        if ($franchiseBusiness) {

            $franchiseBusiness->city_name = $franchiseBusiness->city->name ?? '';
            $franchiseBusiness->state_name = $franchiseBusiness->state->name ?? '';
            $franchiseBusiness->country_name = $franchiseBusiness->country->name ?? '';

            $franchiseBusiness->update($input);
            $franchiseBusiness = FranchiseBusiness::where('status', 1)
                   ->where('id', $id)
                   ->with('owner')
                   ->with('franchiseCategory')
                   ->first();

            $franchiseBusiness->franchise_category_name = $franchiseBusiness->franchiseCategory->name ?? '';
            unset($franchiseBusiness->franchiseCategory);

            if (!empty($franchiseBusiness->images)) {
                $newImages = [];
                $images = explode(" || ", $franchiseBusiness->images);
                foreach ($images as $value) {
                    $newImages[] = $value;
                }

                $franchiseBusiness->images = $newImages[0] ? $newImages[0] : '';
            }

            $name = $franchiseBusiness->owner ? $franchiseBusiness->owner->name : '';
            $title = 'New '.$franchiseBusiness->name.' Franchise add.';

            createNotification($title, 'FranchiseBusiness', $franchiseBusiness->id, 'update');

            return $this->sendResponse($franchiseBusiness, 'Franchise updated successfully.');
        }else{
            return $this->sendError('Franchise not found.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $franchiseBusiness = FranchiseBusiness::find($id);
        if (!is_null($franchiseBusiness)) {

            $franchiseBusiness->delete();
            return $this->sendResponse([], 'Franchise Deleted successfully.');

        }else{
            return $this->sendError('Franchise not found.');
        }
    }

    public function category(Request $request)
    {
        $franchiseBusinessCategory = FranchiseCategory::where('status', 1)
        ->orderByRaw('name COLLATE utf8mb4_unicode_ci')
        ->get();

        return $this->sendResponse($franchiseBusinessCategory, 'Product Category retrieved successfully.');
    }

    public function byUser($user)
    {
        $franchiseBusinesses = FranchiseBusiness::with(['owner', 'franchiseCategory', 'city', 'state', 'country'])
                   ->where('status', 1)
                   ->where('user_id', $user)
                   ->orderBy('created_at', 'desc')
                   ->get();

        foreach ($franchiseBusinesses as $key => $franchiseBusiness) {
            $franchiseBusiness->franchise_category_name = $franchiseBusiness->franchiseCategory->name;
            $franchiseBusiness->city_name = $franchiseBusiness->city->name ?? '';
            $franchiseBusiness->state_name = $franchiseBusiness->state->name ?? '';
            $franchiseBusiness->country_name = $franchiseBusiness->country->name ?? '';

            unset($franchiseBusiness->franchiseCategory);
            unset($franchiseBusiness->city);
            unset($franchiseBusiness->state);
            unset($franchiseBusiness->country);
        }

        if ($franchiseBusinesses->count() > 0) {
            return $this->sendResponse($franchiseBusinesses, 'Product retrieved successfully.');
        }else{
            return $this->sendResponse([], 'Product not found.');
        }
    }
}
