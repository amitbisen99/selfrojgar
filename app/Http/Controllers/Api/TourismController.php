<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tourism;
use App\Models\TourismCategory;
use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Support\Facades\Validator;
use App\Models\City;
use App\Models\State;
use App\Models\Country;
use App\Models\ImageUpload;

class TourismController extends BaseController
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
            $tourismes = Tourism::with(['owner', 'tourismCategory', 'city', 'state', 'country'])
                               ->where('status', 1)
                               // ->whereRaw("
                               //      6371 * acos(
                               //          cos(radians(?)) * cos(radians(latitude)) *
                               //          cos(radians(longitude) - radians(?)) +
                               //          sin(radians(?)) * sin(radians(latitude))
                               //      ) < ?", [$latitude, $longitude, $latitude, $radius])
                               // Note: ST_Distance_Sphere returns METERS, so we multiply radius by 1000
                               ->whereRaw("ST_Distance_Sphere(point(longitude, latitude), point(?, ?)) <= ?", [$longitude, $latitude, $radius * 1000])
                               ->orderBy('created_at', 'desc')
                               ->get();
        }else{
            $tourismes = Tourism::with(['owner', 'tourismCategory', 'city', 'state', 'country'])
                       ->where('status', 1)
                       ->orderBy('created_at', 'desc')
                       ->get();
        }


        foreach ($tourismes as $key => $tourism) {
            $tourism->category_name = $tourism->TourismCategory->name ?? '';
            $tourism->city_name = $tourism->city->name ?? '';
            $tourism->state_name = $tourism->state->name ?? '';
            $tourism->country_name = $tourism->country->name ?? '';

            unset($tourism->TourismCategory);
            unset($tourism->city);
            unset($tourism->state);
            unset($tourism->country);
        }

        return $this->sendResponse($tourismes, 'Tourism retrieved successfully.');
    }

    public function filter(Request $request)
    {
        $query = Tourism::query();

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

        if ($request->from_railway) {
            $query->where('from_railway', '<=', $request->from_railway);
        }

        if ($request->from_bus_stand) {
            $query->where('from_bus_stand', '<=', $request->from_bus_stand);
        }
        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%')
                    ->orWhereHas('TourismCategory', function ($subQuery) use ($request) {
                          $subQuery->where('name', 'like', '%' . $request->name . '%');
                      });
        }

        $tourismes = $query->where('status', 1)
            ->with(['owner', 'tourismCategory'])
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($tourismes as $key => $tourism) {
            $tourism->category_name = $tourism->TourismCategory->name ?? '';
            unset($tourism->TourismCategory);

            $tourism_images = "";
            if (!empty($tourism->images)) {
                $newImages = [];
                $images = explode(" || ", $tourism->images);
                foreach ($images as $value) {
                    $newImages[] = $value;
                }

                $tourism->images = $newImages[0] ? $newImages[0] : '';
            }
            $tourism->city_name = $tourism->city->name ?? '';
            $tourism->state_name = $tourism->state->name ?? '';
            $tourism->country_name = $tourism->country->name ?? '';

            unset($tourism->city);
            unset($tourism->state);
            unset($tourism->country);
        }

        return $this->sendResponse($tourismes, 'Product retrieved successfully.');
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
                $images[] = ImageUpload::upload('uploads/tourism/images/', $value);
            }
        }

        $input['images'] = implode(' || ', $images);

        $tourism = Tourism::create($input);

        unset($tourism->category_id);

        if (!empty($tourism->images)) {
            $newImages = [];
            $images = explode(" || ", $tourism->images);
            foreach ($images as $value) {
                $newImages[] = $value;
            }

            $tourism->images = $newImages[0] ? $newImages[0] : '';
        }
        $tourism->city_name = $tourism->city->name ?? '';
        $tourism->state_name = $tourism->state->name ?? '';
        $tourism->country_name = $tourism->country->name ?? '';

        unset($tourism->city);
        unset($tourism->state);
        unset($tourism->country);
        $tourism->category_name = TourismCategory::where('id', $request->category_id)->value('name') ?? NULL;

        $name = $tourism->owner ? $tourism->owner->name : '';
        $title = 'New '.$tourism->name.' tourism place available.';

        createNotification($title, 'Tourism', $tourism->id, 'created');
        return $this->sendResponse($tourism, 'Tourism created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $tourism = Tourism::with(['owner', 'tourismCategory', 'city', 'state', 'country'])
                   ->where('status', 1)
                   ->where('id', $id)
                   ->first();

        if (!is_null($tourism)) {
            $tourism->category_name = $tourism->TourismCategory->name ?? '';
            $tourism->city_name = $tourism->city->name ?? '';
            $tourism->state_name = $tourism->state->name ?? '';
            $tourism->country_name = $tourism->country->name ?? '';

            unset($tourism->TourismCategory);
            unset($tourism->city);
            unset($tourism->state);
            unset($tourism->country);

            return $this->sendResponse($tourism, 'Tourism retrieved successfully.');

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
                $images[] = ImageUpload::upload('uploads/tourism/images/', $value);
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

        $tourism = Tourism::find($id);
        if ($tourism) {

            $tourism->update($input);
            $tourism = Tourism::where('status', 1)
                   ->where('id', $id)
                   ->with('owner')
                   ->with('tourismCategory')
                   ->first();

            $tourism->category_name = $tourism->TourismCategory->name ?? '';
            unset($tourism->TourismCategory);

            if (!empty($tourism->images)) {
                $newImages = [];
                $images = explode(" || ", $tourism->images);
                foreach ($images as $value) {
                    $newImages[] = $value;
                }

                $tourism->images = $newImages[0] ? $newImages[0] : '';
            }
            $tourism->city_name = $tourism->city->name ?? '';
            $tourism->state_name = $tourism->state->name ?? '';
            $tourism->country_name = $tourism->country->name ?? '';

            unset($tourism->city);
            unset($tourism->state);
            unset($tourism->country);

            $name = $tourism->owner ? $tourism->owner->name : '';
            $title = 'New '.$tourism->name.' tourism place available.';

            createNotification($title, 'Tourism', $tourism->id, 'update');
            return $this->sendResponse($tourism, 'Tourism updated successfully.');
        }else{
            return $this->sendError('Tourism not found.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tourism = Tourism::find($id);
        if (!is_null($tourism)) {

            $tourism->delete();
            return $this->sendResponse([], 'Tourism Deleted successfully.');

        }else{
            return $this->sendError('Tourism not found.');
        }
    }

    public function category(Request $request)
    {
        $tourismCategory = TourismCategory::where('status', 1)
        ->orderByRaw('name COLLATE utf8mb4_unicode_ci')
        ->get();

        return $this->sendResponse($tourismCategory, 'Tourism Category retrieved successfully.');
    }

    public function byUser($user)
    {
        $tourismes = Tourism::with(['owner', 'tourismCategory', 'city', 'state', 'country'])
                   ->where('status', 1)
                   ->where('user_id', $user)
                   ->orderBy('created_at', 'desc')
                   ->get();

        foreach ($tourismes as $key => $tourism) {
            $tourism->category_name = $tourism->TourismCategory->name ?? '';
            $tourism->city_name = $tourism->city->name ?? '';
            $tourism->state_name = $tourism->state->name ?? '';
            $tourism->country_name = $tourism->country->name ?? '';

            unset($tourism->TourismCategory);
            unset($tourism->city);
            unset($tourism->state);
            unset($tourism->country);
        }

        if ($tourismes->count() > 0) {
            return $this->sendResponse($tourismes, 'Product retrieved successfully.');
        }else{
            return $this->sendResponse([], 'Product not found.');
        }
    }
}
