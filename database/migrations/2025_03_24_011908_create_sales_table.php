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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['normal', 'insurance'])->default('normal');
            $table->enum('coverage_type', ['full', 'partial'])->nullable();
            $table->string('shifa_card_number')->nullable();
            $table->decimal('total_amount', 10, 2);
            $table->decimal('covered_amount', 10, 2)->default(0);
            $table->decimal('patient_pays', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
