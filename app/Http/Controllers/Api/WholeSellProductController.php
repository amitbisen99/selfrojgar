<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ImageUpload;
use App\Models\WholeSellProduct;
use App\Models\WholeSellCategory;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\BaseController as BaseController;
use DB;
use Carbon\Carbon;

class WholeSellProductController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $wholeSellProduct = WholeSellProduct::with('wholeSellCategory')->with('serviceProvider')
                   ->where('status', 1)
                   ->whereDate('end_date', '>', Carbon::today())
                   ->orderBy('created_at', 'desc')
                   ->get();

        $wholeSellProduct = $wholeSellProduct->map(function ($wholeSellProduct) {
            $wholeSellProduct->whole_sell_categories_name = $wholeSellProduct->wholeSellCategory ? $wholeSellProduct->wholeSellCategory->name : null;
            if (!empty($wholeSellProduct->images)) {
                $newImages = [];
                $images = explode(" || ", $wholeSellProduct->images);
                foreach ($images as $value) {
                    $newImages[] = $value;
                }
                $wholeSellProduct->images = $newImages[0] ? $newImages[0] : '';
            }
            $wholeSellProduct->city_name = $wholeSellProduct->city->name ?? '';
            $wholeSellProduct->state_name = $wholeSellProduct->state->name ?? '';
            $wholeSellProduct->country_name = $wholeSellProduct->country->name ?? '';

            unset($wholeSellProduct->city);
            unset($wholeSellProduct->state);
            unset($wholeSellProduct->country);
            unset($wholeSellProduct->wholeSellCategory);
            return $wholeSellProduct;
        });
        return $this->sendResponse($wholeSellProduct, 'Product retrieved successfully.');
    }

    public function filter(Request $request)
    {
        $query = WholeSellProduct::query();

        if($request->user_id){
            $query = $query->where('user_id', $request->user_id);
        }
        if($request->whole_sell_categories_id){
            $query = $query->where('whole_sell_categories_id', $request->whole_sell_categories_id);
        }

        if ($request->from_price && $request->to_price) {
            $query->whereBetween('amount', [$request->from_price, $request->to_price]);
        } elseif ($request->from_price) {
            $query->where('amount', '>=', $request->from_price);
        } elseif ($request->to_price) {
            $query->where('amount', '<=', $request->to_price);
        }

        if ($request->min_qty) {
            $query->where('min_qty', '<=', $request->min_qty);
        }

        if($request->search){
            $query = $query->Where('name', 'like', '%'.$request->search.'%')
                            ->orWhereHas('wholeSellCategory', function ($subQuery) use ($request) {
                                  $subQuery->where('name', 'like', '%' . $request->search . '%');
                              });
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

        $wholeSellProduct = $query->where('whole_sell_products.status', 1)
                                    ->with(['wholeSellCategory', 'serviceProvider'])
                                   ->whereDate('end_date', '>', Carbon::today())
                                   ->orderBy('whole_sell_products.created_at', 'desc')
                                   ->select('whole_sell_products.*')
                                   ->get();

        $wholeSellProduct = $wholeSellProduct->map(function ($wholeSellProduct) {
            $wholeSellProduct->whole_sell_categories_name = $wholeSellProduct->wholeSellCategory ? $wholeSellProduct->wholeSellCategory->name : null;
            if (!empty($wholeSellProduct->images)) {
                $newImages = [];
                $images = explode(" || ", $wholeSellProduct->images);
                foreach ($images as $value) {
                    $newImages[] = $value;
                }

                $wholeSellProduct->images = $newImages[0] ? $newImages[0] : '';
            }
            $wholeSellProduct->city_name = $wholeSellProduct->city->name ?? '';
            $wholeSellProduct->state_name = $wholeSellProduct->state->name ?? '';
            $wholeSellProduct->country_name = $wholeSellProduct->country->name ?? '';

            unset($wholeSellProduct->city);
            unset($wholeSellProduct->state);
            unset($wholeSellProduct->country);
            unset($wholeSellProduct->wholeSellCategory);
            return $wholeSellProduct;
        });

        return $this->sendResponse($wholeSellProduct, 'Product retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'user_id' => 'required',
            'whole_sell_categories_id' => 'required',
            'name' => 'required',
            'amount' => 'required',
            'end_date' => 'required',
            'min_qty' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors()->first(), []);
        }
        $images = [];
        if (isset($input['images']) && !empty($input['images'])) {
            foreach ($input['images'] as $value) {
                $images[] = ImageUpload::upload('uploads/product/images/', $value);
            }
        }

        $input['images'] = implode(' || ', $images);
        $wholeSellProduct = WholeSellProduct::create($input);

        unset($wholeSellProduct->whole_sell_categories_id);

        if (!empty($wholeSellProduct->images)) {
            $newImages = [];
            $images = explode(" || ", $wholeSellProduct->images);
            foreach ($images as $value) {
                $newImages[] = $value;
            }

            $wholeSellProduct->images = $newImages[0] ? $newImages[0] : '';
        }

        $wholeSellProduct->whole_sell_categories_name = WholeSellCategory::where('id', $request->whole_sell_categories_id)->value('name') ?? NULL;

        $wholeSellProduct->city_name = $wholeSellProduct->city->name ?? '';
        $wholeSellProduct->state_name = $wholeSellProduct->state->name ?? '';
        $wholeSellProduct->country_name = $wholeSellProduct->country->name ?? '';

        unset($wholeSellProduct->city);
        unset($wholeSellProduct->state);
        unset($wholeSellProduct->country);

        $name = $wholeSellProduct->serviceProvider ? $wholeSellProduct->serviceProvider->name : '';
        $title = 'New '.$wholeSellProduct->name.' whole Sell Product add.';

        createNotification($title, 'WholeSellProduct', $wholeSellProduct->id, 'created');

        return $this->sendResponse($wholeSellProduct, 'Whole Sell Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $wholeSellProduct = WholeSellProduct::where('status', 1)
                                                ->with(['serviceProvider'])
                                                ->where('id', $id)
                                                ->whereDate('end_date', '>', Carbon::today())
                                                ->first();

        if (!is_null($wholeSellProduct)) {

            $whole_sell_categories_name = WholeSellCategory::where('id', $wholeSellProduct->whole_sell_categories_id)->value('name') ?? NULL;
            unset($wholeSellProduct->whole_sell_categories_id);
            $wholeSellProduct->whole_sell_categories_name = $whole_sell_categories_name;

            $wholeSellProduct->city_name = $wholeSellProduct->city->name ?? '';
            $wholeSellProduct->state_name = $wholeSellProduct->state->name ?? '';
            $wholeSellProduct->country_name = $wholeSellProduct->country->name ?? '';

            unset($wholeSellProduct->city);
            unset($wholeSellProduct->state);
            unset($wholeSellProduct->country);

            return $this->sendResponse($wholeSellProduct, 'Product retrieved successfully.');

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
            'whole_sell_categories_id' => 'required',
            'name' => 'required',
            'amount' => 'required',
            'end_date' => 'required',
            'min_qty' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors()->first(), []);
        }

        if (isset($input['images']) && !empty($input['images'])) {
            $images = [];
            foreach ($input['images'] as $value) {
                $images[] = ImageUpload::upload('uploads/product/images/', $value);
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

        $wholeSellProduct = WholeSellProduct::find($id);
        if ($wholeSellProduct) {

            $wholeSellProduct->update($input);
            unset($wholeSellProduct->whole_sell_categories_id);

            if (!empty($wholeSellProduct->images)) {
                $newImages = [];
                $images = explode(" || ", $wholeSellProduct->images);
                foreach ($images as $value) {
                    $newImages[] = $value;
                }

                $wholeSellProduct->images = $newImages[0] ? $newImages[0] : '';
            }

            $wholeSellProduct->whole_sell_categories_name = WholeSellCategory::where('id', $request->whole_sell_categories_id)->value('name') ?? NULL;

            $wholeSellProduct->city_name = $wholeSellProduct->city->name ?? '';
            $wholeSellProduct->state_name = $wholeSellProduct->state->name ?? '';
            $wholeSellProduct->country_name = $wholeSellProduct->country->name ?? '';

            unset($wholeSellProduct->city);
            unset($wholeSellProduct->state);
            unset($wholeSellProduct->country);

            $name = $wholeSellProduct->serviceProvider ? $wholeSellProduct->serviceProvider->name : '';
            $title = 'New '.$wholeSellProduct->name.' whole Sell Product add.';

            createNotification($title, 'WholeSellProduct', $wholeSellProduct->id, 'update');

            return $this->sendResponse($wholeSellProduct, 'Product created successfully.');
        }else{
            return $this->sendError('Product not found.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $wholeSellProduct = WholeSellProduct::find($id);
        if (!is_null($wholeSellProduct)) {

            $wholeSellProduct->delete();
            return $this->sendResponse([], 'Product Deleted successfully.');

        }else{
            return $this->sendError('Product not found.');
        }
    }

    public function category(Request $request)
    {
        $wholeSellCategory = WholeSellCategory::where('status', 1)
        ->orderByRaw('name COLLATE utf8mb4_unicode_ci')
        ->get();

        return $this->sendResponse($wholeSellCategory, 'whole Sell Category retrieved successfully.');
    }

    public function byUser($user)
    {
        $wholeSellProduct = WholeSellProduct::with('wholeSellCategory')->with('serviceProvider')
                   ->where('user_id', $user)
                   ->where('status', 1)
                   ->whereDate('end_date', '>', Carbon::today())
                   ->orderBy('created_at', 'desc')
                   ->get();
        $wholeSellProduct = $wholeSellProduct->map(function ($wholeSellProduct) {
            $wholeSellProduct->whole_sell_categories_name = $wholeSellProduct->wholeSellCategory ? $wholeSellProduct->wholeSellCategory->name : null;
            if (!empty($wholeSellProduct->images)) {
                $newImages = [];
                $images = explode(" || ", $wholeSellProduct->images);
                foreach ($images as $value) {
                    $newImages[] = $value;
                }

                $wholeSellProduct->images = $newImages[0] ? $newImages[0] : '';
            }
            $wholeSellProduct->city_name = $wholeSellProduct->city->name ?? '';
            $wholeSellProduct->state_name = $wholeSellProduct->state->name ?? '';
            $wholeSellProduct->country_name = $wholeSellProduct->country->name ?? '';

            unset($wholeSellProduct->city);
            unset($wholeSellProduct->state);
            unset($wholeSellProduct->country);

            unset($wholeSellProduct->wholeSellCategory);
            return $wholeSellProduct;
        });

        return $this->sendResponse($wholeSellProduct, 'Product retrieved successfully.');
    }
}
