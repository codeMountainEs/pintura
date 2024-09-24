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
        Schema::create('movimientos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            $table->string('tipo'); // entrada o salida
            $table->string('origen')->nullable(); // del producto proveedor o similar

            // salida se compra en litros se saca por m2 caluclameos el rendimiento
            $table->string('descripcion')->nullable();
            $table->integer('capas')->nullable(); // numero
            $table->decimal('rendimiento',10,2)->nullable(); // por capa m2/L
            $table->decimal('superficie',10,2)->nullable();// total a pintar en m2


            $table->decimal('cantidad', 10,2 )->default(1)->nullable();
            // Cantidad de pintura necesaria: (10 m² * 2 capas) / 10 m²/L = 2 litros
            $table->string('medida'); // del producto

            $table->decimal('precio',10,2)->nullable();
            $table->decimal('total',10,2)->nullable();

            $table->string('destino')->nullable(); // del producto


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimientos');
    }
};
