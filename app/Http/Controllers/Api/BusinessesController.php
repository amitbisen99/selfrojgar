<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Business;
use App\Models\BusinessCategory;
use App\Models\BusinessRating;
use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Support\Facades\Validator;
use App\Models\City;
use App\Models\State;
use App\Models\Country;
use App\Models\ImageUpload;

class BusinessesController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $latitude = request()->latitude;
        $longitude = request()->longitude;
        $radius = request()->radius;

        if($latitude && $longitude && $radius){
            $businesses = Business::with(['owner', 'businessCategory', 'city', 'state', 'country'])
                               ->where('status', 1)
                               ->withAvg('ratings', 'rating')
                            //    ->whereRaw("
                            //         6371 * acos(
                            //             cos(radians(?)) * cos(radians(latitude)) *
                            //             cos(radians(longitude) - radians(?)) +
                            //             sin(radians(?)) * sin(radians(latitude))
                            //         ) < ?", [$latitude, $longitude, $latitude, $radius])
                               ->whereRaw("ST_Distance_Sphere(point(longitude, latitude), point(?, ?)) <= ?", [$longitude, $latitude, $radius * 1000])
                               ->orderBy('created_at', 'desc')
                               ->get();
        }else{
            $businesses = Business::with(['owner', 'businessCategory', 'city', 'state', 'country'])
                       ->where('status', 1)
                       ->withAvg('ratings', 'rating')
                       ->orderBy('created_at', 'desc')
                       ->get();
        }


        foreach ($businesses as $key => $business) {
            $business->category_name = !is_null($business->businessCategory) ? $business->businessCategory->name : '';
            $business->city_name = !is_null($business->city) ? $business->city->name : '';
            $business->state_name = !is_null($business->state) ? $business->state->name : '';
            $business->country_name = !is_null($business->country) ? $business->country->name : '';

            unset($business->businessCategory);
            unset($business->city);
            unset($business->state);
            unset($business->country);
        }

        return $this->sendResponse($businesses, 'business retrieved successfully.');
    }

    public function filter(Request $request)
    {
        $query = Business::query();

        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
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

        if ($request->open_time) {
            $query->where('open_time', '<=', $request->open_time);
        }

        if ($request->close_time) {
            $query->where('close_time', '>=', $request->close_time);
        }



        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                    ->orWhereHas('businessCategory', function ($subQuery) use ($request) {
                      $subQuery->where('name', 'like', '%' . $request->search . '%');
                  });
        }

        $businesses = $query->where('status', 1)
            ->with(['owner', 'businessCategory'])
            ->withAvg('ratings', 'rating')
            ->orderBy('created_at', 'desc')
            ->get();


        foreach ($businesses as $key => $business) {

            $business->city_name = !is_null($business->city) ? $business->city->name : '';
            $business->state_name = !is_null($business->state) ? $business->state->name : '';
            $business->country_name = !is_null($business->country) ? $business->country->name : '';

            $business->category_name = $business->businessCategory->name ?? '';
            unset($business->businessCategory);

            $business_images = "";
            if (!empty($business->images)) {
                $newImages = [];
                $images = explode(" || ", $business->images);
                foreach ($images as $value) {
                    $newImages[] = $value;
                }

                $business->images = $newImages[0] ? $newImages[0] : '';
            }
        }

        return $this->sendResponse($businesses, 'Product retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'user_id' => 'required',
            'category_id' => 'required',
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
                $images[] = ImageUpload::upload('uploads/business/images/', $value);
            }
        }

        $input['images'] = implode(' || ', $images);

        $business = Business::create($input);

        unset($business->category_id);

        if (!empty($business->images)) {
            $newImages = [];
            $images = explode(" || ", $business->images);
            foreach ($images as $value) {
                $newImages[] = $value;
            }

            $business->images = $newImages[0] ? $newImages[0] : '';
        }

        $business->category_name = BusinessCategory::where('id', $request->category_id)->value('name') ?? NULL;

        $business->city_name = !is_null($business->city) ? $business->city->name : '';
        $business->state_name = !is_null($business->state) ? $business->state->name : '';
        $business->country_name = !is_null($business->country) ? $business->country->name : '';

        $name = $business->owner ? $business->owner->name : '';
        $title = 'New '.$business->name.' add.';

        createNotification($title, 'Business', $business->id, 'created');

        return $this->sendResponse($business, 'business created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $business = Business::with(['owner', 'businessCategory', 'city', 'state', 'country'])
                   ->where('status', 1)
                   ->withAvg('ratings', 'rating')
                   ->where('id', $id)
                   ->first();

        if (!is_null($business)) {
            $business->category_name = !is_null($business->businessCategory) ? $business->businessCategory->name : '';
            $business->city_name = !is_null($business->city) ? $business->city->name : '';
            $business->state_name = !is_null($business->state) ? $business->state->name : '';
            $business->country_name = !is_null($business->country) ? $business->country->name : '';

            unset($business->businessCategory);
            unset($business->city);
            unset($business->state);
            unset($business->country);

            return $this->sendResponse($business, 'business retrieved successfully.');

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
            'category_id' => 'required',
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
                $images[] = ImageUpload::upload('uploads/business/images/', $value);
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

        $business = Business::find($id);
        if ($business) {

            $business->update($input);
            $business = Business::where('status', 1)
                   ->where('id', $id)
                   ->with('owner')
                   ->withAvg('ratings', 'rating')
                   ->with('businessCategory')
                   ->first();

            $business->category_name = $business->businessCategory->name ?? '';
            unset($business->businessCategory);

            if (!empty($business->images)) {
                $newImages = [];
                $images = explode(" || ", $business->images);
                foreach ($images as $value) {
                    $newImages[] = $value;
                }

                $business->images = $newImages[0] ? $newImages[0] : '';
            }

            $nanme = $business->owner ? $business->owner->name : '';
            $title = 'New '.$business->name.' add.';

            $business->city_name = !is_null($business->city) ? $business->city->name : '';
            $business->state_name = !is_null($business->state) ? $business->state->name : '';
            $business->country_name = !is_null($business->country) ? $business->country->name : '';

            createNotification($title, 'Business', $business->id, 'updated');
            return $this->sendResponse($business, 'business updated successfully.');
        }else{
            return $this->sendError('business not found.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $business = Business::find($id);
        if (!is_null($business)) {

            $business->delete();
            return $this->sendResponse([], 'business Deleted successfully.');

        }else{
            return $this->sendError('business not found.');
        }
    }

    public function category(Request $request)
    {
        $businessCategory = BusinessCategory::where('status', 1)
        ->orderByRaw('name COLLATE utf8mb4_unicode_ci')
        ->get();

        return $this->sendResponse($businessCategory, 'business Category retrieved successfully.');
    }

    public function byUser($user)
    {
        $businesses = Business::with(['owner', 'businessCategory', 'city', 'state', 'country'])
                   ->where('status', 1)
                   ->withAvg('ratings', 'rating')
                   ->where('user_id', $user)
                   ->orderBy('created_at', 'desc')
                   ->get();

        foreach ($businesses as $key => $business) {
            $business->category_name = !is_null($business->businessCategory) ? $business->businessCategory->name : '';
            $business->city_name = !is_null($business->city) ? $business->city->name : '';
            $business->state_name = !is_null($business->state) ? $business->state->name : '';
            $business->country_name = !is_null($business->country) ? $business->country->name : '';

            unset($business->businessCategory);
            unset($business->city);
            unset($business->state);
            unset($business->country);
        }

        if ($businesses->count() > 0) {
            return $this->sendResponse($businesses, 'Product retrieved successfully.');
        }else{
            return $this->sendResponse([], 'Product not found.');
        }
    }

    public function rating(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'user_id' => 'required',
            'business_id' => 'required',
            'rating' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors()->first(), []);
        }

        BusinessRating::create($input);

        return $this->sendResponse([], 'Product Rating added successfully.');
    }
}
