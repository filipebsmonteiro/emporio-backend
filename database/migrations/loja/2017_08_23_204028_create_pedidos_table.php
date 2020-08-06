<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['Pedido Realizado', 'Cancelado', 'Em Fabricação', 'Enviado', 'Concluido']);
            $table->string('referencia')        ->nullable();
            $table->longText('observacoes')     ->nullable();
            $table->double('valor');
            $table->double('taxaEntrega');

            $table->foreignId('Clientes_idClientes')->constrained('clientes');
            $table->foreignId('Lojas_idLojas')->constrained('lojas');
            $table->foreignId('Enderecos_idEnderecos')->constrained('endereco_clientes');

            $table->string('ip_address')        ->default('0.0.0.0');

            $table->timestamp('confirmed_at')               ->nullable();
            $table->timestamp('sent_at')                    ->nullable();
            $table->timestamp('finalized_at')                ->nullable();

            $table->boolean('print')                        ->default(false);
            $table->timestamp('agendamento')                ->nullable();

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
        Schema::dropIfExists('pedidos');
    }
}
