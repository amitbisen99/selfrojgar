<?php

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\JobController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\JobIndustryController;
use App\Http\Controllers\Api\CountryStateCity;
use App\Http\Controllers\Api\JobApplyController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\EducationController;
use App\Http\Controllers\Api\ExperienceController;
use App\Http\Controllers\Api\ArtistController;
use App\Http\Controllers\Api\OnDemandServiceController;
use App\Http\Controllers\Api\WholeSellProductController;
use App\Http\Controllers\Api\FranchiseBusinessController;
use App\Http\Controllers\Api\TourismController;
use App\Http\Controllers\Api\BusinessesController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\AdvertisementController;
use App\Http\Controllers\Api\PropertyController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('register',[AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('forgot/password', [AuthController::class, 'forgotPassword']);
Route::post('change/password', [AuthController::class, 'changePassword']);
Route::post('social/login-registration', [AuthController::class, 'socialLogin']);
Route::post('delete/account', [AuthController::class, 'delete']);

Route::post('country', [CountryStateCity::class, 'country']);
Route::post('state', [CountryStateCity::class, 'state']);
Route::post('city', [CountryStateCity::class, 'city']);

Route::post('payment/create', [PaymentController::class, 'create']);

Route::middleware(['auth:api'])->group(function () {
    
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/tokens', [AuthController::class, 'tokens']);

    Route::get('job-industry', [JobIndustryController::class, 'index']);

    // Route::resource('job', JobController::class);
    Route::get('job', [JobController::class, 'index']);
    Route::get('job/show/{id}', [JobController::class, 'show']);
    Route::post('job/store', [JobController::class, 'store']);
    Route::post('job/update/{id}', [JobController::class, 'update']);
    Route::post('job/delete/{id}', [JobController::class, 'destroy']);
    
    Route::post('job/filter', [JobController::class, 'filter']);
    Route::post('job/by/user/{userID}', [JobController::class, 'jobByUser']);

    Route::post('job/apply', [JobApplyController::class, 'jobApply']);
    Route::post('job/apply/user/{user_id}', [JobApplyController::class, 'appliedByUser']);
    
    Route::post('profile/by/user/{userID}', [ProfileController::class, 'profileByUser']);
    Route::post('profile/update/by/user/{userID}', [ProfileController::class, 'profileUpdate']);

    Route::group(['prefix' => 'buy-sell/product'], function(){
        Route::get('category', [ProductController::class, 'category']);
        Route::get('/', [ProductController::class, 'index']);
        Route::get('show/{id}', [ProductController::class, 'show']);
        Route::post('store', [ProductController::class, 'store']);
        Route::post('update/{id}', [ProductController::class, 'update']);
        Route::post('delete/{id}', [ProductController::class, 'destroy']);
        Route::post('rating', [ProductController::class, 'rating']);
        Route::post('/by/user/{user}', [ProductController::class, 'byUser']);
        Route::post('filter', [ProductController::class, 'filter']);
        // Route::post('by/user/{userID}', [ProductController::class, 'jobByUser']);
    });
    Route::group(['prefix' => 'education'], function(){
        Route::get('/{user}', [EducationController::class, 'index']);
        Route::post('/create', [EducationController::class, 'create']);
    });

    Route::group(['prefix' => 'experiences'], function(){
        Route::get('/{user}', [ExperienceController::class, 'index']);
        Route::post('create', [ExperienceController::class, 'create']);
    });

    Route::group(['prefix' => 'artist'], function(){
        Route::get('genres', [ArtistController::class, 'genres']);
        Route::get('/', [ArtistController::class, 'index']);
        Route::get('show/{id}', [ArtistController::class, 'show']);
        Route::post('store', [ArtistController::class, 'store']);
        Route::post('update/{id}', [ArtistController::class, 'update']);
        Route::post('delete/{id}', [ArtistController::class, 'destroy']);
        Route::post('/by/user/{user}', [ArtistController::class, 'byUser']);
        Route::post('filter', [ArtistController::class, 'filter']);
    });

    Route::group(['prefix' => 'on-demand-service'], function(){
        Route::get('category', [OnDemandServiceController::class, 'category']);
        Route::get('/', [OnDemandServiceController::class, 'index']);
        Route::get('show/{id}', [OnDemandServiceController::class, 'show']);
        Route::post('store', [OnDemandServiceController::class, 'store']);
        Route::post('update/{id}', [OnDemandServiceController::class, 'update']);
        Route::post('delete/{id}', [OnDemandServiceController::class, 'destroy']);
        Route::post('/by/user/{user}', [OnDemandServiceController::class, 'byUser']);
        Route::post('filter', [OnDemandServiceController::class, 'filter']);
    });

    Route::group(['prefix' => 'whole-sell-product'], function(){
        Route::get('category', [WholeSellProductController::class, 'category']);
        Route::get('/', [WholeSellProductController::class, 'index']);
        Route::get('show/{id}', [WholeSellProductController::class, 'show']);
        Route::post('store', [WholeSellProductController::class, 'store']);
        Route::post('update/{id}', [WholeSellProductController::class, 'update']);
        Route::post('delete/{id}', [WholeSellProductController::class, 'destroy']);
        Route::post('/by/user/{user}', [WholeSellProductController::class, 'byUser']);
        Route::post('filter', [WholeSellProductController::class, 'filter']);
    });

    Route::group(['prefix' => 'franchise'], function(){
        Route::get('category', [FranchiseBusinessController::class, 'category']);
        Route::get('/', [FranchiseBusinessController::class, 'index']);
        Route::get('show/{id}', [FranchiseBusinessController::class, 'show']);
        Route::post('store', [FranchiseBusinessController::class, 'store']);
        Route::post('update/{id}', [FranchiseBusinessController::class, 'update']);
        Route::post('delete/{id}', [FranchiseBusinessController::class, 'destroy']);
        Route::post('/by/user/{user}', [FranchiseBusinessController::class, 'byUser']);
        Route::post('filter', [FranchiseBusinessController::class, 'filter']);
    });


    Route::group(['prefix' => 'tourism'], function(){
        Route::get('category', [TourismController::class, 'category']);
        Route::get('/', [TourismController::class, 'index']);
        Route::get('show/{id}', [TourismController::class, 'show']);
        Route::post('store', [TourismController::class, 'store']);
        Route::post('update/{id}', [TourismController::class, 'update']);
        Route::post('delete/{id}', [TourismController::class, 'destroy']);
        Route::post('/by/user/{user}', [TourismController::class, 'byUser']);
        Route::post('filter', [TourismController::class, 'filter']);
    });

    Route::group(['prefix' => 'businesses'], function(){
        Route::get('category', [BusinessesController::class, 'category']);
        Route::get('/', [BusinessesController::class, 'index']);
        Route::get('show/{id}', [BusinessesController::class, 'show']);
        Route::post('store', [BusinessesController::class, 'store']);
        Route::post('update/{id}', [BusinessesController::class, 'update']);
        Route::post('delete/{id}', [BusinessesController::class, 'destroy']);
        Route::post('/by/user/{user}', [BusinessesController::class, 'byUser']);
        Route::post('filter', [BusinessesController::class, 'filter']);
        Route::post('rating', [BusinessesController::class, 'rating']);
    });

    Route::group(['prefix' => 'advertisment'], function(){
        Route::get('/', [AdvertisementController::class, 'index']);
        Route::get('show/{id}', [AdvertisementController::class, 'show']);
        Route::post('store', [AdvertisementController::class, 'store']);
        Route::post('update/{id}', [AdvertisementController::class, 'update']);
        Route::post('delete/{id}', [AdvertisementController::class, 'destroy']);
        Route::post('/by/user/{user}', [AdvertisementController::class, 'byUser']);
        Route::post('filter', [AdvertisementController::class, 'filter']);
    });

    Route::group(['prefix' => 'property'], function(){
        Route::get('/', [PropertyController::class, 'index']);
        Route::get('show/{id}', [PropertyController::class, 'show']);
        Route::post('store', [PropertyController::class, 'store']);
        Route::post('update/{id}', [PropertyController::class, 'update']);
        Route::post('delete/{id}', [PropertyController::class, 'destroy']);
        Route::post('/by/user/{user}', [PropertyController::class, 'byUser']);
        Route::post('filter', [PropertyController::class, 'filter']);
        Route::get('category', [PropertyController::class, 'category']);
    });

    Route::group(['prefix' => 'notification'], function(){
        Route::get('/{id}', [NotificationController::class, 'index']);
    });

});

