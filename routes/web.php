<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminHomeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\JobController;
use App\Http\Controllers\Admin\JobIndustryController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\StateController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\GenresController;
use App\Http\Controllers\Admin\ArtistController;
use App\Http\Controllers\Admin\OnDemandCategoryController;
use App\Http\Controllers\Admin\OnDemandServiceController;
use App\Http\Controllers\Admin\WholeSellCategoryController;
use App\Http\Controllers\Admin\WholeSellProductController;
use App\Http\Controllers\Admin\FranchiseCategoryController;
use App\Http\Controllers\Admin\FranchiseBusinessController;
use App\Http\Controllers\Admin\TourismCategoryController;
use App\Http\Controllers\Admin\TourismController;
use App\Http\Controllers\Admin\BusinessCategoryController;
use App\Http\Controllers\Admin\BusinessesController;
use App\Http\Controllers\Admin\AdvertisementController;
use App\Http\Controllers\Admin\PropertyCategoryController;
use App\Http\Controllers\Admin\PropertyController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes();


Route::get('/privacypolicy', function () {
    return response()->file(public_path('privacypolicy.html'));
});

Route::get('/termAndCondition', function () {
    return response()->file(public_path('termAndCondition.html'));
});


Route::get('/deleteAccount', function () {
    return response()->file(public_path('deleteAccount.html'));
});


Route::get('/', function () {
    return redirect('login');
});


Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function(){
    
    Route::get('dashboard', [AdminHomeController::class, 'index'])->name('dashboard'); 
    Route::get('setting', [AdminHomeController::class, 'setting'])->name('dashboard.setting'); 
    Route::post('setting/update', [AdminHomeController::class, 'settingUpdate'])->name('dashboard.setting.update'); 

    Route::get('storage/logs', [AdminHomeController::class, 'log'])->name('laravelLogs.index');
    Route::get('clear/logs', [AdminHomeController::class, 'clearLog'])->name('clear.logs');
    
    Route::resource('user', UserController::class);
    Route::post('user/status', [UserController::class, 'statusUpdate'])->name('user.status'); 
    
    Route::resource('job', JobController::class);
    Route::post('job/status', [JobController::class, 'statusUpdate'])->name('job.status'); 

    Route::resource('industry-job', JobIndustryController::class);
    Route::post('industry-job/status', [JobIndustryController::class, 'statusUpdate'])->name('industry-job.status'); 

    Route::resource('country', CountryController::class);
    Route::post('country/status', [CountryController::class, 'statusUpdate'])->name('country.status'); 

    Route::resource('state', StateController::class);
    Route::post('state/status', [StateController::class, 'statusUpdate'])->name('state.status'); 

    Route::resource('city', CityController::class);
    Route::post('city/status', [CityController::class, 'statusUpdate'])->name('city.status'); 

    Route::resource('product-category', ProductCategoryController::class);
    Route::post('product-category/status', [ProductCategoryController::class, 'statusUpdate'])->name('product-category.status');

    Route::resource('product', ProductController::class);
    Route::post('product/status', [ProductController::class, 'statusUpdate'])->name('product.status'); 

    Route::resource('payment', PaymentController::class);

    Route::resource('artist', ArtistController::class);
    Route::post('artist/status', [ArtistController::class, 'statusUpdate'])->name('artist.status'); 

    Route::resource('genres', GenresController::class);
    Route::post('genres/status', [GenresController::class, 'statusUpdate'])->name('genres.status'); 

    Route::resource('genres', GenresController::class);
    Route::post('genres/status', [GenresController::class, 'statusUpdate'])->name('genres.status'); 

    Route::resource('on-demand-category', OnDemandCategoryController::class);
    Route::post('on-demand-category/status', [OnDemandCategoryController::class, 'statusUpdate'])->name('on-demand-category.status'); 

    Route::resource('on-demand-service', OnDemandServiceController::class);
    Route::post('on-demand-service/status', [OnDemandServiceController::class, 'statusUpdate'])->name('on-demand-service.status'); 

    Route::resource('whole-sell-category', WholeSellCategoryController::class);
    Route::post('whole-sell-category/status', [WholeSellCategoryController::class, 'statusUpdate'])->name('whole-sell-category.status'); 

    Route::resource('whole-sell-product', WholeSellProductController::class);
    Route::post('whole-sell-product/status', [WholeSellProductController::class, 'statusUpdate'])->name('whole-sell-product.status');

    Route::resource('franchise-category', FranchiseCategoryController::class);
    Route::post('franchise-category/status', [FranchiseCategoryController::class, 'statusUpdate'])->name('franchise-category.status');

    Route::resource('franchise-business', FranchiseBusinessController::class);
    Route::post('franchise-business/status', [FranchiseBusinessController::class, 'statusUpdate'])->name('franchise-business.status');

    Route::resource('tourism-category', TourismCategoryController::class);
    Route::post('tourism-category/status', [TourismCategoryController::class, 'statusUpdate'])->name('tourism-category.status');

    Route::resource('tourism-business', TourismController::class);
    Route::post('tourism-business/status', [TourismController::class, 'statusUpdate'])->name('tourism-business.status');

    Route::resource('business-category', BusinessCategoryController::class);
    Route::post('business-category/status', [BusinessCategoryController::class, 'statusUpdate'])->name('business-category.status');

    Route::resource('businesses', BusinessesController::class);
    Route::post('businesses/status', [BusinessesController::class, 'statusUpdate'])->name('businesses.status');

    Route::resource('advertisement', AdvertisementController::class);
    Route::post('advertisement/status', [AdvertisementController::class, 'statusUpdate'])->name('advertisement.status');

    Route::resource('property-category', PropertyCategoryController::class);
    Route::post('property-category/status', [PropertyCategoryController::class, 'statusUpdate'])->name('property-category.status');

    Route::resource('propertyes', PropertyController::class);
    Route::post('propertyes/status', [PropertyController::class, 'statusUpdate'])->name('propertyes.status');
});
