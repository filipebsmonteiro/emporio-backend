<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFuncionamentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('funcionamentos', function (Blueprint $table) {
            $table->id();
            $table->string('dia_semana');
            $table->time('inicio_funcionamento');
            $table->time('termino_funcionamento');
            $table->time('inicio_delivery');
            $table->time('termino_delivery');

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
        Schema::dropIfExists('funcionamentos');
    }
}
