<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIngMultiplosProdutosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ing_multiplos_produtos', function (Blueprint $table) {
        	$table->id();

        	$table->foreignId('Multiplos_idMultiplos')->constrained('ingrediente_multiplos');
            $table->foreignId('Produtos_idProdutos')->constrained('produtos');

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
        Schema::dropIfExists('ing_multiplos_produtos');
    }
}
