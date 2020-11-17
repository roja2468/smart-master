<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Auth::routes(['verify' => true]);
Route::group([
    'namespace' => 'Api',
    'middleware' => 'ApiRequest'
], function () {
    Route::post('GetPrivacyPolicy', 'ContentController@GetPrivacyPolicy');
	Route::post('DoLogin', 'AuthController@DoLogin');
	Route::post('DoRegister', 'AuthController@DoRegister');
    Route::post('SendOtp', 'AuthController@SendOtp');
    Route::post('VerifyOtp', 'AuthController@VerifyOtp');
    Route::post('Signup', 'AuthController@Signup');
    Route::post('Logout', 'AuthController@logout');
    Route::post('ForgotPassword', 'AuthController@ForgotPassword');
    Route::group([
        'middleware' => [
            'APIToken',
            // 'auth:api'
        ]
    ], function() {
        Route::post('UpdateToken', 'AuthController@updateToken');
        Route::post('GetUserProfile', 'UserController@GetUserProfile');
        Route::post('UpdateProfile', 'UserController@UpdateProfile');
        Route::post('GetMainCategory', 'CategoryController@GetMainCategory');
        Route::post('GetSubCategory', 'CategoryController@GetSubCategory');
        Route::post('GetChildCategory', 'CategoryController@GetChildCategory');
        Route::post('GetTopic', 'CategoryController@GetTopic');
        Route::post('GetVideo', 'CategoryController@GetVideo');
        Route::post('GetDocument', 'CategoryController@GetDocument');
        Route::post('GetPackage', 'CategoryController@GetPackage');
        Route::post('GetBanner', 'ContentController@GetBanner');
        Route::post('GetAboutUs', 'ContentController@GetAboutUs');
        Route::post('GetFaq', 'ContentController@GetFaq');
        Route::post('PostFeedback', 'ContentController@PostFeedback');
        Route::post('GetAppNotification', 'ContentController@GetAppNotification');
        Route::post('ReadAppNotification', 'ContentController@ReadAppNotification');
        Route::post('GetReferralAmount', 'AuthController@GetReferralAmount');
        Route::post('WithdrawAmount', 'AuthController@WithdrawAmount');
        Route::post('GetWalletHistory', 'AuthController@GetWalletHistory');
        Route::post('GenerateCashfreeOrderToken', 'UserController@GenerateCashfreeOrderToken');
        Route::post('VideoCount', 'CategoryController@VideoCount');
        Route::post('DocumentCount', 'CategoryController@DocumentCount');
        Route::post('OrderCheckout', 'UserController@OrderCheckout');
        Route::post('GetMyPackage', 'UserController@GetMyPackage');
        Route::post('IgnorePackageProfile', 'UserController@IgnorePackageProfile');
        Route::post('GetReferralCodeCommission', 'UserController@GetReferralCodeCommission');
        Route::post('GetExpirePackage', 'UserController@GetExpirePackage');
        Route::post('ChangePassword', 'AuthController@ChangePassword');
    });
});