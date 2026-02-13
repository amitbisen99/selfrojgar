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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('category_id');
            $table->string('name')->nullable();
            $table->string('property_for')->nullable()->comment('values is (Sell / Rent)');
            $table->string('area_value')->nullable()->comment('Value is Like(200,80)');
            $table->string('area_for')->nullable()->comment('values is Like (Sqryard, Acre, Bigha, Sqrft)');
            $table->string('area_price_for')->nullable()->comment('values is Like (Sqryard, Acre, Bigha, Sqrft)');
            $table->float('area_price', 10, 2)->nullable();
            $table->float('total_price', 10, 2)->nullable();
            $table->float('rent_price', 10, 2)->nullable();
            $table->string('rent_price_type')->nullable()->comment('Values is Like (Monthly, Yearly)');
            $table->string('country_id')->nullable();
            $table->string('state_id')->nullable();
            $table->string('city_id')->nullable();
            $table->text('address')->nullable();
            $table->string('pincode')->nullable();
            $table->string('contactno')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('dimension')->nullable();
            $table->longText('images')->nullable();
            $table->integer('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
