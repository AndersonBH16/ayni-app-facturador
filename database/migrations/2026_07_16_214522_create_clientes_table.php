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
        Schema::create('clientes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->foreignUuid('rubro_id')->nullable()->constrained('rubros')->nullOnDelete();
            $table->foreignUuid('lista_precio_id')->nullable()->constrained('listas_precio')->nullOnDelete();
            $table->enum('tipo_cliente', ['especial', 'general'])->default('general');
            $table->string('tipo_documento', 1)->default('6'); // catálogo 06 SUNAT: 6=RUC, 1=DNI
            $table->string('ruc_dni', 15);
            $table->string('razon_social');
            $table->string('email')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->unique(['empresa_id', 'ruc_dni']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
