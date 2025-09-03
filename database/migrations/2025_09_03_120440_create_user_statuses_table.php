<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Insert default statuses
        DB::table('user_statuses')->insert([
            ['name' => 'active', 'description' => 'Active user account', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'inactive', 'description' => 'Inactive user account', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'pending', 'description' => 'Pending verification', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('user_statuses');
    }
};
