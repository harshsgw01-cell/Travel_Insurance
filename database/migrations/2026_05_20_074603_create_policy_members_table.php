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
        Schema::create('policy_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('policy_id')->constrained()->cascadeOnDelete();
            $table->foreignId('traveler_id')->constrained()->cascadeOnDelete();
            $table->string('relationship');
            $table->decimal('coverage_amount', 12, 2);
            $table->decimal('premium', 12, 2);
            $table->timestamps();

            $table->unique(['policy_id', 'traveler_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('policy_members');
    }
};
