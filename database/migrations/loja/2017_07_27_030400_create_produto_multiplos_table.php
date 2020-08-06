<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProdutoMultiplosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produto_multiplos', function (Blueprint $table) {
            $table->id();

            $table->string('nome', 70);
            $table->boolean('obrigatorio')			->default(false);
            $table->integer('quantidade_min')		->nullable();
            $table->integer('quantidade_max')		->nullable();
            $table->double('valor')                 ->nullable();

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
        Schema::dropIfExists('produto_multiplos');
    }
}
