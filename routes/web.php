<?php

use App\Http\Controllers\EpisodesController;
use App\Http\Controllers\SeasonsController;
use App\Http\Controllers\SeriesController;
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
    return to_route('series.index');
});

//define todas as rotas para series com base 
Route::resource('/series', SeriesController::class)
    ->except(['show']);
                             //indica o método que vai ser utilizado, nesse caso, método index
Route::get('/series/{series}/seasons', [SeasonsController::class, 'index'])->name('seasons.index');

Route::get('/seasons/{season}/episodes/', [EpisodesController::class, 'index'])->name('episodes.index');
Route::post('/seasons/{season}/episodes/', [EpisodesController::class, 'update'])->name('episodes.update');

/*
//Cria um grupo de rotas que será controlada pelo mesmo controlador, dessa forma não se faz necessário
//inserir o controlador em cada rota
Route::controller(SeriesController::class)->group(function(){
    Route::get('/series', 'index')->name('series.index');
    Route::get('/series/create', 'create')->name('series.create');
    // O name permite que seja criada uma rota nomeada, fazerndo com que, em outros lugares do código
    //seja possível buscar diretamente pelo nome da rota, independente da url
    //como por exemplo, {{route('series.create)}}
    Route::post('/series/store', 'store')->name('series.store'); 
});*/
