<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePedProdProdutosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ped_prod_produtos', function (Blueprint $table) {
            $table->id();
            $table->double('quantidade')            ->default(1);
            $table->double('valor');

            $table->foreignId('Ped_produtos_idPed_produtos')->constrained('pedidos_produtos');
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
        Schema::dropIfExists('ped_prod_produtos');
    }
}
