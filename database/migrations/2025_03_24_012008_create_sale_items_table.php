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
        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')
                ->constrained()
                ->onDelete('cascade');
            $table->foreignId('lot_id') ->references('id')
            ->on('lots')
            ->onDelete('cascade');
            $table->foreignId('product_id')
            ->references('id')
            ->on('products')
            ->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('original_price', 10, 2);
            $table->decimal('final_price', 10, 2);
            $table->boolean('insurance_covered')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_items');
    }
};
