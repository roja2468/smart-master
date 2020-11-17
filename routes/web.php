<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
$middleware = [];
if(Config::get('app.cacheclear')){
	array_push($middleware, ['middleware' => 'clearcache']);
}
Route::get('/user_cron', 'Api\UserController@CronOfUserPaidFree');
//Route::POST('/user_remark', 'Admin\UserController@user_remark')->name('admin.user_remark');

Route::get('/', 'HomeController@privacy_policy');
Route::get('/guidlines', 'HomeController@guidlines');
Route::get('/Refund-Policy', 'HomeController@refund');
Auth::routes(['verify' => true,'register' => false,'reset' => true]);
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/admin', 'Admin\LoginController@showLoginForm')->name('admin.login')->middleware('guest');
Route::group(['middleware' => ['auth','cors'],'prefix' => 'admin','namespace' => 'Admin','as' => 'admin.'], function() {

	Route::get('/test_payment','HomeController@test_payment');
	Route::get('/payment-success','HomeController@success');
	Route::get('/payment-notify','HomeController@notify');

	Route::get('/dashboard', 'HomeController@dashboard')->name('home');
	Route::get('/profile', 'HomeController@profile')->name('profile');
	Route::POST('/profile-update', 'HomeController@update_profile')->name('update.profile');
	Route::POST('/change-password', 'HomeController@change_password')->name('change.password');
	
	Route::group(['prefix' => 'category','as' => 'category.'], function() {
		Route::get('/', 'CategoryController@list')->name('list');
		Route::POST('/datatable', 'CategoryController@datatable')->name('data');
		Route::get('/add', 'CategoryController@add')->name('add');
		Route::get('/unique', 'CategoryController@unique')->name('unique');
		/*Route::POST('/sub-category', 'CategoryController@sub_category')->name('sub_category');*/
		Route::POST('/save', 'CategoryController@save')->name('save');
		Route::get('/edit/{id}','CategoryController@edit')->name('edit');
		Route::POST('/update/{id}','CategoryController@update')->name('update');
		Route::POST('/delete','CategoryController@delete')->name('delete');
	});

	Route::group(['prefix' => 'slider','as' => 'slider.'], function() {
		Route::get('/', 'SliderController@list')->name('list');
		Route::POST('/datatable', 'SliderController@datatable')->name('data');
		Route::get('/add', 'SliderController@add')->name('add');
		Route::POST('/save', 'SliderController@save')->name('save');
		Route::get('/edit/{id}','SliderController@edit')->name('edit');
		Route::POST('/update/{id}','SliderController@update')->name('update');
		Route::POST('/delete','SliderController@delete')->name('delete');
		Route::POST('/change-active-status','SliderController@change_active_status')->name('change_active_status');
	});

	Route::group(['prefix' => 'sub-categories','as' => 'sub_categories.'], function() {
		Route::get('/', 'SubCategoryController@list')->name('list');
		Route::POST('/datatable', 'SubCategoryController@datatable')->name('data');
		Route::get('/add', 'SubCategoryController@add')->name('add');
		Route::get('/unique', 'SubCategoryController@unique')->name('unique');
		Route::POST('/save', 'SubCategoryController@save')->name('save');
		Route::get('/edit/{id}','SubCategoryController@edit')->name('edit');
		Route::POST('/update/{id}','SubCategoryController@update')->name('update');
		Route::POST('/delete','SubCategoryController@delete')->name('delete');
	});

	Route::group(['prefix' => 'child-categories','as' => 'child_categories.'], function() {
		Route::get('/', 'ChildCategoryController@list')->name('list');
		Route::POST('/datatable', 'ChildCategoryController@datatable')->name('data');
		Route::get('/add', 'ChildCategoryController@add')->name('add');
		Route::get('/unique', 'ChildCategoryController@unique')->name('unique');
		Route::POST('/save', 'ChildCategoryController@save')->name('save');
		Route::get('/edit/{id}','ChildCategoryController@edit')->name('edit');
		Route::POST('/update/{id}','ChildCategoryController@update')->name('update');
		Route::POST('/delete','ChildCategoryController@delete')->name('delete');
		Route::POST('/get_sub_category','ChildCategoryController@get_sub_category')->name('get_sub_category');
		Route::POST('/get_child_category','ChildCategoryController@get_child_category')->name('get_child_category');
	});

	Route::group(['prefix' => 'topic','as' => 'topic.'], function() {
		Route::get('/', 'TopicController@list')->name('list');
		Route::POST('/datatable', 'TopicController@datatable')->name('data');
		Route::get('/add', 'TopicController@add')->name('add');
		Route::get('/unique', 'TopicController@unique')->name('unique');
		/*Route::POST('/sub-category', 'TopicController@sub_category')->name('sub_category');*/
		Route::POST('/save', 'TopicController@save')->name('save');
		Route::get('/edit/{id}','TopicController@edit')->name('edit');
		Route::POST('/update/{id}','TopicController@update')->name('update');
		Route::POST('/delete','TopicController@delete')->name('delete');
		Route::POST('/delete-document','TopicController@document_delete')->name('document.delete');
		Route::POST('/delete-video','TopicController@video_delete')->name('video.delete');
		
	});

	Route::group(['prefix' => 'topic-video','as' => 'topic.video.'], function() {
		Route::get('/', 'TopicVideoController@list')->name('list');
		Route::POST('/datatable', 'TopicVideoController@datatable')->name('data');
		Route::get('/add', 'TopicVideoController@add')->name('add');
		Route::POST('/save', 'TopicVideoController@save')->name('save');
		Route::get('/edit/{id}','TopicVideoController@edit')->name('edit');
		Route::POST('/update/{id}','TopicVideoController@update')->name('update');
		Route::POST('/delete','TopicVideoController@delete')->name('delete');
	});

	Route::group(['prefix' => 'topic-document','as' => 'topic.document.'], function() {
		Route::get('/', 'TopicDocumentController@list')->name('list');
		Route::POST('/datatable', 'TopicDocumentController@datatable')->name('data');
		Route::get('/add', 'TopicDocumentController@add')->name('add');
		Route::POST('/save', 'TopicDocumentController@save')->name('save');
		Route::get('/edit/{id}','TopicDocumentController@edit')->name('edit');
		Route::POST('/update/{id}','TopicDocumentController@update')->name('update');
		Route::POST('/delete','TopicDocumentController@delete')->name('delete');
	});

	Route::group(['prefix' => 'comments','as' => 'comment.'], function() {
		Route::get('/', 'CommentController@list')->name('list');
		Route::POST('/datatable', 'CommentController@datatable')->name('data');
		Route::POST('/change-approve-dis-approve', 'CommentController@change_approve_status')->name('change_approve_status');
		Route::POST('/comment-info', 'CommentController@comment_info')->name('comment_info');
	});

	Route::group(['prefix' => 'contact','as' => 'contact.'], function() {
		Route::get('/', 'ContactController@list')->name('list');
		Route::POST('/datatable', 'ContactController@datatable')->name('data');
		Route::POST('/comment-info', 'ContactController@contact_info')->name('contact_info');
	});

	Route::group(['prefix' => 'users','as' => 'user.'], function() {
		Route::get('/', 'UserController@list')->name('list');
		Route::POST('/datatable', 'UserController@datatable')->name('data');
		Route::POST('/user-info', 'UserController@user_info')->name('user_info');
		Route::POST('/user-remark', 'UserController@user_remark')->name('user_remark');
		Route::POST('/change-block-status', 'UserController@change_block_status')->name('change_block_status');
      
		Route::get('/edit/{id}','UserController@edit')->name('edit');
		Route::POST('/update/{id}','UserController@update')->name('update');
	});

	Route::group(['prefix' => 'user-packages','as' => 'user.package.'], function() {
		Route::get('/', 'UserController@user_package_list')->name('list');
		Route::POST('/datatable', 'UserController@user_package_datatable')->name('data');
		Route::POST('/package-info', 'UserController@user_package_user_info')->name('package_info');
	});

	Route::group(['prefix' => 'content','as' => 'content.'], function() {
		Route::get('/', 'ContentController@list')->name('list');
	});

	Route::group(['prefix' => 'about-us','as' => 'about_us.'], function() {
		Route::get('/', 'ContentController@about_list')->name('list');
		Route::get('/about-us-edit/{id}','ContentController@editAboutUs')->name('edit');
		Route::post('/about-us-update/{id}','ContentController@updateAboutUs')->name('update');
	});

	Route::group(['prefix' => 'privacy-policy','as' => 'privacy_policy.'], function() {
		Route::get('/', 'ContentController@privacyPolicyList')->name('list');
		Route::get('/edit/{id}','ContentController@editPrivacyPolicy')->name('edit');
		Route::post('/update/{id}','ContentController@updatePrivacyPolicy')->name('update');
	});

	Route::group(['prefix' => 'notification','as' => 'notification.'], function() {
		Route::get('/', 'NotificationController@addPushNotification')->name('addPushNotification');
		Route::post('/send', 'NotificationController@sendPushNotification')->name('sendPushNotification');
	});

	Route::group(['prefix' => 'mail-notification','as' => 'mail_notification.'], function() {
		Route::get('/', 'NotificationController@addMailPushNotification')->name('addPushNotification');
		Route::post('/send', 'NotificationController@sendMailPushNotification')->name('sendMailPushNotification');
		Route::get('/test', function(){
			return view('Mail.mail_notification');
		});
	});

	Route::group(['prefix' => 'package','as' => 'package.'], function() {
		Route::get('/', 'PackageController@list')->name('list');
		Route::POST('/datatable', 'PackageController@datatable')->name('data');
		Route::get('/add', 'PackageController@add')->name('add');
		Route::POST('/save', 'PackageController@save')->name('save');
		Route::get('/edit/{id}','PackageController@edit')->name('edit');
		Route::POST('/update/{id}','PackageController@update')->name('update');
		Route::POST('/delete','PackageController@delete')->name('delete');
		Route::POST('/change-active-status', 'PackageController@change_active_status')->name('change_active_status');
	});

	Route::group(['prefix' => 'demo-article','as' => 'demo.article.'], function() {
		Route::get('/', 'DemoArticleController@list')->name('list');
		Route::POST('/datatable', 'DemoArticleController@datatable')->name('data');
		Route::get('/add', 'DemoArticleController@add')->name('add');
		Route::POST('/save', 'DemoArticleController@save')->name('save');
		Route::get('/edit/{id}','DemoArticleController@edit')->name('edit');
		Route::POST('/update/{id}','DemoArticleController@update')->name('update');
		Route::POST('/delete','DemoArticleController@delete')->name('delete');
		Route::POST('/change-active-status','DemoArticleController@change_active_status')->name('change_active_status');
	});

	Route::group(['prefix' => 'demo-video','as' => 'demo.video.'], function() {
		Route::get('/', 'DemoVideoController@list')->name('list');
		Route::POST('/datatable', 'DemoVideoController@datatable')->name('data');
		Route::get('/add', 'DemoVideoController@add')->name('add');
		Route::POST('/save', 'DemoVideoController@save')->name('save');
		Route::get('/edit/{id}','DemoVideoController@edit')->name('edit');
		Route::POST('/update/{id}','DemoVideoController@update')->name('update');
		Route::POST('/delete','DemoVideoController@delete')->name('delete');
		Route::POST('/change-active-status','DemoVideoController@change_active_status')->name('change_active_status');
	});

	Route::group(['prefix' => 'faq','as' => 'faq.'], function() {
		Route::get('/', 'FaqController@list')->name('list');
		Route::POST('/datatable', 'FaqController@datatable')->name('data');
		Route::get('/add', 'FaqController@add')->name('add');
		Route::POST('/save', 'FaqController@save')->name('save');
		Route::get('/edit/{id}','FaqController@edit')->name('edit');
		Route::POST('/update/{id}','FaqController@update')->name('update');
		Route::POST('/delete','FaqController@delete')->name('delete');
		Route::POST('/change-active-status', 'FaqController@change_active_status')->name('change_active_status');
	});

	Route::group(['prefix' => 'app-notification','as' => 'app_notification.'], function() {
		Route::get('/', 'AppNotificationController@index')->name('list');
		Route::POST('/datatable', 'AppNotificationController@datatable')->name('data');
		Route::get('/add', 'AppNotificationController@add')->name('add');
		Route::POST('/save', 'AppNotificationController@save')->name('save');
		Route::get('/edit/{id}','AppNotificationController@edit')->name('edit');
		Route::POST('/update/{id}','AppNotificationController@update')->name('update');
		Route::POST('/delete','AppNotificationController@delete')->name('delete');
		Route::POST('/change-active-status', 'AppNotificationController@change_active_status')->name('change_active_status');
	});

	Route::group(['prefix' => 'wallet','as' => 'wallet.'], function() {
		Route::get('/', 'WalletController@list')->name('list');
		Route::POST('/datatable', 'WalletController@datatable')->name('data');
		Route::POST('/change-approve-status', 'WalletController@change_approve_status')->name('change_approve_status');
	});
	
	Route::group(['prefix' => 'referral-amount','as' => 'referral_amount.'], function() {
		Route::get('/', 'WalletController@referral_amount_edit')->name('edit');
		Route::POST('/update/{id}','WalletController@referral_amount_update')->name('update');
	});

	Route::group(['prefix' => 'referral-amount-commission','as' => 'referral_amount_commission.'], function() {
		Route::get('/', 'WalletController@referral_amount_commission_edit')->name('edit');
		Route::POST('/update/{id}','WalletController@referral_amount_commission_update')->name('update');
	});
});
