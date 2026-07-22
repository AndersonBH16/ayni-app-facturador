<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cliente_portal_usuario', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('cliente_id')->constrained('clientes')->cascadeOnDelete();
            $table->foreignUuid('portal_usuario_id')->constrained('portal_usuarios')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['cliente_id', 'portal_usuario_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cliente_portal_usuario');
    }
};
