<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePedidoCupomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedido_cupoms', function (Blueprint $table) {
            $table->id();

            $table->foreignId('Pedidos_idPedidos')->constrained('pedidos');
            $table->foreignId('Cupoms_idCupoms')->constrained('cupoms');

            $table->double('valor')         ->nullable();

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
        Schema::dropIfExists('pedido_cupoms');
    }
}
