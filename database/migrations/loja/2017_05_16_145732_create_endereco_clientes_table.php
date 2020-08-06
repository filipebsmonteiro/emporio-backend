<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnderecoClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('endereco_clientes', function (Blueprint $table) {
            $table->id();
            $table->string('CEP', 12);
            $table->string('Logradouro', 70)->nullable();
            $table->string('Bairro', 40)	->nullable();
            $table->string('Cidade', 40)	->nullable();
            $table->string('Referencia', 70)->nullable();

            $table->foreignId('Clientes_idClientes')->constrained('clientes');

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
        Schema::dropIfExists('endereco_clientes');
    }
}
