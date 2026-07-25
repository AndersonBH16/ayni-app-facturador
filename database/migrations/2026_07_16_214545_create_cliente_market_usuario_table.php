<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cliente_market_usuario', function (Blueprint $table) {
            $table->foreignUuid('cliente_id')->constrained('clientes')->cascadeOnDelete();
            $table->foreignUuid('market_usuario_id')->constrained('market_usuarios')->cascadeOnDelete();
            $table->timestamps();

            $table->primary(['cliente_id', 'market_usuario_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cliente_market_usuario');
    }
};
