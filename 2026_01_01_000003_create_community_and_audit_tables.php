<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('circuit', 100);
            $table->string('priority', 20)->default('info');
            $table->text('content');
            $table->boolean('is_active')->default(true);
            $table->timestamp('published_at')->useCurrent();
            $table->timestamps();
        });

        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('category', 100);
            $table->text('question');
            $table->text('answer');
            $table->text('hindi_question')->nullable();
            $table->text('hindi_answer')->nullable();
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('pilgrim_name', 120);
            $table->string('email', 191)->nullable();
            $table->string('circuit', 100);
            $table->integer('rating')->default(5);
            $table->string('service_aspect', 100)->default('Overall Experience');
            $table->text('comment');
            $table->boolean('is_moderated')->default(true);
            $table->timestamps();
        });

        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('title', 200);
            $table->text('message');
            $table->string('type', 50)->default('info');
            $table->string('action_url', 255)->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('lost_found_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('report_type', 20); // 'lost' or 'found'
            $table->string('item_name', 200);
            $table->string('category', 100);
            $table->string('circuit', 100);
            $table->string('incident_location', 255);
            $table->date('incident_date');
            $table->text('description');
            $table->string('contact_person', 120);
            $table->string('contact_phone', 30);
            $table->string('status', 30)->default('reported');
            $table->boolean('is_approved')->default(true);
            $table->timestamps();
        });

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('user_email', 191)->nullable();
            $table->string('action', 100);
            $table->string('model_type', 100)->nullable();
            $table->unsignedBigInteger('model_id')->nullable();
            $table->string('ip_address', 50)->nullable();
            $table->text('user_agent')->nullable();
            $table->text('details')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('lost_found_reports');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('feedback');
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('announcements');
    }
};
