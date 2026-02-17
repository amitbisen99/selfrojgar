<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\PropertyCategory;
use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Support\Facades\Validator;
use App\Models\City;
use App\Models\State;
use App\Models\Country;
use App\Models\ImageUpload;

class PropertyController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
 public function index()
    {
        $latitude = request()->latitude;
        $longitude = request()->longitude;
        $radius = request()->radius;

        if ($latitude && $longitude && $radius) {
            $propertyes = Property::with(['owner', 'propertyCategory', 'city', 'state', 'country'])
                ->where('status', 1)
                ->withAvg('ratings', 'rating')
                ->whereRaw("ST_Distance_Sphere(point(longitude, latitude), point(?, ?)) <= ?", [$longitude, $latitude, $radius * 1000])
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $propertyes = Property::with(['owner', 'propertyCategory', 'city', 'state', 'country'])
                ->where('status', 1)
                ->withAvg('ratings', 'rating')
                ->orderBy('created_at', 'desc')
                ->get();
        }


        foreach ($propertyes as $key => $property) {
            $property->category_name = !is_null($property->propertyCategory) ? $property->propertyCategory->name : '';
            $property->city_name = !is_null($property->city) ? $property->city->name : '';
            $property->state_name = !is_null($property->state) ? $property->state->name : '';
            $property->country_name = !is_null($property->country) ? $property->country->name : '';

            unset($property->propertyCategory);
            unset($property->city);
            unset($property->state);
            unset($property->country);
        }

        return $this->sendResponse($propertyes, 'business retrieved successfully.');
    }

    public function filter(Request $request)
    {
        $query = Property::query();

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

        if ($request->area_from_price && $request->area_to_price) {
            $query->whereBetween('area_price', [$request->area_from_price, $request->area_to_price]);
        } elseif ($request->area_from_price) {
            $query->where('area_price', '>=', $request->area_from_price);
        } elseif ($request->area_to_price) {
            $query->where('area_price', '<=', $request->area_to_price);
        }

        if ($request->area_from_total_price && $request->area_to_total_price) {
            $query->whereBetween('total_price', [$request->area_from_total_price, $request->area_to_total_price]);
        } elseif ($request->area_from_total_price) {
            $query->where('total_price', '>=', $request->area_from_total_price);
        } elseif ($request->area_to_total_price) {
            $query->where('total_price', '<=', $request->area_to_total_price);
        }

        if ($request->rent_from_price && $request->rent_to_price) {
            $query->where('rent_price', '>=', $request->rent_from_price)->where('rent_price', '<=', $request->rent_to_price);
        } elseif ($request->rent_from_price) {
            $query->where('rent_price', '>=', $request->rent_from_price);
        } elseif ($request->rent_to_price) {
            $query->where('rent_price', '<=', $request->rent_to_price);
        }


        if ($request->close_time) {
            $query->where('close_time', '>=', $request->close_time);
        }



        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                    ->orWhereHas('propertyCategory', function ($subQuery) use ($request) {
                          $subQuery->where('name', 'like', '%' . $request->search . '%');
                      });
        }

        $propertyes = $query->where('status', 1)
            ->with(['owner', 'propertyCategory'])
            ->withAvg('ratings', 'rating')
            ->orderBy('created_at', 'desc')
            ->get();


        foreach ($propertyes as $key => $property) {

            $property->city_name = !is_null($property->city) ? $property->city->name : '';
            $property->state_name = !is_null($property->state) ? $property->state->name : '';
            $property->country_name = !is_null($property->country) ? $property->country->name : '';

            $property->category_name = $property->propertyCategory->name ?? '';
            unset($property->propertyCategory);

            $property_images = "";
            if (!empty($property->images)) {
                $newImages = [];
                $images = explode(" || ", $property->images);
                foreach ($images as $value) {
                    $newImages[] = $value;
                }

                $property->images = $newImages[0] ? $newImages[0] : '';
            }
        }

        return $this->sendResponse($propertyes, 'Product retrieved successfully.');
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
                $images[] = ImageUpload::upload('uploads/property/images/', $value);
            }
        }

        $input['images'] = implode(' || ', $images);

        $property = Property::create($input);

        unset($property->category_id);

        if (!empty($property->images)) {
            $newImages = [];
            $images = explode(" || ", $property->images);
            foreach ($images as $value) {
                $newImages[] = $value;
            }

            $property->images = $newImages[0] ? $newImages[0] : '';
        }

        $property->category_name = propertyCategory::where('id', $request->category_id)->value('name') ?? NULL;

        $name = $property->owner ? $property->owner->name : '';
        $title = $property->name.' property is available for '.$property->property_for;

        $property->city_name = !is_null($property->city) ? $property->city->name : '';
        $property->state_name = !is_null($property->state) ? $property->state->name : '';
        $property->country_name = !is_null($property->country) ? $property->country->name : '';

        createNotification($title, 'Property', $property->id, 'created');

        return $this->sendResponse($property, 'business created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $property = Property::with(['owner', 'propertyCategory', 'city', 'state', 'country'])
                   ->where('status', 1)
                   ->withAvg('ratings', 'rating')
                   ->where('id', $id)
                   ->first();

        if (!is_null($property)) {
            $property->category_name = !is_null($property->propertyCategory) ? $property->propertyCategory->name : '';
            $property->city_name = !is_null($property->city) ? $property->city->name : '';
            $property->state_name = !is_null($property->state) ? $property->state->name : '';
            $property->country_name = !is_null($property->country) ? $property->country->name : '';

            unset($property->propertyCategory);
            unset($property->city);
            unset($property->state);
            unset($property->country);

            return $this->sendResponse($property, 'business retrieved successfully.');

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
                $images[] = ImageUpload::upload('uploads/property/images/', $value);
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

        $property = Property::find($id);
        if ($property) {

            $property->update($input);
            $property = Property::where('status', 1)
                   ->where('id', $id)
                   ->with('owner')
                   ->withAvg('ratings', 'rating')
                   ->with('propertyCategory')
                   ->first();

            $property->category_name = $property->propertyCategory->name ?? '';
            unset($property->propertyCategory);

            if (!empty($property->images)) {
                $newImages = [];
                $images = explode(" || ", $property->images);
                foreach ($images as $value) {
                    $newImages[] = $value;
                }

                $property->images = $newImages[0] ? $newImages[0] : '';
            }

            $nanme = $property->owner ? $property->owner->name : '';
            $title = $property->name.' property is available for '.$property->property_for;

            $property->city_name = !is_null($property->city) ? $property->city->name : '';
            $property->state_name = !is_null($property->state) ? $property->state->name : '';
            $property->country_name = !is_null($property->country) ? $property->country->name : '';

            createNotification($title, 'Property', $property->id, 'updated');
            return $this->sendResponse($property, 'business updated successfully.');
        }else{
            return $this->sendError('business not found.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $property = Property::find($id);
        if (!is_null($property)) {

            $property->delete();
            return $this->sendResponse([], 'business Deleted successfully.');

        }else{
            return $this->sendError('business not found.');
        }
    }

    public function category(Request $request)
    {
        $propertyCategory = propertyCategory::where('status', 1)
        ->orderByRaw('name COLLATE utf8mb4_unicode_ci')
        ->get();

        return $this->sendResponse($propertyCategory, 'business Category retrieved successfully.');
    }

    public function byUser($user)
    {
        $propertyes = Property::with(['owner', 'propertyCategory', 'city', 'state', 'country'])
                   ->where('status', 1)
                   ->withAvg('ratings', 'rating')
                   ->where('user_id', $user)
                   ->orderBy('created_at', 'desc')
                   ->get();

        foreach ($propertyes as $key => $property) {
            $property->category_name = !is_null($property->propertyCategory) ? $property->propertyCategory->name : '';
            $property->city_name = !is_null($property->city) ? $property->city->name : '';
            $property->state_name = !is_null($property->state) ? $property->state->name : '';
            $property->country_name = !is_null($property->country) ? $property->country->name : '';

            unset($property->propertyCategory);
            unset($property->city);
            unset($property->state);
            unset($property->country);
        }

        if ($propertyes->count() > 0) {
            return $this->sendResponse($propertyes, 'Product retrieved successfully.');
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
