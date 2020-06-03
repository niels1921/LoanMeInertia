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

// Auth
Route::get('login')->name('login')->uses('Auth\LoginController@showLoginForm')->middleware('guest');
Route::get('register')->name('register')->uses('Auth\LoginController@showRegisterform')->middleware('guest');
Route::post('register')->name('register.attempt')->uses('Auth\RegisterController@register')->middleware('guest');
Route::post('login')->name('login.attempt')->uses('Auth\LoginController@login')->middleware('guest');
Route::post('logout')->name('logout')->uses('Auth\LoginController@logout');

// Dashboard
Route::get('/')->name('dashboard')->uses('DashboardController@index')->middleware('auth');

// Reservation
//Route:get('reservation')->name('reservation')->uses('ReservationController@index')->middleware('auth');
Route::get('reservation/create')->name('reservation.create')->uses('ReservationController@create')->middleware('auth');
Route::post('reservation')->name('reservation.store')->uses('ReservationController@store')->middleware('auth');

// Users
Route::get('users')->name('users')->uses('UsersController@index')->middleware('remember', 'auth');
Route::get('users/create')->name('users.create')->uses('UsersController@create')->middleware('auth');
Route::post('users')->name('users.store')->uses('UsersController@store')->middleware('auth');
Route::get('users/{user}/edit')->name('users.edit')->uses('UsersController@edit')->middleware('auth');
Route::put('users/{user}')->name('users.update')->uses('UsersController@update')->middleware('auth');
Route::delete('users/{user}')->name('users.destroy')->uses('UsersController@destroy')->middleware('auth');
Route::put('users/{user}/restore')->name('users.restore')->uses('UsersController@restore')->middleware('auth');

// Images
Route::get('/img/{path}', 'ImagesController@show')->where('path', '.*');

// Equipment
Route::get('user/equipment')->name('user.equipment')->uses('EquipmentController@userEquipment')->middleware('remember', 'auth');
Route::get('equipment')->name('equipment')->uses('EquipmentController@index')->middleware('remember', 'auth');
Route::get('equipment/create')->name('equipment.create')->uses('EquipmentController@create')->middleware('auth');
Route::post('equipment')->name('equipment.store')->uses('EquipmentController@store')->middleware('auth');
Route::get('equipment/{equipment}/edit')->name('equipment.edit')->uses('EquipmentController@edit')->middleware('auth');
Route::post('equipment/update')->name('equipment.update')->uses('EquipmentController@update')->middleware('auth');
Route::delete('equipment/{equipment}')->name('equipment.destroy')->uses('EquipmentController@destroy')->middleware('auth');
//Route::put('organizations/{organization}/restore')->name('organizations.restore')->uses('OrganizationsController@restore')->middleware('auth');


// Categories
Route::group(['middleware' => ['can:categories']], function () {
    Route::get('categories')->name('categories')->uses('CategoryController@index')->middleware('remember', 'auth');
    Route::get('categories/create')->name('categories.create')->uses('CategoryController@create')->middleware('auth');
    Route::post('categories')->name('categories.store')->uses('CategoryController@store')->middleware('auth');
    Route::get('categories/{category}/edit')->name('categories.edit')->uses('CategoryController@edit')->middleware('auth');
    Route::put('categories/{category}')->name('categories.update')->uses('CategoryController@update')->middleware('auth');
    Route::delete('categories/{category}')->name('categories.destroy')->uses('CategoryController@destroy')->middleware('auth');
});


// Reservations
// Equipment
Route::get('reservations')->name('reservations')->uses('ReservationController@index')->middleware('remember', 'auth');
Route::get('reservations/create/{equipment}')->name('reservations.create')->uses('ReservationController@create')->middleware('remember', 'auth');
Route::post('reservations/store')->name('reservations.store')->uses('ReservationController@store')->middleware('remember', 'auth');
Route::post('reservations/cancel')->name('reservations.cancel')->uses('ReservationController@destroy')->middleware('remember', 'auth');
Route::get('reservations/dates/{id}')->name('reservations.dates')->uses('ReservationController@getDates')->middleware('remember', 'auth');
//Route::get('equipment/create')->name('equipment.create')->uses('EquipmentController@create')->middleware('auth');
//Route::post('equipment')->name('equipment.store')->uses('EquipmentController@store')->middleware('auth');
//Route::get('equipment/{equipment}/edit')->name('equipment.edit')->uses('EquipmentController@edit')->middleware('auth');
//Route::put('equipment/{equipment}')->name('equipment.update')->uses('EquipmentController@update')->middleware('auth');
//Route::delete('equipment/{equipment}')->name('equipment.destroy')->uses('EquipmentController@destroy')->middleware('auth');
//Route::put('organizations/{organization}/restore')->name('organizations.restore')->uses('OrganizationsController@restore')->middleware('auth');




// 500 error
Route::get('500', function () {
    // Force debug mode for this endpoint in the demo environment
    if (App::environment('demo')) {
        Config::set('app.debug', true);
    }

    echo $fail;
});
