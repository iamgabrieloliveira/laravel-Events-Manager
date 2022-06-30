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

use App\Http\Controllers\EventController;


//Rota raiz, com o middleware que faz a autenticação
Route::get('/', [EventController::class, 'index'])->middleware('auth');

//Rota que adiciona eventos ao DB
Route::post('/', [EventController::class, 'store'])->name('store');

//Rota que deleta o evento do DB, a partir do id do evento
Route::delete('/{id}', [EventController::class, 'destroy'])->name('delete');

//Rota que atualiza os dados do evento selecionado
Route::put('/update/{id}', [EventController::class, 'update'])->name('atualizar');

//Rota que busca os evetos que usuário logado criou
Route::get('/{name}/myevents', [EventController::class, 'myEvents'])->name('myEvents');

//Rota que confirma o usuário no evento clicado
Route::post('/{id}', [EventController::class, 'confirm'])->name('confirmPresence');

//Rota que cancela o usuário no evento clicado
Route::put('/cancel/{id}', [EventController::class, 'cancel'])->name('cancelPresence');

//Rota que lista a partir do evento a lista de usuários que estão cadastrados
Route::get('/list/{event}', [EventController::class, 'list'])->name('presenceList');

//Rota que mostra quais eventos o usuário logado confirmou presença
Route::get('/{name}/confirmeds', [EventController:: class, 'myConfirmedEvents'])->name('myConfirmedEvents');

