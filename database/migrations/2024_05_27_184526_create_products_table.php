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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('product_categories_id');
            $table->string('brand_name');
            $table->string('product_name');
            $table->longText('description');
            $table->string('price');
            $table->string('price_for')->nullable();
            $table->longText('images');
            $table->integer('status')->default(1);
            $table->integer('latitude')->nullable();
            $table->integer('longitude')->nullable();
            $table->string('end_date')->nullable();
            $table->string('country_id')->nullable();
            $table->string('state_id')->nullable();
            $table->string('city_id')->nullable();
            $table->text('address')->nullable();
            $table->string('pincode')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
