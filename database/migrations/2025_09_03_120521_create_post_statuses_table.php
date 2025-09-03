<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('post_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Insert default post statuses
        DB::table('post_statuses')->insert([
            ['name' => 'draft', 'description' => 'Draft post not published yet', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'published', 'description' => 'Published and visible to public', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'scheduled', 'description' => 'Scheduled for future publication', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'archived', 'description' => 'Archived and not visible', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('post_statuses');
    }
};
