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
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            $table->string('doctor_name'); // Name of the doctor issuing the prescription
            $table->string('patient_name'); // Name of the patient
            $table->string('patient_phone')->nullable(); // Patient's phone number
            $table->text('notes')->nullable(); // Additional notes about the prescription
            $table->date('prescription_date')->nullable();
            $table->enum('status', ['pending', 'processed', 'completed'])->default('pending'); // Status of the prescription
            $table->integer('duration')->nullable(); // Date the prescription was issued
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescriptions');
    }
};
