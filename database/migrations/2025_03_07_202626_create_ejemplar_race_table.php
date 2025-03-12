<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEjemplarRaceTable extends Migration
{
    public function up()
    {
        Schema::create('ejemplar_race', function (Blueprint $table) {
            $table->id();
            $table->foreignId('race_id')->constrained()->onDelete('cascade');
            $table->string('ejemplar_name');
            $table->enum('status', ['activar', 'desactivar'])->default('desactivar');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ejemplar_race');
    }
}
