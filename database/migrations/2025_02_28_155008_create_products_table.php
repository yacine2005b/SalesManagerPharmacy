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
        Schema::create('products', function (Blueprint $table) {
            $table->id(); 
            $table->string('name'); 
            $table->decimal('dosage', 8, 2)->nullable();
            $table->string('dosage_unit', 10)->nullable(); 
            $table->text('description')->nullable(); 
            $table->boolean('remboursable')->default(false); 
            $table->integer('low_stock_threshold')->default(0);
            $table->boolean('prescription')->default(false);
            $table->integer('total_quantity')->default(0); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
