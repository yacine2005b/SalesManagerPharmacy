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
        Schema::create('lots', function (Blueprint $table) {
            $table->id(); 
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); 
            $table->string('batch_number'); 
            $table->integer('quantity');
            $table->decimal('price', 10, 2);
            $table->date('expiration_date'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lots');
    }
};
