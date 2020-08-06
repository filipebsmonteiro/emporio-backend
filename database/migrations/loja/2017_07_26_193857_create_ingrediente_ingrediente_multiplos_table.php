<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIngredienteIngredienteMultiplosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ingrediente_ing_multiplos', function (Blueprint $table) {
        	$table->id();

            $table->foreignId('Ingredientes_idIngredientes')->constrained('ingredientes');
            $table->foreignId('Multiplos_idMultiplos')->constrained('ingrediente_multiplos');

        	$table->double('valor')->nullable();

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
        Schema::dropIfExists('ingrediente_ing_multiplos');
    }
}
