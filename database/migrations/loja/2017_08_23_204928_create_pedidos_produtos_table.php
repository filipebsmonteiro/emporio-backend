<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePedidosProdutosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedidos_produtos', function (Blueprint $table) {
            $table->id();
            $table->double('quantidade')            ->default(1);
            $table->double('valor');
            $table->string('tamanho')               ->nullable();

            $table->foreignId('Pedidos_idPedidos')->constrained('pedidos');
            $table->foreignId('Produtos_idProdutos')->constrained('produtos');

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
        Schema::dropIfExists('pedidos_produtos');
    }
}
