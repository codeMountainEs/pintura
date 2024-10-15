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
        Schema::table('movimientos', function (Blueprint $table) {
            $table->decimal('rendimiento2',10,2)->default(1)->nullable(); // por capa m2/L
            $table->string('rendimiento_tipo')->nullable()->default('Kg/m2'); // entrada o salida
            $table->decimal('unidades',10,2)->default(1)->nullable(); // por capa m2/L

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movimientos', function (Blueprint $table) {
            if (Schema::hasColumn('movimientos', 'rendimiento2')) {
                Schema::table('movimientos', function ($table) {
                    $table->dropColumn('rendimiento2');
                    $table->dropColumn('rendimiento_tipo');
                    $table->dropColumn('unidades');
                });
            }
        });
    }
};
