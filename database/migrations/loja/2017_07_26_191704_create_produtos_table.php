<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProdutosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produtos', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 70);
            $table->double('preco')						->nullable();
            $table->string('imagem')					    ->nullable();
            $table->string('unidade_medida')				->nullable();
            $table->double('minimo_unidade')				->nullable();
            $table->double('intervalo')				    ->nullable();
            $table->enum('status', ['Disponível', 'Indisponível', 'Desabilitado']);
            $table->unsignedInteger('codigo_PDV')               ->nullable();

            $table->boolean('promocionar')               ->default(false);
            $table->double('valorPromocao')             ->nullable();
            $table->boolean('freteGratis')               ->default(false);

            $table->foreignId('Cat_produtos_idCat_produtos')->constrained('categoria_produtos');
            $table->foreignId('Lojas_idLojas')->constrained('lojas');

            //Informações de Disponibilidade
            $table->boolean('domingo')					->default(false);
            $table->boolean('segunda')					->default(false);
            $table->boolean('terca')					    ->default(false);
            $table->boolean('quarta')					->default(false);
            $table->boolean('quinta')					->default(false);
            $table->boolean('sexta')					    ->default(false);
            $table->boolean('sabado')					->default(false);
            $table->enum('disponibilidade', ['Sempre Disponível', '1 Turno', '2 Turnos']);
            $table->time('inicio_periodo1')				->nullable();
            $table->time('termino_periodo1')			    ->nullable();
            $table->time('inicio_periodo2')				->nullable();
            $table->time('termino_periodo2')			    ->nullable();
            $table->time('tempo_producao')              ->nullable();

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
        Schema::dropIfExists('produtos');
    }
}
