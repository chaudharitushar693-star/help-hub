<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assistance_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('tracking_code', 40)->unique()->index();
            $table->string('request_type', 50);
            $table->string('priority', 20)->default('medium');
            $table->string('pilgrim_name', 120);
            $table->string('contact_number', 30);
            $table->string('alternate_contact', 30)->nullable();
            $table->string('circuit', 100);
            $table->string('current_location', 255);
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->text('description');
            $table->string('status', 30)->default('pending')->index();
            $table->string('assigned_to', 120)->nullable();
            $table->text('resolution_notes')->nullable();
            $table->timestamps();
        });

        Schema::create('request_updates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assistance_request_id')->constrained('assistance_requests')->onDelete('cascade');
            $table->string('updated_by', 120);
            $table->string('status', 50);
            $table->text('comment');
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('itineraries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('title', 200);
            $table->string('destination_circuit', 100);
            $table->date('start_date');
            $table->integer('duration_days')->default(3);
            $table->integer('group_size')->default(2);
            $table->decimal('estimated_budget_inr', 10, 2)->default(5000.00);
            $table->string('travel_mode', 50)->default('train');
            $table->string('accessibility_needs', 200)->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_public')->default(false);
            $table->timestamps();
        });

        Schema::create('itinerary_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('itinerary_id')->constrained('itineraries')->onDelete('cascade');
            $table->integer('day_number');
            $table->string('time_slot', 50);
            $table->string('activity_title', 200);
            $table->text('activity_description')->nullable();
            $table->string('location', 200);
            $table->decimal('estimated_cost', 8, 2)->default(0.00);
            $table->text('tips')->nullable();
            $table->timestamps();
        });

        Schema::create('saved_places', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('place_type', 50);
            $table->unsignedBigInteger('place_id');
            $table->string('place_name', 200);
            $table->string('circuit', 100);
            $table->text('notes')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saved_places');
        Schema::dropIfExists('itinerary_items');
        Schema::dropIfExists('itineraries');
        Schema::dropIfExists('request_updates');
        Schema::dropIfExists('assistance_requests');
    }
};
