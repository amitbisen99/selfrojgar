<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ImageUpload;
use App\Models\ProductRating;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\BaseController as BaseController;
use DB;
use Carbon\Carbon;


class ProductController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('getProductCategory')->with('seller')
                   ->where('status', 1)
                   ->withAvg('ratings', 'rating')
                   ->whereDate('end_date', '>', Carbon::today())
                   ->orderBy('created_at', 'desc')
                   ->get();
        $products = $products->map(function ($product) {
            $product->product_category_name = $product->getProductCategory ? $product->getProductCategory->name : null;
            if (!empty($product->images)) {
                $newImages = [];
                $images = explode(" || ", $product->images);
                foreach ($images as $value) {
                    $newImages[] = $value;
                }

                $product->images = $newImages[0] ? $newImages[0] : '';
            }
            $product->city_name = $product->city->name ?? '';
            $product->state_name = $product->state->name ?? '';
            $product->country_name = $product->country->name ?? '';

            unset($product->city);
            unset($product->state);
            unset($product->country);
            unset($product->getProductCategory);
            return $product;
        });

        return $this->sendResponse($products, 'Product retrieved successfully.');
    }

    public function filter(Request $request)
    {
        $query = Product::query();

        if($request->user_id){
            $query = $query->where('user_id', $request->user_id);
        }

        if($request->product_categories_id){
            $query = $query->where('product_categories_id', $request->product_categories_id);
        }

        if ($request->product_name) {
            $query = $query->where('product_name', 'like', '%'.$request->product_name.'%')
                                ->orWhereHas('getProductCategory', function ($subQuery) use ($request) {
                                      $subQuery->where('name', 'like', '%' . $request->product_name . '%');
                                  });
        }

        if ($request->brand_name) {
            $query = $query->where('brand_name', 'like', '%'.$request->brand_name.'%');
        }

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        } elseif ($request->start_date) {
            $query->where('created_at', '>=', $request->start_date);
        } elseif ($request->end_date) {
            $query->where('created_at', '<=', $request->end_date);
        }

        if ($request->from_price && $request->to_price) {
            $query->whereBetween('price', [$request->from_price, $request->to_price]);
        } elseif ($request->from_price) {
            $query->where('price', '>=', $request->from_price);
        } elseif ($request->to_price) {
            $query->where('price', '<=', $request->to_price);
        }

        if ($request->latitude && $request->longitude && $request->radius) {
            $query->where(haversineFormula($request->latitude, $request->longitude, $request->radius));
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

        $products = $query->where('products.status', 1)
                   ->withAvg('ratings', 'rating')->with('seller')
                   ->orderBy('created_at', 'desc')
                   ->select('products.*')
                   ->whereDate('end_date', '>', Carbon::today())
                   ->get();

        foreach ($products as $key => $product) {
            $product->product_categories_name = ProductCategory::find($product->product_categories_id);
            $product->product_categories_name = $product->product_categories_name->name ?? '';
            unset($product->product_categories_id);

            $product_images = "";
            if (!empty($product->images)) {
                $newImages = [];
                $images = explode(" || ", $product->images);
                foreach ($images as $value) {
                    $newImages[] = $value;
                }

                $product->images = $newImages[0] ? $newImages[0] : '';
            }
            $product->city_name = $product->city->name ?? '';
            $product->state_name = $product->state->name ?? '';
            $product->country_name = $product->country->name ?? '';

            unset($product->city);
            unset($product->state);
            unset($product->country);
        }

        return $this->sendResponse($products, 'Product retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'user_id' => 'required',
            'product_categories_id' => 'required',
            'brand_name' => 'required',
            'product_name' => 'required',
            'price' => 'required',
            'images' => 'required',
            'end_date' => 'required',
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

        $product = Product::create($input);

        unset($product->product_categories_id);

        if (!empty($product->images)) {
            $newImages = [];
            $images = explode(" || ", $product->images);
            foreach ($images as $value) {
                $newImages[] = $value;
            }

            $product->images = $newImages[0] ? $newImages[0] : '';
        }

        $product->city_name = $product->city->name ?? '';
        $product->state_name = $product->state->name ?? '';
        $product->country_name = $product->country->name ?? '';

        unset($product->city);
        unset($product->state);
        unset($product->country);

        $product->product_categories_name = ProductCategory::where('id', $request->product_categories_id)->value('name') ?? NULL;

        $name = $product->seller ? $product->seller->name : '';
        $title = 'New '.$product->product_name.' product available.';

        createNotification($title, 'Buy-Sell Product', $product->id, 'created');

        return $this->sendResponse($product, 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $product = Product::where('status', 1)
                   ->withAvg('ratings', 'rating')
                   ->where('id', $id)->with('seller')
                   ->whereDate('end_date', '>', Carbon::today())
                   ->first();
        if (!is_null($product)) {

            $product_categories_name = ProductCategory::where('id', $product->product_categories_id)->value('name') ?? NULL;
            unset($product->product_categories_id);
            $product->product_categories_name = $product_categories_name;
            $product->city_name = $product->city->name ?? '';
            $product->state_name = $product->state->name ?? '';
            $product->country_name = $product->country->name ?? '';

            unset($product->city);
            unset($product->state);
            unset($product->country);
            return $this->sendResponse($product, 'Product retrieved successfully.');

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
            'product_categories_id' => 'required',
            'brand_name' => 'required',
            'product_name' => 'required',
            'price' => 'required',
            'end_date' => 'required',
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

        $product = Product::find($id);
        if ($product) {

            $product->update($input);
            $product = Product::where('status', 1)
                   ->withAvg('ratings', 'rating')
                   ->where('id', $id)->with('seller')
                   ->first();

            unset($product->product_categories_id);
            $product->product_categories_name = ProductCategory::where('id', $request->product_categories_id)->value('name') ?? NULL;

            if (!empty($product->images)) {
                $newImages = [];
                $images = explode(" || ", $product->images);
                foreach ($images as $value) {
                    $newImages[] = $value;
                }

                $product->images = $newImages[0] ? $newImages[0] : '';
            }
            $product->city_name = $product->city->name ?? '';
            $product->state_name = $product->state->name ?? '';
            $product->country_name = $product->country->name ?? '';

            unset($product->city);
            unset($product->state);
            unset($product->country);
            $name = $product->seller ? $product->seller->name : '';
            $title = 'New '.$product->product_name.' product available.';

            createNotification($title, 'Buy-Sell Product', $product->id, 'update');

            return $this->sendResponse($product, 'Product created successfully.');
        }else{
            return $this->sendError('Product not found.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);
        if (!is_null($product)) {

            $product->delete();
            return $this->sendResponse([], 'Product Deleted successfully.');

        }else{
            return $this->sendError('Product not found.');
        }
    }

    public function category(Request $request)
    {
        $productCategory = ProductCategory::where('status', 1)
        ->orderByRaw('name COLLATE utf8mb4_unicode_ci')
        ->get();

        return $this->sendResponse($productCategory, 'Product Category retrieved successfully.');
    }

    public function rating(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'user_id' => 'required',
            'product_id' => 'required',
            'rating' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError($validator->errors()->first(), []);
        }

        ProductRating::create($input);

        return $this->sendResponse([], 'Product Rating added successfully.');
    }

    public function byUser($user)
    {
        $products = Product::with('getProductCategory')
                   ->where('status', 1)
                   ->where('user_id', $user)
                   ->withAvg('ratings', 'rating')->with('seller')
                   ->orderBy('created_at', 'desc')
                   ->whereDate('end_date', '>', Carbon::today())
                   ->get();
        $products = $products->map(function ($product) {
            $product->product_category_name = $product->getProductCategory ? $product->getProductCategory->name : null;
            if (!empty($product->images)) {
                $newImages = [];
                $images = explode(" || ", $product->images);
                foreach ($images as $value) {
                    $newImages[] = $value;
                }

                $product->images = $newImages[0] ? $newImages[0] : '';
            }
            unset($product->getProductCategory);
            $product->city_name = $product->city->name ?? '';
            $product->state_name = $product->state->name ?? '';
            $product->country_name = $product->country->name ?? '';

            unset($product->city);
            unset($product->state);
            unset($product->country);
            return $product;
        });

        if ($products->count() > 0) {
            return $this->sendResponse($products, 'Product retrieved successfully.');
        }else{
            return $this->sendResponse([], 'Product not found.');
        }
    }
}
