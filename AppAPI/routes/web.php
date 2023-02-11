<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\WebController;
use App\Http\Controllers\OutlineController;
use App\Http\Controllers\ScriptController;

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
    Route::get('/web', [WebController::class, 'index'])->middleware('auth');
    Route::get('/web-create', [WebController::class, 'createIndex'])->middleware('auth');
    Route::post('/web-save', [WebController::class, 'save'])->middleware('auth');

    Route::get('/web-edit/{id}', [WebController::class, 'edit'])->middleware('auth');
    Route::post('/web-update/{id}', [WebController::class, 'update'])->middleware('auth');
    Route::get('/web-delete/{id}', [WebController::class, 'destroy'])->middleware('auth');

    //outline
    Route::get('/outline', [OutlineController::class, 'index'])->middleware('auth');
    Route::post('/outline/create', [OutlineController::class, 'getOutlineContent'])->middleware('auth');

    Route::get('/convert', [OutlineController::class, 'convertIndex'])->middleware('auth');
    Route::post('/convert/create', [OutlineController::class, 'convert'])->middleware('auth');

    //script
    Route::get('/script', [ScriptController::class, 'index'])->middleware('auth');
    Route::post('/script/create', [ScriptController::class, 'getScriptContent'])->middleware('auth');
    
});