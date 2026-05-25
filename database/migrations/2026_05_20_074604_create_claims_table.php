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
        Schema::create('claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('policy_id')->constrained()->cascadeOnDelete();
            $table->string('claim_number')->unique();
            $table->foreignId('traveler_id')->nullable()->constrained()->nullOnDelete();
            $table->string('claim_type');
            $table->date('incident_date');
            $table->decimal('amount_claimed', 12, 2);
            $table->decimal('amount_approved', 12, 2)->nullable();
            $table->string('status')->default('submitted');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('claims');
    }
};
