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
        Schema::create('on_demand_services', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('on_demand_categories_id');
            $table->string('name');
            $table->string('phone');
            $table->text('description');
            $table->integer('amount');
            $table->longText('images');
            $table->string('country_id')->nullable();
            $table->string('state_id')->nullable();
            $table->string('city_id')->nullable();
            $table->text('address')->nullable();
            $table->string('pincode')->nullable();
            $table->integer('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('on_demand_services');
    }
};
