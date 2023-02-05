<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\WebController;

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
    return view('welcome');
});


Route::prefix('user')->group(function () {
    //Login
    Route::get('/login', [LoginController::class, 'loginIndex'])->name('Login');
    Route::post('/post-login', [LoginController::class, 'postLogin']);
    //Register
    Route::get('/register', [LoginController::class, 'registerIndex']);
    Route::post('/post-register', [LoginController::class, 'postRegister']);

    Route::get('/logout', [LoginController::class, 'logout']);

});





Route::prefix('dashboard')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->middleware('auth');
    //
    Route::get('/web', [WebController::class, 'index']);
    Route::get('/web-create', [WebController::class, 'createIndex']);
    Route::post('/web-save', [WebController::class, 'save']);

    Route::get('/web-edit/{id}', [WebController::class, 'edit']);
    Route::post('/web-update/{id}', [WebController::class, 'update']);
    Route::get('/web-delete/{id}', [WebController::class, 'destroy']);

    Route::get('/posts', [PostController::class, 'index']);
    Route::get('/post/create', [PostController::class, 'create']);
    Route::post('/post/get-content', [PostController::class, 'getContent']);
    Route::post('/post/save', [PostController::class, 'save']);
    Route::get('/post-delete/{id}', [PostController::class, 'destroy']);
});