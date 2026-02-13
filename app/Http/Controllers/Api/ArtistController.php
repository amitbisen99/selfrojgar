<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Genres;
use App\Models\Artist;
use App\Models\ImageUpload;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\BaseController as BaseController;
use DB;

class ArtistController extends BaseController
{
    public function genres(Request $request)
    {
        $genres = Genres::where('status', 1)
        ->orderByRaw('name COLLATE utf8mb4_unicode_ci')
        ->get();

        return $this->sendResponse($genres, 'genres retrieved successfully.');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $artists = Artist::with(['genres', 'serviceProvider'])->where('status', 1)->get();

        foreach ($artists as $key => $artist) {
            if (!is_null($artist->genres)) {
                $artist->genres_name = $artist->genres->name;
                unset($artist->genres);
            }

            if (!empty($artist->images)) {
                $newImages = [];
                $images = explode(" || ", $artist->images);
                foreach ($images as $value) {
                    $newImages[] = $value;
                }

                $artist->images = $newImages[0] ? $newImages[0] : '';
            }
            $artist->city_name = $artist->city->name ?? '';
            $artist->state_name = $artist->state->name ?? '';
            $artist->country_name = $artist->country->name ?? '';

            unset($artist->city);
            unset($artist->state);
            unset($artist->country);
        }

        return $this->sendResponse($artists, 'Artist retrieved successfully.');
    }

    public function filter(Request $request)
    {
        $query = Artist::query();

        if($request->user_id){
            $query = $query->where('user_id', $request->user_id);
        }

        if($request->genres_id){
            $query = $query->where('genres_id', $request->genres_id);
        }

        if($request->genres_name){
            $query = $query->where('genres_id', $request->genres_id);
        }

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        } elseif ($request->start_date) {
            $query->where('created_at', '>=', $request->start_date);
        } elseif ($request->end_date) {
            $query->where('created_at', '<=', $request->end_date);
        }

        if ($request->from_price && $request->to_price) {
            $query->whereBetween('charges', [$request->from_price, $request->to_price]);
        } elseif ($request->from_price) {
            $query->where('charges', '>=', $request->from_price);
        } elseif ($request->to_price) {
            $query->where('charges', '<=', $request->to_price);
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
                            ->orWhereHas('genres', function ($subQuery) use ($request) {
                                  $subQuery->where('name', 'like', '%' . $request->search . '%');
                              });
        }

        $artists = $query->where('artists.status', 1)
                    ->with(['genres', 'serviceProvider'])
                    ->select('artists.*')
                    ->get();

        foreach ($artists as $key => $artist) {
            if (!is_null($artist->genres)) {
                $artist->genres_name = $artist->genres->name;
                unset($artist->genres);
            }

            if (!empty($artist->images)) {
                $newImages = [];
                $images = explode(" || ", $artist->images);
                foreach ($images as $value) {
                    $newImages[] = $value;
                }

                $artist->images = $newImages[0] ? $newImages[0] : '';
            }
            $artist->city_name = $artist->city->name ?? '';
            $artist->state_name = $artist->state->name ?? '';
            $artist->country_name = $artist->country->name ?? '';

            unset($artist->city);
            unset($artist->state);
            unset($artist->country);
        }

