<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePedProdProdMultiplosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ped_prod_prod_multiplos', function (Blueprint $table) {
            $table->id();
            $table->double('quantidade')            ->default(1);

            $table->foreignId('Ped_produtos_idPed_produtos')->constrained('pedidos_produtos');
            $table->foreignId('Prod_Multiplos_idProd_Multiplos')->constrained('produto_multiplos');
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
        Schema::dropIfExists('ped_prod_prod_multiplos');
    }
}
