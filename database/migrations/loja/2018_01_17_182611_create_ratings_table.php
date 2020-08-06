<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('Clientes_idClientes')->constrained('clientes');
            $table->foreignId('Pedidos_idPedidos')->constrained('pedidos');

            $table->integer('site');
            $table->integer('comida');
            $table->integer('entrega');
            $table->integer('embalagem');
            $table->longText('comentarios')->nullable();
            $table->longText('resposta')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ratings');
    }
}
