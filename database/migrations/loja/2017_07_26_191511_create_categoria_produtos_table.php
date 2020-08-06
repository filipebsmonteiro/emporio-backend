<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriaProdutosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categoria_produtos', function (Blueprint $table) {
        	$table->id();
            $table->string('nome', 70);
            $table->string('grupo', 70)->nullable();
        	$table->enum('layout', ['Padrão', 'Pizza', 'Combo']);

            $table->foreignId('Lojas_idLojas')->constrained('lojas');

        	//Informações obrigatórias quando tipo for igual Pizza
        	$table->boolean('permiteCombinacao')		->default(false);
        	$table->string('quantidadeCombinacoes')		->nullable();

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
        Schema::dropIfExists('categoria_produtos');
    }
}
