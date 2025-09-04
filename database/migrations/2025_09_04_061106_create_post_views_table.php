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
        Schema::create('post_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('ip_address', 45); // supports both IPv4 and IPv6
            $table->text('user_agent')->nullable();
            $table->string('referer', 500)->nullable();
            $table->string('device_type', 50)->nullable(); // mobile, desktop, tablet
            $table->string('browser', 100)->nullable();
            $table->string('browser_version', 50)->nullable();
            $table->string('platform', 100)->nullable(); // operating system
            $table->string('country_code', 2)->nullable(); // ISO country code
            $table->string('region', 100)->nullable(); // state/province
            $table->string('city', 100)->nullable();
            $table->string('timezone', 100)->nullable();
            $table->string('utm_source', 255)->nullable();
            $table->string('utm_medium', 255)->nullable();
            $table->string('utm_campaign', 255)->nullable();
            $table->string('utm_term', 255)->nullable();
            $table->string('utm_content', 255)->nullable();
            $table->integer('session_duration')->nullable(); // in seconds
            $table->boolean('is_bot')->default(false);
            $table->boolean('is_mobile')->default(false);
            $table->json('extra_data')->nullable(); // for any additional analytics data
            $table->timestamp('viewed_at');
            $table->timestamps();

            // Indexes for better query performance
            $table->index(['post_id', 'viewed_at']);
            $table->index(['user_id', 'viewed_at']);
            $table->index(['ip_address', 'viewed_at']);
            $table->index(['country_code', 'viewed_at']);
            $table->index(['device_type', 'viewed_at']);
            $table->index(['is_bot', 'viewed_at']);
            $table->index('viewed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_views');
    }
};
