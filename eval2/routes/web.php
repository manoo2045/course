<?php

use App\Http\Controllers\CoureurController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('/', function () {
    return redirect('equipe/login');
});


Route::get('/insertAd',[\App\Http\Controllers\AuthController::class,'insertAd']);
Route::get('/insertCli',[\App\Http\Controllers\AuthController::class,'inscription']);

Route::get('/admin/login',[\App\Http\Controllers\AuthController::class,'login'])->name('auth.login');
Route::post('/admin/login',[\App\Http\Controllers\AuthController::class,'doLogin'])->name('auth.doLogin');
Route::get('/logout',[\App\Http\Controllers\AuthController::class,'logout'])->name('auth.logout');
//Route::get('/client/login',[\App\Http\Controllers\AuthController::class,'loginClient']);
//Route::post('/client/login',[\App\Http\Controllers\AuthController::class,'doLoginClient'])->name('auth.doLoginClient');
//Route::post('/client/register',[\App\Http\Controllers\AuthController::class,'doRegister']);
//Route::get('/register',[\App\Http\Controllers\AuthController::class,'register'])->name('auth.register');


Route::middleware('admin')->group(function () {
    Route::get('/home', [\App\Http\Controllers\AccueilAdmin::class, 'home']);
    Route::get('/admin/clearBd', [\App\Http\Controllers\AccueilAdmin::class, 'clearBd']);
});

Route::get('equipe/login', [App\Http\Controllers\Auth\EquipeLoginController::class, 'showLoginForm'])->name('equipe.login');
Route::post('equipe/login', [App\Http\Controllers\Auth\EquipeLoginController::class, 'login'])->name('equipe.login.submit');

Route::get('insc', [App\Http\Controllers\Auth\EquipeLoginController::class, 'inscription']);

Route::prefix('equipe')->middleware('equipe')->group(function () {
    Route::post('logout', [App\Http\Controllers\Auth\EquipeLoginController::class, 'logout'])->name('equipe.logout');
    Route::get('/', [App\Http\Controllers\AccueilEquipeController::class, 'index'])->name('equipe.home');

    Route::get('/etape',[\App\Http\Controllers\AccueilEquipeController::class,'list_etat']);
    Route::get('/etape/list/',[\App\Http\Controllers\AccueilEquipeController::class,'list_etat']);

    Route::post('/etape_coureur/insert',[\App\Http\Controllers\AccueilEquipeController::class,'insert_etape_coureur']);
    Route::get('/etape_coureur/insert/{id}',[\App\Http\Controllers\AccueilEquipeController::class,'etape_coureurInsertView']);

    Route::get('/classement-equipe',[\App\Http\Controllers\CoureurController::class,'classementEquipe']);
});


Route::prefix('admin')->middleware('admin')->group(function () {
    Route::get('/genereCategorie',[\App\Http\Controllers\AccueilAdmin::class,'genererCategorie']);

    Route::get('/import',[\App\Http\Controllers\ImportController::class,'index']);
    Route::post('/import/data',[\App\Http\Controllers\ImportController::class,'importEtapeResultat']);
    Route::post('/import/point',[\App\Http\Controllers\ImportController::class,'importPoint']);

    Route::get('/etape',[\App\Http\Controllers\AccueilAdmin::class,'list_etat']);
    Route::get('/etape/list/',[\App\Http\Controllers\AccueilAdmin::class,'list_etat']);

    Route::post('/temps_coureur/insert/',[CoureurController::class,'insert']);
    Route::get('/temps_coureur/insert/{id}',[CoureurController::class,'temps_coureurInsertView']);

    Route::get('/penalite',[\App\Http\Controllers\AccueilAdmin::class,'list_equipe_penaliser']);
    Route::post('/penalite/insert',[\App\Http\Controllers\AccueilAdmin::class,'insert_penalite']);
    Route::get('/penalite/delete/{id}',[\App\Http\Controllers\AccueilAdmin::class,'detete_penalite']);

    Route::get('/export',[\App\Http\Controllers\AccueilAdmin::class,'export']);
});

Route::get('/classementGlobale/{id}',[CoureurController::class,'classementCoureur']);
Route::get('/classementGlobale',[CoureurController::class,'classement']);
Route::get('/classementEquipe',[CoureurController::class,'classementEquipe_admin']);
Route::get('/classement/detail/{id}',[CoureurController::class,'detailClassement']);

