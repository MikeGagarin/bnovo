<?php

use Mike\Bnovo\Controllers\GuestController;
use Mike\Bnovo\Controllers\HomeController;
use Mike\Bnovo\Http\Route;

Route::get('/', HomeController::class, 'index');

Route::get('/guest/list', GuestController::class, 'index');
Route::get('/guest/find', GuestController::class, 'find');
Route::delete('/guest/destroy', GuestController::class, 'destroy');
Route::post('/guest/store', GuestController::class, 'store');