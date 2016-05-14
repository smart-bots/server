<?php

use SmartBots\{
    User,
    Hub,
    Bot,
    Member,
    Schedule,
    Automation,
    HubPermission,
    BotPermission,
    SchedulePermission,
    AutomationPermission
};

Route::get('', function () {
    return view('comingsoon.index');
});

Route::get('test', function () {
	// dd(auth()->user()->can('view',Hub::findOrFail(session('currentHub'))));
	// $str = 'Saturday 23:09';
	// $pattern = '/^(Sunday|Monday|Tuesday|Wednesday|Thursday|Friday|Saturday) ([01]{1}[0-9]{1}|[2]{1}[0-3]{1}):([012345]{1}[0-9]{1})$/';
	// preg_match($pattern, $str, $matches);
	// return dd($matches);
});

// Route::auth();

Route::group([
	'prefix' => 'account',
	'as'     => 'a'
], function () {

	Route::group(['middleware' => ['nonAuthed']], function() {
		Route::get('login','UserController@getLogin')->name('::login');
		Route::post('login','UserController@postLogin');
		Route::get('register','UserController@getRegister')->name('::register');
		Route::post('register','UserController@postRegister');
		Route::get('forgot','UserController@getForgot')->name('::forgot');
		Route::post('forgot','UserController@postForgot');
	});

	Route::group(['middleware' => ['authed']], function() {
		Route::get('/', function () {
			return redirect()->to(route('a::edit'),301);
		});
		Route::get('edit','UserController@edit')->name('::edit');
		Route::post('edit','UserController@update')->name('::update');
		Route::get('logout','UserController@logout')->name('::logout');
		Route::post('logout','UserController@logout')->name('::logout');
		Route::get('change-pass','UserController@getChangePass')->name('::changePass');
		Route::post('change-pass','UserController@postChangePass');

		Route::get('search/{query?}','UserController@search')->name('::search');
	});

});

Route::group([
	'prefix'     => 'hub',
	'as'         => 'h',
	'middleware' => ['authed']
], function () {

	Route::get('index','HubController@index')->name('::index');
	Route::get('create','HubController@create')->name('::create');
	Route::post('create','HubController@store');
	Route::post('login','HubController@login')->name('::login');

	Route::group([
		'middleware' => ['hubLogedIn']
	], function () {

		Route::get('/', function() {
			return redirect()->to(route('h::dashboard'),301);
		});

		Route::get('dashboard','HubController@dashboard')->name('::dashboard');

		Route::get('show','HubController@show')->name('::show');

		Route::get('logout','HubController@logout')->name('::logout');
		Route::group(['middleware' => 'can:editDelete'], function () {

			Route::get('edit','HubController@edit')->name('::edit');
			Route::post('edit','HubController@update');

			Route::get('deactivate','HubController@deactivate')->name('::deactivate');

			Route::get('reactivate','HubController@reactivate')->name('::reactivate');

			Route::get('destroy','HubController@destroy')->name('::destroy');
		});

		Route::get('bots-status','HubController@botsStatus')->name('::botsStatus');

		Route::group([
			'prefix' => 'member',
			'as'     => '::m'
		], function() {

			Route::get('/',function () {
				return redirect()->to(route('h::m::index'),301);
			});

			Route::get('index','MemberController@index')->name('::index')->middleware('can:viewAllMembers');

			Route::group(['middleware' => 'can:addMembers'], function () {

				Route::get('create','MemberController@create')->name('::create');
				Route::post('create','MemberController@store');
			});

			Route::get('{id}/show','MemberController@show')->name('::show');

			Route::group(['middleware' => 'can:editDeleteAllMembers'], function () {

				Route::get('{id}/edit','MemberController@edit')->name('::edit');
				Route::post('{id}/edit','MemberController@update');

				Route::post('{id}/deactivate','MemberController@deactivate')->name('::deactivate');
				Route::post('{id}/reactivate','MemberController@reactivate')->name('::reactivate');

				Route::post('{id}/destroy','MemberController@destroy')->name('::destroy');
			});

		});

		Route::group([
			'prefix' => 'bot',
			'as'     => '::b'
		], function() {

			Route::get('/',function () {
				return redirect()->to(route('h::b::index'),301);
			});

			Route::get('index','BotController@index')->name('::index');

			Route::group(['middleware' => 'can:addBots'], function () {

				Route::get('create','BotController@create')->name('::create');
				Route::post('create','BotController@store');
			});
			Route::group(['middleware' => 'can:basic'], function () {

				Route::get('{id}/show','BotController@show')->name('::show');
				Route::post('control','BotController@control')->name('::control');
			});

			Route::group(['middleware' => 'can:higher'], function () {

				Route::get('{id}/edit','BotController@edit')->name('::edit');
				Route::post('{id}/edit','BotController@update');

				Route::post('{id}/deactivate','BotController@deactivate')->name('::deactivate');
				Route::post('{id}/reactivate','BotController@reactivate')->name('::reactivate');

				Route::post('{id}/destroy','BotController@destroy')->name('::destroy');
			});

			Route::get('search/{query?}/{query2?}','BotController@search')->name('::search');

		});

		Route::group([
			'prefix' => 'schedule',
			'as'     => '::s'
		], function() {

			Route::get('/',function () {
				return redirect()->to(route('h::s::index'),301);
			});

			Route::get('index','ScheduleController@index')->name('::index');

			Route::get('{id}/show','ScheduleController@show')->name('::show');

			Route::group(['middleware' => 'can:createSchedules'], function () {

				Route::get('create','ScheduleController@create')->name('::create');
				Route::post('create','ScheduleController@store');
			});
			Route::group(['middleware' => 'can:higher'], function () {

				Route::get('{id}/edit','ScheduleController@edit')->name('::edit');
				Route::post('{id}/edit','ScheduleController@update');

				Route::post('{id}/deactivate','ScheduleController@deactivate')->name('::deactivate');
				Route::post('{id}/reactivate','ScheduleController@reactivate')->name('::reactivate');

				Route::post('{id}/destroy','ScheduleController@destroy')->name('::destroy');
			});

		});

	});

});


// Route::any('{all}', function(){
//     abort(404);
// })->where('all', '.*');
