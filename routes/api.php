<?php

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

Route::group(['namespace' => 'API\Painel', 'prefix' => 'painel/auth'], function () {
    Route::post('login', 'AuthController@login');
    Route::post('reset', 'AuthController@resetPassword');
});
Route::group(['namespace' => 'API\Painel', 'prefix' => 'painel', 'middleware' => ['auth:api_painel']], function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('logout', 'AuthController@logout');
        Route::post('refresh', 'AuthController@refresh');
        Route::post('me', 'AuthController@me');
    });

    Route::apiResource('cliente', 'ClienteController')->only([
        'index', 'show'
    ]);

    Route::group(['prefix' => 'cupom'], function () {
        Route::get('', 'CupomController@index');
        Route::get('tipos', 'CupomController@tipos');
        Route::get('{id}', 'CupomController@show');
        Route::post('', 'CupomController@store');
        Route::put('{id}', 'CupomController@update');
        Route::post('valida', 'CupomController@valida');
    });

    Route::group(['prefix' => 'dashboard'], function () {
        Route::get('quantidade', 'DashboardController@index');
        Route::get('cards', 'DashboardController@cardsInfo');
    });

    Route::group(['prefix' => 'fidelidade'], function () {
        Route::apiResource('', 'FidelidadeController')->only([
            'index', 'store'
        ]);
        Route::get('{id}', 'FidelidadeController@valorResgate');
    });

    Route::apiResource('formapagamento', 'FormaPagamentoController')->except([
        'destroy'
    ]);

    Route::apiResource('ingrediente', 'IngredienteController')->except([
        'destroy'
    ]);

    Route::apiResource('pedido', 'PedidoController')->only([
        'index', 'show', 'update'
    ]);

    Route::apiResource('perfil', 'PerfilController')->only(['index']);

    Route::group(['prefix' => 'produto'], function () {
        Route::apiResource('categoria', 'CategoriaProdutoController');
        Route::put('changeStatus/{id}', 'ProdutoController@changeStatus');
        Route::post('uploadImagem', 'ProdutoController@uploadImagem');
        Route::get('', 'ProdutoController@index');
        Route::get('{id}', 'ProdutoController@show');
        Route::post('', 'ProdutoController@store');
        Route::put('{id}', 'ProdutoController@update');
        Route::delete('{id}', 'ProdutoController@destroy');
    });

    Route::apiResource('loja', 'LojaController')->only([
        'index', 'show', 'update'
    ]);

    Route::apiResource('usuario', 'UsuarioController');
});

Route::group(['namespace' => 'API\Site'], function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', 'AuthController@login');
        Route::post('reset', 'AuthController@resetPassword');
        Route::post('logout', 'AuthController@logout')->middleware('auth:api');
        Route::post('refresh', 'AuthController@refresh')->middleware('auth:api');
        Route::post('me', 'AuthController@me')->middleware('auth:api');
    });

    Route::group(['prefix' => 'cliente'], function () {
        Route::get('{id}', 'ClienteController@show')->middleware('auth:api');
        Route::post('', 'ClienteController@store')->name('cliente.create');
        Route::put('{id}', 'ClienteController@update')
            ->middleware('auth:api')
            ->name('cliente.update');
    });

    Route::post('cupom/valida', 'CupomController@valida');

    Route::group(['prefix' => 'endereco'], function () {
        Route::get('', 'EnderecoController@index')->middleware('auth:api');
        Route::get('{id}', 'EnderecoController@show');
        Route::post('', 'EnderecoController@store')->middleware('auth:api');
        Route::put('{id}', 'EnderecoController@update')->middleware('auth:api');
        Route::delete('{id}', 'EnderecoController@destroy')->middleware('auth:api');
        Route::get('getLojaResponsavel/{CEP}', 'EnderecoController@getLojaResponsavel');
    });

    Route::group(['prefix' => 'fidelidade'], function () {
        Route::get('', 'FidelidadeController@index')->middleware('auth:api');
        Route::get('{id}', 'FidelidadeController@valorResgate');
    });

    Route::get('formapagamento', 'FormaPagamentoController@index');

    Route::get('ingrediente', 'IngredienteController@index');

    Route::apiResource('pedido', 'PedidoController')->only([
        'index', 'store', 'show'
    ])->middleware('auth:api');

    Route::group(['prefix' => 'produto'], function () {
        Route::group(['prefix' => 'categoria'], function () {
            Route::get('listAvailable', 'CategoriaProdutoController@listAvailable');
            Route::get('{id}', 'CategoriaProdutoController@show');
        });

        Route::get('', 'ProdutoController@index');
        Route::get('{id}', 'ProdutoController@show');

    });

    Route::apiResource('loja', 'LojaController')->only([
        'index', 'show'
    ]);

    Route::post('send-sac', 'InstitucionalController@sacEnvia');
});
