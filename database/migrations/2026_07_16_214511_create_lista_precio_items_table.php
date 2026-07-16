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
        Schema::create('lista_precio_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('lista_precio_id')->constrained('listas_precio')->cascadeOnDelete();
            $table->foreignUuid('producto_id')->constrained('productos')->cascadeOnDelete();
            $table->decimal('precio', 12, 2);
            $table->timestamps();

            $table->unique(['lista_precio_id', 'producto_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lista_precio_items');
    }
};
