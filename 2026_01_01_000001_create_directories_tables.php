<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('category', 100)->index();
            $table->string('circuit', 100)->index();
            $table->string('location', 255);
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->string('operating_hours', 100)->default('24 Hours');
            $table->string('contact_number', 50)->nullable();
            $table->string('fee_type', 50)->default('Free');
            $table->text('description')->nullable();
            $table->boolean('is_verified')->default(true);
            $table->timestamps();
        });

        Schema::create('medical_centres', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('centre_type', 100);
            $table->string('circuit', 100)->index();
            $table->string('location', 255);
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->string('helpline_number', 50);
            $table->string('doctor_in_charge', 120)->nullable();
            $table->text('available_facilities')->nullable();
            $table->boolean('is_24x7')->default(true);
            $table->timestamps();
        });

        Schema::create('accommodations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('type', 100);
            $table->string('circuit', 100)->index();
            $table->string('address', 255);
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->string('contact_phone', 50);
            $table->string('price_range', 50)->default('Nominal Donation');
            $table->text('amenities')->nullable();
            $table->string('booking_url', 255)->nullable();
            $table->decimal('rating', 3, 2)->default(4.50);
            $table->boolean('is_verified')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accommodations');
        Schema::dropIfExists('medical_centres');
        Schema::dropIfExists('facilities');
    }
};
