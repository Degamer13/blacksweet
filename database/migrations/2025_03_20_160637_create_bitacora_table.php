<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('bitacora', function (Blueprint $table) {
            $table->id();
            $table->integer('number');

            // Claves foráneas separadas
            $table->unsignedBigInteger('race_id');
            $table->string('ejemplar_name');
            $table->string('cliente');
            $table->decimal('monto1', 10, 2);
            $table->decimal('monto2', 10, 2);
            $table->decimal('monto3', 10, 2);
            $table->decimal('monto4', 10, 2);
            $table->decimal('total', 10, 2);
            $table->decimal('porcentaje', 10, 2);
            $table->decimal('pote', 10, 2)->nullable();
            $table->decimal('acumulado', 10, 2)->nullable();

            $table->decimal('total_pagar', 10, 2);
            $table->decimal('total_subasta', 10, 2);

            // Campos para la suma de cada monto
            $table->decimal('subasta1', 10, 2)->default(0);
            $table->decimal('subasta2', 10, 2)->default(0);
            $table->decimal('subasta3', 10, 2)->default(0);
            $table->decimal('subasta4', 10, 2)->default(0);

            $table->timestamps();

            // Relaciones con 'races' y 'ejemplars'
            $table->foreign('race_id')->references('id')->on('races')->onDelete('cascade');

        });
    }

    public function down() {
        Schema::dropIfExists('bitacora');
    }
};
