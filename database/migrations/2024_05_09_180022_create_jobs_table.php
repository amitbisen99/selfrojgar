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
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('job_industry_id');
            $table->string('role');
            $table->string('company_name');
            $table->text('company_logo')->nullable();
            $table->string('start_salary');
            $table->string('end_salary');
            $table->string('type')->comment('Full Time, Part time');
            $table->string('work_type')->comment('On Office ,Work from home, Freelancer');
            $table->text('address');
            $table->string('city_id');
            $table->string('state_id');
            $table->string('country_id');
            $table->string('skills');
            $table->string('mobile')->nullable();
            $table->text('about');
            $table->longText('description');
            $table->string('salary_type');
            $table->string('employe_level');
            $table->string('latitude');
            $table->string('longitude');
            $table->integer('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
