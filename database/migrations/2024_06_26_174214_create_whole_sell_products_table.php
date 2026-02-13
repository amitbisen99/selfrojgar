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
        Schema::create('whole_sell_products', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('whole_sell_categories_id');
            $table->string('name')->nullable();
            $table->longText('description')->nullable();
            $table->integer('amount')->nullable();
            $table->string('end_date')->nullable();
            $table->integer('min_qty')->nullable();
            $table->longText('images')->nullable();
            $table->string('country_id')->nullable();
            $table->string('state_id')->nullable();
            $table->string('city_id')->nullable();
            $table->text('address')->nullable();
            $table->string('pincode')->nullable();
            $table->string('mobile')->nullable();
            $table->string('price_for')->nullable();
            $table->integer('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whole_sell_products');
    }
};
