<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 70);
            $table->string('CPF', 24)		->nullable()->unique();
            $table->date('nascimento');
            $table->enum('sexo', ['Masculino', 'Feminino', 'Não Informar']);
            $table->string('phone', 16);
            $table->string('email', 70)		->unique();
            $table->string('password', 70);
            $table->string('facebook_id', 70)->nullable();
            $table->boolean('newsletter')->default(false);
            //$table->rememberToken();

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
        Schema::dropIfExists('clientes');
    }
}
