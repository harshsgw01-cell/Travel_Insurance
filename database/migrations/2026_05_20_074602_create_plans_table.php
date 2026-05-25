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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('policy_type');
            $table->decimal('base_premium', 12, 2);
            $table->decimal('coverage_amount', 12, 2);
            $table->unsignedSmallInteger('min_age')->default(0);
            $table->unsignedSmallInteger('max_age')->default(100);
            $table->unsignedSmallInteger('max_family_members')->nullable();
            $table->json('covered_countries')->nullable();
            $table->json('benefits')->nullable();
            $table->json('add_ons')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
