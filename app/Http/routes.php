<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


/**
 * Route for API
 */

Route::get('dev', 'MerchantController@phaitest');

// Password reset link request routes...
Route::get('password/email', 'Auth\PasswordController@getEmail');
Route::post('password/email', 'Auth\PasswordController@postEmail');

// Password reset routes...
Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
Route::post('password/reset', 'Auth\PasswordController@postReset');
//Route for change password
Route::post('change-password-merchant' , 'MerchantController@changePasswordMerchant');


Route::controller('api', 'APIController');

Route::get('/', function () {
	return Redirect::to('index.html');
    // return view('welcome');
});

//For Manage
Route::get('/401','ErrorsController@manage401');

Route::get('/423',  function () {
	return redirect('login/manage')->withErrors('Tài khoản của bạn đã bị Deactive bởi Merchant');
});

//For Merchant
Route::get('/402','ErrorsController@merchant401');

Route::get('/424',  function () {
	return redirect('login')->withErrors('Tài khoản của bạn đã bị Deactive bởi SuperAdmin. Vui lòng liên hệ Admin để được trợ giúp');
});

Route::get('/404','ErrorsController@404');

Route::get('/500','ErrorsController@500');

Route::get('/503','ErrorsController@503');
/*
|--------------------------------------------------------------------------
| Route for Account
|--------------------------------------------------------------------------
|
*/


	Route::get('merchant-login' , 'Auth\AuthController@getLogin');
	Route::get('merchant-register' , 'Auth\AuthController@getRegister');
 



Route::controller('login' , 'Auth\AuthController');
Route::controller('register' , 'Auth\RegisterController');


// Route::get('login' , 'Auth\AuthController@index');

// Route::get('merchant-login' , 'Auth\AuthController@getLogin');

// Route::get('merchant-login' , 'Auth\AuthController@getLogin');
// Route::post('auth/login' , 'Auth\AuthController@postLogin');
Route::get('logout' , 'Auth\AuthController@getLogout');
Route::get('logout/admin', 'Auth\AuthController@getLogoutAdmin' );
Route::get('logout/manage', 'Auth\AuthController@getLogoutManage' );

Route::controller('manage' , 'ManageController');


// Route::get('register/merchant' , 'Auth\AuthController@getRegister');
// Route::post('register/merchant' , 'Auth\AuthController@postRegister');

Route::get('active/{token}' , 'Auth\AuthController@getActiveAccount');


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::controller('admincp', 'AdminController');


Route::controller('ajax' , 'AjaxController');

// Route::group(['middleware'=>'merchant'], function(){

	Route::group(['prefix'=>'/merchant'], function(){



		Route::get('/' , 'MerchantController@index');
		Route::post('merchant-feedbacks' , 'MerchantController@feedBacksMerchant');

		Route::get('upgrade' , 'MerchantController@upgradePackages');

		Route::post('upgrade-request-package' , 'MerchantController@upgradePackageRequest');


		Route::post('budget' , 'MerchantController@budget');
		

		Route::controller('initialize' , 'InitializeCardController');

		
		Route::get('initialize-card' , 'InitializeCardController@index');
		Route::post('store-shop' , 'InitializeCardController@storeShop');

		// Route::post('create-merchant' , 'InitializeCardController@store');
		// Route::post('create-type-card' , 'InitializeCardController@createTypeCard');
		// Route::post('store-shop' , 'InitializeCardController@storeShop' );

		Route::post('register-merchants' , 'InitializeCardController@registerMerchants');

		Route::get('get-all-info-packages' , 'InitializeCardController@getInfoPackages');


		Route::delete('destroy-store', 'InitializeCardController@destroy');
		Route::post('delete-image', 'InitializeCardController@unlinkImage');


		Route::post('config-active-store' , 'InitializeCardController@configActiveStore');
		Route::post('config-inactive-store' , 'InitializeCardController@configInactiveStore');

		// Route::post('update-name-store', 'InitializeCardController@updateNameAndAddressStore');

		

		

		Route::get('send-messages' , 'MessagesController@index');
		Route::post('store-messages' , 'MessagesController@store');

		Route::get('create-incentives' , 'IncentivesController@index');
		Route::post('store-incentives' , 'IncentivesController@store');
		Route::post('update-incentives' , 'IncentivesController@update');
		Route::post('delete-incentives', 'IncentivesController@delete');
		Route::post('active-incentives', 'IncentivesController@active');
		Route::post('edit-incentives', 'IncentivesController@edit');
		
		Route::any('upload-image', 'IncentivesController@upload');
		Route::get('feedback' , 'FeedbackController@index');

		Route::get('account-manage' , 'AccountManageController@index');
		Route::post('filter-account' , 'AccountManageController@filterAccount');
		Route::post('get-filter-account/id' , 'AccountManageController@getAccountByFilter');
		

		Route::get('detail/{id}' , 'AccountManageController@memberDetail');

		Route::get('analytics' , 'AnalyticsController@index');

		Route::get('transfer-history' , 'MerchantController@transferHistory');

		Route::get('district','InitializeCardController@getDistrict');
	});
// });

Route::group(['middleware' => ['web']], function () {
    //
});
