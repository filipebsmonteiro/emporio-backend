<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ceps', function (Blueprint $table) {
            $table->id();
            $table->string('CEP', 9);
            $table->string('logradouro', 100);
            $table->string('complemento', 100)  ->nullable();
            $table->string('local', 100)        ->nullable();
            $table->string('bairro', 100);
            $table->double('taxa_entrega');
            $table->double('vlr_minimo_pedido')         ->default(0);

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
        Schema::dropIfExists('ceps');
    }
}
