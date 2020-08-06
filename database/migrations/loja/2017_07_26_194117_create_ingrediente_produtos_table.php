<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIngredienteProdutosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ingrediente_produtos', function (Blueprint $table) {
        	$table->id();
        	$table->enum('visibilidade', ['Ingrediente', 'Essencial Visível', 'Essencial Não Visível']);

            $table->foreignId('Ingredientes_idIngredientes')->constrained('ingredientes');
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
        Schema::dropIfExists('ingrediente_produtos');
    }
}
