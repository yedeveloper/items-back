<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//API route for register
Route::post('/register', [App\Http\Controllers\API\AuthController::class, 'register']);
//API route for login
Route::post('/login', [App\Http\Controllers\API\AuthController::class, 'login']);

//Protecting Routes
Route::group(['middleware' => ['auth:sanctum']], function () {

    //Route to get the entire list
    Route::get('/getAll', [App\Http\Controllers\API\AuthController::class, 'getAll']);

    //Route to update the entire list
    Route::post('/syncAll', [App\Http\Controllers\API\AuthController::class, 'syncAll']);

    //Route to create a new item
    Route::post('/newItem', [App\Http\Controllers\API\AuthController::class, 'newItem']);

    //Route to update an existing item
    Route::put('/updateItem', [App\Http\Controllers\API\AuthController::class, 'updateItem']);

    //Route to delete an existing item
    Route::delete('/deleteItem', [App\Http\Controllers\API\AuthController::class, 'deleteItem']);

    // API route for logout user
    Route::post('/logout', [App\Http\Controllers\API\AuthController::class, 'logout']);

});
