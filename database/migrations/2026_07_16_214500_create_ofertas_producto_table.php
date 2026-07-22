<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ofertas_producto', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->foreignUuid('producto_id')->constrained('productos')->cascadeOnDelete();
            $table->decimal('precio_base', 12, 2);
            $table->boolean('controla_stock')->default(false);
            $table->integer('stock_disponible')->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->unique(['empresa_id', 'producto_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ofertas_producto');
    }
};
