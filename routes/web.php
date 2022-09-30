<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('home');
}); 
Route::resource('/', 'App\Http\Controllers\HomeController');  
Route::resource('/home', 'App\Http\Controllers\HomeController'); 
Route::get('/bikes/add/{id}', 'App\Http\Controllers\BikesController@add');
Route::post('/bikes/add/{id}', 'App\Http\Controllers\BikesController@add');
Route::get('/bikes/edit/{id}', 'App\Http\Controllers\BikesController@edit');
Route::post('/bikes/edit/{id}', 'App\Http\Controllers\BikesController@edit');
Route::get('/bikes/delete/{id}', 'App\Http\Controllers\BikesController@delete');
Route::post('/bikes/delete/{id}', 'App\Http\Controllers\BikesController@delete');
Route::resource('/bikes', 'App\Http\Controllers\BikesController'); 

Route::get('/cities/add/{id}', 'App\Http\Controllers\CitiesController@add');
Route::post('/cities/add/{id}', 'App\Http\Controllers\CitiesController@add');
Route::get('/cities/edit/{id}', 'App\Http\Controllers\CitiesController@edit');
Route::post('/cities/edit/{id}', 'App\Http\Controllers\CitiesController@edit');
Route::get('/cities/delete/{id}', 'App\Http\Controllers\CitiesController@delete');
Route::post('/cities/delete/{id}', 'App\Http\Controllers\CitiesController@delete');
Route::resource('/cities', 'App\Http\Controllers\CitiesController'); 

Route::get('/vendors/add/{id}', 'App\Http\Controllers\VendorsController@add');
Route::post('/vendors/add/{id}', 'App\Http\Controllers\VendorsController@add');
Route::get('/vendors/edit/{id}', 'App\Http\Controllers\VendorsController@edit');
Route::post('/vendors/edit/{id}', 'App\Http\Controllers\VendorsController@edit');
Route::get('/vendors/delete/{id}', 'App\Http\Controllers\VendorsController@delete');
Route::post('/vendors/delete/{id}', 'App\Http\Controllers\VendorsController@delete');
Route::resource('/vendors', 'App\Http\Controllers\VendorsController'); 

Route::get('/bookings/add/{id}', 'App\Http\Controllers\BookingsController@add');
Route::post('/bookings/book', 'App\Http\Controllers\BookingsController@book');
Route::post('/bookings/filter', 'App\Http\Controllers\BookingsController@index');
Route::get('/bookings/list', 'App\Http\Controllers\BookingsController@list');
Route::get('/bookings/vendor', 'App\Http\Controllers\BookingsController@vendor');
Route::get('/bookings/book', 'App\Http\Controllers\BookingsController@book');
Route::post('/bookings/add/{id}', 'App\Http\Controllers\BookingsController@add');
Route::get('/bookings/my', 'App\Http\Controllers\BookingsController@my');
Route::get('/bookings/edit/{id}', 'App\Http\Controllers\BookingsController@edit');
Route::post('/bookings/edit/{id}', 'App\Http\Controllers\BookingsController@edit');
Route::get('/bookings/delete/{id}', 'App\Http\Controllers\BookingsController@delete');
Route::post('/bookings/delete/{id}', 'App\Http\Controllers\BookingsController@delete');
Route::resource('/bookings', 'App\Http\Controllers\BookingsController'); 


 
Route::get('/api/bookings/{id}', 'App\Http\Controllers\ApiController@bookings');
Route::get('/api/vendorbikes/{id}', 'App\Http\Controllers\ApiController@vendorbikes'); 
Route::get('/api/booking_status/{id}', 'App\Http\Controllers\ApiController@booking_status');

Route::resource('/logout', 'App\Http\Controllers\LogoutController');  
Route::get('/logout', 'App\Http\Controllers\LoginController@logout'); 
Route::post('/login/add', 'App\Http\Controllers\LoginController@add');
Route::post('/login/authenticate', 'App\Http\Controllers\LoginController@authenticate'); 
Route::resource('/login', 'App\Http\Controllers\LoginController');   
Route::get('/logout', [App\Http\Controllers\LogoutController::class, 'index'])->name('logout');
Route::get('/login', [App\Http\Controllers\LoginController::class, 'index'])->name('login');

Route::post('/register/add', 'App\Http\Controllers\RegisterController@add');
Route::get('/register/confirmation/{id}', 'App\Http\Controllers\RegisterController@confirmation');
Route::get('/register/show/{id}', 'App\Http\Controllers\RegisterController@show');  
Route::resource('/register', 'App\Http\Controllers\RegisterController');

Route::get('/user/add/{id}', 'App\Http\Controllers\UserController@add');
Route::get('/user/view/{id}', 'App\Http\Controllers\UserController@view');
Route::get('/user/edit/{id}', 'App\Http\Controllers\UserController@edit');
Route::post('/user/edit/{id}', 'App\Http\Controllers\UserController@edit');
Route::get('/user/delete/{id}', 'App\Http\Controllers\UserController@delete');
Route::post('/user/delete/{id}', 'App\Http\Controllers\UserController@delete'); 
Route::resource('/user', 'App\Http\Controllers\UserController'); 
