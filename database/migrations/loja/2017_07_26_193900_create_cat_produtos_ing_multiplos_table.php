<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatProdutosIngMultiplosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cat_produtos_ing_multiplos', function (Blueprint $table) {
        	$table->id();

            $table->foreignId('Cat_produtos_idCat_produtos')->constrained('categoria_produtos');
            $table->foreignId('Multiplos_idMultiplos')->constrained('ingrediente_multiplos');

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
        Schema::dropIfExists('cat_produtos_ing_multiplos');
    }
}
