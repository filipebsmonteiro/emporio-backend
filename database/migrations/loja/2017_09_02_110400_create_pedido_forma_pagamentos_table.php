<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePedidoFormaPagamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedido_forma_pagamentos', function (Blueprint $table) {
            $table->id();
            $table->double('troco')->nullable();

            $table->foreignId('Pedidos_idPedidos')->constrained('pedidos');
            $table->foreignId('Formas_idFormas')->constrained('forma_pagamentos');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pedido_forma_pagamentos');
    }
}
