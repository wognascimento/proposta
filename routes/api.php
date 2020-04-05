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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['middleware' => ['jwt.verify']], function() {
    Route::post('me', 'Auth\LoginJwtController@me');
});

Route::apiResources([
    'propostas' => 'Api\PropostaApiController',
]);
Route::get('getpropostas', 'Api\PropostaApiController@propostas');

Route::post('register', 'Auth\LoginJwtController@register');
Route::post('login', 'Auth\LoginJwtController@login');
// Route::post('me', 'Auth\LoginJwtController@me');

Route::get('gettemas', 'TemaController@getTemas');
Route::post('create', 'TemaController@store');
Route::get('temas', 'TemaController@index');
Route::get('tema-catalogo/{id}', 'TemaController@temaSelecionado');
Route::get('temas_lancamentos', 'TemaController@inicial');
Route::get('sugestao-atendimento', 'TemaController@sugestaoAtendimento');
Route::get('arrumar_conceito', 'TemaController@arrumarConceito');
Route::get('conceitos', 'TemaController@getConceitos');
Route::get('conceito-tema', 'TemaController@conceitos');
Route::get('tema-conceito', 'TemaController@conceito');
Route::get('tema-catalogo/{id}', 'TemaController@getTemaCatalogo');
Route::get('agenda', 'TemaController@agendaShowroom');

Route::post('create-tema-selecionado', 'TemaSelecionadoClienteController@store');
Route::get('temas-selecionados', 'TemaSelecionadoClienteController@temasSelecionados');
Route::delete('tema-selecionados/{id}', 'TemaSelecionadoClienteController@destroy');

Route::post('save-briefing', 'PropostaController@store');


Route::group(['prefix' => 'controlado'], function () {
    Route::get('validar', 'ControladoShoppingController@validar');
    Route::post('adicionar', 'ControladoShoppingController@adicionar');
});
