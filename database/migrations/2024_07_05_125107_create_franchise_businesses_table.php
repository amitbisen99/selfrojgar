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
        Schema::create('franchise_businesses', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('franchise_categories_id');
            $table->string('name');
            $table->longText('description')->nullable();
            $table->string('city_id');
            $table->string('state_id');
            $table->string('country_id');
            $table->text('address')->nullable();
            $table->string('pin_code')->nullable();
            $table->string('industry_experience')->nullable();
            $table->string('investment')->nullable();
            $table->longText('other')->nullable();
            $table->longText('images')->nullable();
            $table->string('mobile')->nullable();
            $table->integer('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('franchise_businesses');
    }
};
