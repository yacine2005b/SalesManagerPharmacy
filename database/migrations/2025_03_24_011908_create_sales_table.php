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
            $table->unsignedBigInteger('sale_session_id')->nullable();
            $table->unsignedBigInteger('prescription_id')->nullable();
            $table->enum('type', ['normal', 'insurance',"prescription"])->default('normal');
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending'); 
            $table->decimal('total_amount', 10, 2);
            $table->decimal('covered_amount', 10, 2)->default(0);
            $table->decimal('patient_pays', 10, 2);
            $table->timestamps();

            $table->foreign('sale_session_id')->references('id')->on('sale_sessions')->onDelete('cascade');
            $table->foreign('prescription_id')->references('id')->on('prescriptions')->onDelete('set null'); // Add foreign key constraint
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
