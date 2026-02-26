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
        Schema::create('reservas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pasajero_id')->constrained('pasajeros')->cascadeOnDelete();
            $table->foreignId('habitacion_id')->constrained('habitaciones')->cascadeOnDelete();
            $table->date('fecha_entrada');
            $table->date('fecha_salida');
            $table->integer('numero_personas');
            $table->enum('estado', ['Pendiente', 'Confirmada', 'Cancelada', 'Completada']);
            $table->enum('tipo_pago', ['Efectivo', 'Tarjeta', 'Transferencia']);
            $table->decimal('total_pagado', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};
