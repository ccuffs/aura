<?php

use App\Http\Controllers\UserSitesController;
use App\Http\Controllers\AuraController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

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

// Fix wrong style/mix urls when being served from reverse proxy
URL::forceRootUrl(config('app.url'));

Route::group(['middleware' => ['web', 'auth', 'verified']], function () {
    // Aura
    Route::get('/aura', [AuraController::class, 'show'])->name('aura.show');
    
    // Test
    Route::get('/test/passport', [TestController::class, 'passport'])->name('test.passport');    
});

Route::get('/', function () {
    if (Auth::check()) {
        return redirect(config('fortify.home'));
    } else {
        return view('auth.login');
    }
});
