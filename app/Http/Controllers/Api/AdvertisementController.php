<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Advertisement;
use App\Models\City;
use App\Models\State;
use App\Models\Country;
use App\Models\ImageUpload;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\BaseController as BaseController;

class AdvertisementController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $advertisementes = Advertisement::with(['sponsor', 'city', 'state', 'country'])
                       ->where('status', 1)
                       ->orderBy('created_at', 'desc')
                       ->get();


        foreach ($advertisementes as $key => $advertisement) {
            $advertisement->city_name = !is_null($advertisement->city) ? $advertisement->city->name : '';
            $advertisement->state_name = !is_null($advertisement->state) ? $advertisement->state->name : '';
            $advertisement->country_name = !is_null($advertisement->country) ? $advertisement->country->name : '';

            unset($advertisement->city);
            unset($advertisement->state);
            unset($advertisement->country);
        }

        return $this->sendResponse($advertisementes, 'advertisment retrieved successfully.');
    }

    public function filter(Request $request)
    {
        $query = Advertisement::query();

        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
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
            $query->where('offer_name', 'like', '%' . $request->search . '%');
        }

        $advertisementes = $query->where('status', 1)
            ->with(['sponsor'])
            ->orderBy('created_at', 'desc')
            ->get();


        foreach ($advertisementes as $key => $advertisement) {

            $advertisement->city_name = !is_null($advertisement->city) ? $advertisement->city->name : '';
            $advertisement->state_name = !is_null($advertisement->state) ? $advertisement->state->name : '';
            $advertisement->country_name = !is_null($advertisement->country) ? $advertisement->country->name : '';

            unset($advertisement->city);
            unset($advertisement->state);
            unset($advertisement->country);

            $advertisement_images = "";
            if (!empty($advertisement->images)) {
                $newImages = [];
                $images = explode(" || ", $advertisement->images);
                foreach ($images as $value) {
                    $newImages[] = $value;
                }

                $advertisement->images = $newImages[0] ? $newImages[0] : '';
            }
        }

        return $this->sendResponse($advertisementes, 'Product retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'user_id' => 'required',
            'offer_name' => 'required',
            'description' => 'required',
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
                $images[] = ImageUpload::upload('uploads/advertisment/', $value);
            }
        }

        $input['images'] = implode(' || ', $images);

        $advertisement = Advertisement::create($input);

        unset($advertisement->category_id);

        if (!empty($advertisement->images)) {
            $newImages = [];
            $images = explode(" || ", $advertisement->images);
            foreach ($images as $value) {
                $newImages[] = $value;
            }

            $advertisement->images = $newImages[0] ? $newImages[0] : '';
        }

        $advertisement->city_name = !is_null($advertisement->city) ? $advertisement->city->name : '';
        $advertisement->state_name = !is_null($advertisement->state) ? $advertisement->state->name : '';
        $advertisement->country_name = !is_null($advertisement->country) ? $advertisement->country->name : '';

        unset($advertisement->city);
        unset($advertisement->state);
        unset($advertisement->country);

        $name = $advertisement->sponsor ? $advertisement->sponsor->name : '';
        $title = 'New '.$advertisement->offer_name.' add.';

        createNotification($title, 'Advertisment', $advertisement->id, 'created');

        return $this->sendResponse($advertisement, 'advertisment created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $advertisement = Advertisement::with(['sponsor', 'city', 'state', 'country'])
                   ->where('status', 1)
                   ->where('id', $id)
                   ->first();

        if (!is_null($advertisement)) {
            $advertisement->city_name = !is_null($advertisement->city) ? $advertisement->city->name : '';
            $advertisement->state_name = !is_null($advertisement->state) ? $advertisement->state->name : '';
            $advertisement->country_name = !is_null($advertisement->country) ? $advertisement->country->name : '';

            unset($advertisement->city);
            unset($advertisement->state);
            unset($advertisement->country);

            return $this->sendResponse($advertisement, 'advertisment retrieved successfully.');

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
            'offer_name' => 'required',
            'description' => 'required',
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
                $images[] = ImageUpload::upload('uploads/advertisment/', $value);
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

        $advertisement = Advertisement::find($id);
        if ($advertisement) {

            $advertisement->update($input);
            $advertisement = Advertisement::where('status', 1)
                   ->where('id', $id)
                   ->with('sponsor')
                   ->first();


            if (!empty($advertisement->images)) {
                $newImages = [];
                $images = explode(" || ", $advertisement->images);
                foreach ($images as $value) {
                    $newImages[] = $value;
                }

                $advertisement->images = $newImages[0] ? $newImages[0] : '';
            }

            $advertisement->city_name = !is_null($advertisement->city) ? $advertisement->city->name : '';
            $advertisement->state_name = !is_null($advertisement->state) ? $advertisement->state->name : '';
            $advertisement->country_name = !is_null($advertisement->country) ? $advertisement->country->name : '';

            unset($advertisement->city);
            unset($advertisement->state);
            unset($advertisement->country);

            $nanme = $advertisement->sponsor ? $advertisement->sponsor->name : '';
            $title = 'New '.$advertisement->offer_name.' add.';

            createNotification($title, 'Advertisment', $advertisement->id, 'updated');
            return $this->sendResponse($advertisement, 'advertisment updated successfully.');
        }else{
            return $this->sendError('advertisment not found.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $advertisement = Advertisement::find($id);
        if (!is_null($advertisement)) {

            $advertisement->delete();
            return $this->sendResponse([], 'advertisment Deleted successfully.');

        }else{
            return $this->sendError('advertisment not found.');
        }
    }

    public function category(Request $request)
    {
        $advertisementCategory = advertismentCategory::where('status', 1)
        ->orderByRaw('name COLLATE utf8mb4_unicode_ci')
        ->get();

        return $this->sendResponse($advertisementCategory, 'advertisment Category retrieved successfully.');
    }

    public function byUser($user)
    {
        $advertisementes = Advertisement::with(['sponsor', 'city', 'state', 'country'])
                   ->where('status', 1)
                   ->where('user_id', $user)
                   ->orderBy('created_at', 'desc')
                   ->get();

        foreach ($advertisementes as $key => $advertisement) {
            $advertisement->city_name = !is_null($advertisement->city) ? $advertisement->city->name : '';
            $advertisement->state_name = !is_null($advertisement->state) ? $advertisement->state->name : '';
            $advertisement->country_name = !is_null($advertisement->country) ? $advertisement->country->name : '';

            unset($advertisement->city);
            unset($advertisement->state);
            unset($advertisement->country);
        }

        if ($advertisementes->count() > 0) {
            return $this->sendResponse($advertisementes, 'Product retrieved successfully.');
        }else{
            return $this->sendResponse([], 'Product not found.');
        }
    }
}
