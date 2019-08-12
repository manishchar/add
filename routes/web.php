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



Auth::routes();

Route::get('my',function(){
	echo date('y-m-d H:i:s a');
	// $exitCode = Artisan::call('cache:clear');
 //    return "Cache is cleared";
});
Route::get('/showForgotForm/{id}','Auth\ForgotPasswordController@showForgotForm');

Route::post('/changeForgotPassword', [
  'as' => 'password.forgot',
  'uses' => 'Auth\ForgotPasswordController@changeForgotPassword'
]);
Route::get('404',['as'=>'404','uses'=>'HomeController@errorCode404']);
//Route::get('405',['as'=>'405','uses'=>'HomeController@errorCode405']);


Route::group(['middleware' => ['auth','clearance']], function () { 
	Route::group(['prefix' => 'admin'], function () {
            
            /*Organisation routes*/   
    Route::resource('organization', 'OrganizationController');
    Route::get('deActive/{id}', 'OrganizationController@deActive');
    Route::get('active/{id}', 'OrganizationController@active');
	Route::get('dashboard', 'HomeController@dashboard');
	Route::get('profile', 'HomeController@profile');
	Route::post('profileupdate', 'HomeController@profileupdate');
	Route::get('websitesetting', 'WebsiteController@index');
	Route::post('websettingupd', 'WebsiteController@websettingupd');
	Route::get('screenlock/{currtime}/{id}/{randnum}', 'HomeController@screenlock');
	Route::resource('users', 'UserController');
    Route::get('deActiveUser/{id}', 'UserController@deActiveUser');
    Route::get('activeUser/{id}', 'UserController@activeUser');
	Route::get('change_password/{id}', 'UserController@change_password');
	Route::post('update_password', 'UserController@update_password');
	Route::resource('roles', 'RoleController');
	Route::resource('permissions', 'PermissionController');
	Route::resource('posts', 'PostController');

	//Route::get('deactiveClient', 'ClientController@deactiveClient');
	Route::get('deactiveClient', 'ClientController@deactiveClient');
	Route::resource('client', 'ClientController');
	Route::get('myMail', 'ClientController@myMail');
	Route::get('updateClient/{id}/{key}', 'ClientController@updateClient');
	Route::get('updateClientFront/{id}/{key}', 'ClientController@updateClientFront');
	Route::resource('location', 'LocationController');
	Route::get('updateLocation/{id}/{key}', 'LocationController@updateLocation');
	Route::get('deactiveLocation', 'LocationController@deactiveLocation');
	
	Route::get('getCity', 'LocationController@getCity');

Route::get('updateAdvertise/{id}/{key}', 'AdvertiseController@updateAdvertise');
Route::get('deactiveAdvertise', 'AdvertiseController@deactiveAdvertise');
Route::resource('advertise', 'AdvertiseController');

	Route::resource('schedule', 'ScheduleController');
	Route::post('getRemainingTime', 'ScheduleController@getRemainingTime');
	Route::post('getAdds', 'ScheduleController@getAdds');
	Route::post('getSchedule', 'ScheduleController@getSchedule');
	Route::post('getScheduleRecord', 'ScheduleController@getScheduleRecord');
	
	Route::get('demo', 'ScheduleController@demo');
	//Route::get('updateReboot', 'LocationController@updateReboot');
	Route::get('updateReboot/{key}', function($key){
		DB::table('otherservices')
        ->where('PIID', $key)
        ->update(['reboot' => '0']);

        return redirect('admin/location');
	});

	Route::post('changeStatus', 'ScheduleController@changeStatus');
	Route::post('repushStatus', 'ScheduleController@repushStatus');
	Route::post('deleteVideo', 'ScheduleController@deleteVideo');
	Route::get('updateAdvertisement/{id}/{key}', 'AdvertiseController@updateAdvertisement');
	Route::resource('keyword', 'KeywordController');
    Route::get('updateKeywords/{id}/{key}', 'KeywordController@updateKeywords');

Route::resource('size', 'ScreenSizeController');

Route::resource('type', 'ScreenTypeController');

Route::resource('skill', 'ourClientController');

	Route::resource('skill', 'SkillController');
	Route::get('updateSkills/{id}/{key}', 'SkillController@updateSkills');
	Route::post('/upload', 'ScheduleController@upload');

	Route::resource('report', 'ReportController');
	Route::get('clientWiseReport', 'ReportController@clientWiseReport');
	Route::get('locationWiseReport', 'ReportController@locationWiseReport');
	Route::get('advertiseWiseReport', 'ReportController@advertiseWiseReport');
	Route::get('scheduleWiseReport', 'ReportController@scheduleWiseReport');
	Route::post('getAdvertise', 'ReportController@getAdvertise');
	Route::post('getlocation', 'ReportController@getlocation');


	Route::get('marquee', 'MarqueeController@index');
	Route::post('add_marquee', 'MarqueeController@store');
	Route::get('marqueedelete/{id}', 'MarqueeController@destroy');
});
//--------------------Forntend Root-------------------------/////
	
	

});

Route::post('/user_login', 'HomeController@user_login');
Route::get('/user_logout', 'HomeController@user_logout');
Route::get('/list', 'HomeController@list');
Route::get('/temp1', 'HomeController@list1');
Route::get('/my_profile', 'HomeController@my_profile');
Route::get('/forgot', 'HomeController@forgot');
Route::post('/forgot', 'HomeController@forgot_submit');
Route::get('/forgot_password/{key?}', 'HomeController@forgot_password');
Route::post('/forgot_password', 'HomeController@forgot_password_submit');
Route::get('/change_password', 'HomeController@change_password');
Route::post('/change_password', 'HomeController@change_password_submit');
Route::post('/send', 'HomeController@send');

Route::post('file-upload', 'ScheduleController@fileUploadPost')->name('fileUploadPost');
Route::get('/location','LocationController@location');
Route::get('/verify/{id}','StudentController@verify');
Route::get('/verifyMessage','StudentController@verifyMessage');
Route::get('/{key?}', 'HomeController@index');
Route::get('error/pageNotFound',['as'=>'error/pageNotFound','uses'=>'StudentController@errorCode404']);



