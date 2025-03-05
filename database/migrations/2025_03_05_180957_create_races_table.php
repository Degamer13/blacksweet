<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRacesTable extends Migration
{
    public function up()
    {
        Schema::create('races', function (Blueprint $table) {
            $table->id(); // Crea el campo id de tipo auto-incremental
            $table->string('name'); // Crea el campo name
            $table->timestamps(); // Crea created_at y updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('races');
    }
}
