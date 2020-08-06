<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCombosProdMultiplosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('combos_prod_multiplos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('Produtos_idProdutos')->constrained('produtos');
            $table->foreignId('Prod_Multiplos_idProd_Multiplos')->constrained('produto_multiplos');

            $table->softDeletes();
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
        Schema::dropIfExists('combos_prod_multiplos');
    }
}
