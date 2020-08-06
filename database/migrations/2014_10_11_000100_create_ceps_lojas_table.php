<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCepsLojasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ceps_lojas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('Lojas_idLojas')->constrained('lojas');
            $table->foreignId('Ceps_idCeps')->constrained('ceps');

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
        Schema::dropIfExists('ceps_lojas');
    }
}
