<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('color', 7)->default('#6366f1'); // hex color
            $table->timestamps();
        });

        // Insert default categories
        DB::table('categories')->insert([
            ['name' => 'Technology', 'slug' => 'technology', 'description' => 'Technology and programming articles', 'color' => '#3b82f6', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Tutorials', 'slug' => 'tutorials', 'description' => 'Step-by-step tutorials', 'color' => '#10b981', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'News', 'slug' => 'news', 'description' => 'Latest news and updates', 'color' => '#f59e0b', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Opinion', 'slug' => 'opinion', 'description' => 'Opinion pieces and thoughts', 'color' => '#8b5cf6', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