        return $this->sendResponse($artists, 'Artist retrieved successfully.');
    }

    public function byUser($user)
    {
        $artists = Artist::with(['genres', 'serviceProvider'])->where('user_id', $user)->where('status', 1)->get();

        foreach ($artists as $key => $artist) {
            if (!is_null($artist->genres)) {
                $artist->genres_name = $artist->genres->name;
                unset($artist->genres);
            }

            if (!empty($artist->images)) {
                $newImages = [];
                $images = explode(" || ", $artist->images);
                foreach ($images as $value) {
                    $newImages[] = $value;
                }

                $artist->images = $newImages[0] ? $newImages[0] : '';
            }
            $artist->city_name = $artist->city->name ?? '';
            $artist->state_name = $artist->state->name ?? '';
            $artist->country_name = $artist->country->name ?? '';

            unset($artist->city);
            unset($artist->state);
            unset($artist->country);
        }

        return $this->sendResponse($artists, 'Artist retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'user_id' => 'required',
            'charges' => 'required',
            'about' => 'required',
            'genres_id' => 'required',
            'contact_no' => 'required',
            'name' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors()->first(), []);
        }

        $images = [];
        if (isset($input['images']) && !empty($input['images'])) {
            foreach ($input['images'] as $value) {
                $images[] = ImageUpload::upload('uploads/artist/', $value);
            }
        }

        $input['images'] = implode(' || ', $images);

        $artist = Artist::create($input);

        unset($artist->genres_id);

        if (!empty($artist->images)) {
            $newImages = [];
            $images = explode(" || ", $artist->images);
            foreach ($images as $value) {
                $newImages[] = $value;
            }

            $artist->images = $newImages[0] ? $newImages[0] : '';
        }
        $artist->city_name = $artist->city->name ?? '';
        $artist->state_name = $artist->state->name ?? '';
        $artist->country_name = $artist->country->name ?? '';

        unset($artist->city);
        unset($artist->state);
        unset($artist->country);

        $artist->genres_name = Genres::where('id', $request->genres_id)->value('name') ?? NULL;

        $name = $artist->serviceProvider ? $artist->serviceProvider->name : '';
        $title = 'New '.$artist->name.' artist available.';

        createNotification($title, 'Artist', $artist->id, 'created');
        return $this->sendResponse($artist, 'Artist created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $artist = Artist::with(['genres', 'serviceProvider'])->find($id);

        if (!is_null($artist)) {
            if (!is_null($artist->genres)) {
                $artist->genres_name = $artist->genres->name;
                unset($artist->genres);
                $artist->city_name = $artist->city->name ?? '';
                $artist->state_name = $artist->state->name ?? '';
                $artist->country_name = $artist->country->name ?? '';

                unset($artist->city);
                unset($artist->state);
                unset($artist->country);
            }
            return $this->sendResponse($artist, 'Artist retrieved successfully.');
        }else{
            return $this->sendError('Artist not found.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'user_id' => 'required',
            'charges' => 'required',
            'about' => 'required',
            'genres_id' => 'required',
            'contact_no' => 'required',
            'name' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors()->first(), []);
        }

        if (isset($input['images']) && !empty($input['images'])) {
            $images = [];
            foreach ($input['images'] as $value) {
                $images[] = ImageUpload::upload('uploads/artist/', $value);
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

        $artist = Artist::find($id);
        if ($artist) {

            $artist->update($input);

            unset($artist->genres_id);
            $artist->genres_name = Genres::where('id', $request->genres_id)->value('name') ?? NULL;

            if (!empty($artist->images)) {
                $newImages = [];
                $images = explode(" || ", $artist->images);
                foreach ($images as $value) {
                    $newImages[] = $value;
                }

                $artist->images = $newImages[0] ? $newImages[0] : '';
            }
            $artist->city_name = $artist->city->name ?? '';
            $artist->state_name = $artist->state->name ?? '';
            $artist->country_name = $artist->country->name ?? '';

            unset($artist->city);
            unset($artist->state);
            unset($artist->country);
            $name = $artist->serviceProvider ? $artist->serviceProvider->name : '';
            $title = 'New '.$artist->name.' artist available.';

            createNotification($title, 'Artist', $artist->id, 'updated');
            return $this->sendResponse($artist, 'Artist Updated successfully.');
        }else{
            return $this->sendError('Artist not found.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $artist = Artist::find($id);
        if (!is_null($artist)) {

            $artist->delete();
            return $this->sendResponse([], 'Artist Deleted successfully.');

        }else{
            return $this->sendError('Artist not found.');
        }
    }
}
