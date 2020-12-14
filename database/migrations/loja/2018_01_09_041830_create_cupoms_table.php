<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCupomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cupoms', function (Blueprint $table) {
            $table->id();

            $table->enum('codigo', [
                'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N'
            ]);
            $table->string('hash')          ->nullable();
            $table->unsignedInteger('numerador')    ->nullable()->zerofill();
            $table->integer('quantidade')   ->nullable();

            $table->double('porcentagem')   ->nullable();
            $table->double('valor')         ->nullable();

            $table->foreignId('Cat_produtos_idCat_produtos')->nullable()->constrained('categoria_produtos');
            $table->foreignId('Produtos_idProdutos')->nullable()->constrained('produtos');

            $table->timestamp('validade');
            $table->boolean('usado')        ->default(false);

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
        Schema::dropIfExists('cupoms');
    }
}
