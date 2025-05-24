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
        Schema::create('shifa_cards', function (Blueprint $table) {
             $table->id();
            $table->foreignId('patient_id')->unique()->constrained('patients')->onDelete('cascade');
            $table->enum('coverage_type', ['full', 'partial'])->default('partial');
            $table->string('card_number')->unique();
            $table->date('issue_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shifa_cards');
    }
};
