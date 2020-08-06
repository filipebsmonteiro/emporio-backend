<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIngredientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ingredientes', function (Blueprint $table) {
        	$table->id();
        	$table->string('nome', 70);
        	$table->boolean('status')->default(true);
            $table->double('preco')->default(0);
            $table->integer('codigo_PDV')               ->nullable();

            $table->foreignId('Cat_ingredientes_idCat_ingredientes')->constrained('categoria_ingredientes');
            $table->foreignId('Lojas_idLojas')->constrained('lojas');

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
        Schema::dropIfExists('ingredientes');
    }
}
