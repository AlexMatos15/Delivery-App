<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Fix slug constraint:
     * - Remove unique constraint global do slug
     * - Adicionar unique constraint composto (user_id, slug)
     * - Isso permite que lojas diferentes tenham produtos com mesmo slug
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Remove o índice único global do slug (se existir)
            $table->dropUnique(['slug']);
            
            // Adiciona índice único composto (user_id, slug)
            // Garante que cada loja tenha slugs únicos
            $table->unique(['user_id', 'slug'], 'products_user_id_slug_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Remove o índice composto
            $table->dropUnique('products_user_id_slug_unique');
            
            // Restaura o índice único global
            $table->unique('slug');
        });
    }
};
