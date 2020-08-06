<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLojasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lojas', function (Blueprint $table) {
            $table->id();
            $table->string('fantasia', 40)->unique();
            $table->string('CNPJ', 20)->unique();
            $table->string('razao_social', 40)->unique();
            $table->string('abreviacao', 40)	->nullable();
            $table->string('phone', 16)		->nullable();

            $table->string('CEP', 12)		->nullable();
            $table->string('Logradouro', 60)	->nullable();
            $table->string('Bairro', 40)		->nullable();
            $table->string('Cidade', 40)		->nullable();

            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->double('vlr_minimo_pedido')     ->default(0);

            $table->boolean('agendamentos')           ->default(true);

            $table->boolean('pagamentosOnline')       ->default(true);
            $table->string('CieloMerchantID', 36)		->nullable();
            $table->string('CieloMerchantKey', 40)		->nullable();

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
        Schema::dropIfExists('lojas');
    }
}
