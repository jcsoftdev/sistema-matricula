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
        Schema::table('periodos_academicos', function (Blueprint $table) {
            $table->decimal('costo_matricula', 10, 2)->default(0.00)->after('fecha_fin_prematricula');
            $table->decimal('costo_mensualidad', 10, 2)->default(0.00)->after('costo_matricula');
            $table->decimal('descuento_maximo', 5, 2)->default(0.00)->after('costo_mensualidad')->comment('Percentage 0-100');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('periodos_academicos', function (Blueprint $table) {
            $table->dropColumn(['costo_matricula', 'costo_mensualidad', 'descuento_maximo']);
        });
    }
};
