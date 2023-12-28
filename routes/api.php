<?php

use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\frontend\v1\BrandController;
use App\Http\Controllers\frontend\v1\ExportController;
use App\Http\Controllers\frontend\v1\saleAreaController;
use App\Http\Controllers\frontend\v1\UserController;
use App\Http\Controllers\frontend\v1\WelcomeDataController;
use Illuminate\Support\Facades\Route;

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
// login and logout api
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::get('/saleArea', [AuthController::class, 'getSalesArea']);

    Route::post('/forget-password', [AuthController::class, 'forgetPassword']);
    Route::put('/reset-password/{token}', [AuthController::class, 'resetPassword']);

});

// saleArea data for register


Route::middleware('auth:sanctum')->group(function () {
    // user api
    Route::get('/get/userData', [UserController::class, 'getUserData']);
    Route::delete('delete/user/{id}', [UserController::class, 'deleteUser']);
    Route::get('/get/roles', [UserController::class, 'getRoles']);
    Route::post('/update/user/role', [UserController::class, 'updateUserRoles']);

    // welcome data api
    Route::get('/get/welcome-data', [WelcomeDataController::class, 'getWelcomeData']);
    Route::post('/create/welcome-data', [WelcomeDataController::class, 'createWelcomeData']);
    Route::delete('delete/welcome-data/{id}', [WelcomeDataController::class, 'deleteWelcomeData']);


    // sale areas api
    Route::get('/get/sale-area', [saleAreaController::class, 'getSaleArea']);
    Route::delete('delete/sale-area/{id}', [saleAreaController::class, 'deleteSaleArea']);
    Route::get('/get/regions', [saleAreaController::class, 'getRegions']);
    Route::post('/create/region', [saleAreaController::class, 'createRegion']);
    Route::post('/create/sale-area', [saleAreaController::class, 'createSaleArea']);

    // brand api
    Route::get('/get/brand', [BrandController::class, 'getBrand']);
    Route::delete('delete/brand/{id}', [BrandController::class, 'deleteBrand']);
    Route::post('/create/brand', [BrandController::class, 'createBrand']);


    //exel file download
    Route::get('/export/excel', [ExportController::class, 'exportWelcomeData']);




    //get brand data api
    Route::get('/get/brandData', [WelcomeDataController::class, 'getBrandData']);
    // get all remark field data
    Route::get('/get/all/remark-data', [WelcomeDataController::class, 'getAllRemarkFieldData']);
    //get install process data api
    Route::get('get/install-data', [WelcomeDataController::class, 'getInstallProcessData']);
    //get remark field data api
    Route::get('/get/remark-data/{id}', [WelcomeDataController::class, 'getRemarkData']);
    // get applicant response data api
    Route::get('/get/response-data/{id}', [WelcomeDataController::class, 'getResponseData']);


});
