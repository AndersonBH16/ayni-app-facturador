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
        Schema::create('productos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->string('sku');
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->decimal('precio_base', 12, 2);
            $table->string('unidad_medida')->default('NIU'); // catálogo 03 SUNAT
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->unique(['empresa_id', 'sku']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
