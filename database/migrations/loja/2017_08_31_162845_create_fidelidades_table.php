<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFidelidadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fidelidades', function (Blueprint $table) {
            $table->id();
            $table->double('valorResgate')->nullable();
            $table->double('valorAcumulado')->nullable();

            $table->foreignId('Clientes_idClientes')->constrained('clientes');
            $table->foreignId('Pedidos_idPedidos')->constrained('pedidos');
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
        Schema::dropIfExists('fidelidades');
    }
}
