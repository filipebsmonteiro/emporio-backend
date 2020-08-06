<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePedProdIngredientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ped_prod_ingredientes', function (Blueprint $table) {
            $table->id();
            $table->double('quantidade')            ->default(1);
            $table->double('valor');

            $table->foreignId('Ped_produtos_idPed_produtos')->constrained('pedidos_produtos');
            $table->foreignId('Ingredientes_idIngredientes')->constrained('ingredientes');

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
        Schema::dropIfExists('ped_prod_ingredientes');
    }
}
