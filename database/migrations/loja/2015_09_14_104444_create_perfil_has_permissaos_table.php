<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePerfilHasPermissaosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('perfil_has_permissaos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('Perfils_idPerfils')->constrained('perfils');
            $table->foreignId('Permissaos_idPermissaos')->constrained('permissaos');

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
        Schema::dropIfExists('perfil_has_permissaos');
    }
}
