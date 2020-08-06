<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pagamentos', function (Blueprint $table) {
            $table->id();

            $table->string('ProofOfSale','15')   ->nullable()->comment('Número da autorização, identico ao NSU.');
            $table->string('Tid','20')          ->nullable()->comment('Código de Id da transação na adquirente..');
            $table->string('PaymentId','36')    ->nullable()->comment('Campo Identificador do Pedido.');
            $table->string('Type','25')          ->nullable()->comment('Forma de pagamento');
            $table->string('Amount','15')        ->nullable()->comment('Valor do Pedido (ser enviado em centavos).');
            $table->string('Status','2')        ->nullable()->comment('Status da Transação.');
            $table->string('ReturnCode','32')   ->nullable()->comment('Código de retorno da Adquirência.');
            $table->string('ReturnMessage','512')   ->nullable()->comment('Mensagem de retorno da Adquirência.');
            $table->string('AuthorizationCode','15') ->nullable()->comment('Código de autorização.');
            $table->string('imagemComprovante')         ->nullable()->comment('Imagem do comprovante');

            $table->foreignId('Pedidos_idPedidos')->constrained('pedidos');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pagamentos');
    }
}
