<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProdMultiploProdutosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prod_multiplo_produtos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('Prod_Multiplos_idProd_Multiplos')->constrained('produto_multiplos');
            $table->foreignId('Produtos_idProdutos')->constrained('produtos');

            $table->double('valor') ->nullable();

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
        Schema::dropIfExists('prod_multiplo_produtos');
    }
}
