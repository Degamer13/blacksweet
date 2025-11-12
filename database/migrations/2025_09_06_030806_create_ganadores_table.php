<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ganadores', function (Blueprint $table) {
            $table->id();

            // Relación con la apuesta/remate
            $table->unsignedBigInteger('remate_id');
            $table->foreign('remate_id')
                  ->references('id')
                  ->on('remates')
                  ->onDelete('cascade');

            // Indica si el remate fue ganador
            $table->boolean('es_ganador')->default(false);

            // Posición obtenida (1 = primer lugar, 2 = segundo, etc.)
            $table->unsignedInteger('posicion')->nullable();

            // Monto que ganó este cliente (en caso de empate se divide)
            $table->decimal('monto_ganado', 12, 2)->default(0);

            // Porcentaje del pote que le corresponde (ej: 50.00 si hay 2 ganadores)
            $table->decimal('porcentaje', 5, 2)->nullable();

            // Guardar el total a pagar del remate (bitácora)
            $table->decimal('total_pagar', 12, 2)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ganadores');
    }
};
