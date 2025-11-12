<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::create('ganadores_bitacora', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('ganador_id')->nullable();
    $table->unsignedBigInteger('remate_id');
    $table->boolean('es_ganador');
    $table->integer('posicion')->nullable();
    $table->decimal('monto_ganado', 10, 2);
    $table->decimal('porcentaje', 5, 2);
    $table->decimal('total_pagar', 10, 2);
    $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ganadores_bitacoras');
    }
};
