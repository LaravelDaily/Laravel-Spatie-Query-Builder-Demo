<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->default(3)->constrained('user_roles')->onDelete('cascade'); // default to 'author'
            $table->foreignId('status_id')->default(1)->constrained('user_statuses')->onDelete('cascade'); // default to 'active'
            $table->text('bio')->nullable();
            $table->string('avatar')->nullable();
            $table->timestamp('last_login_at')->nullable();

            $table->index(['role_id', 'status_id']);
            $table->index(['status_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropForeign(['status_id']);
            $table->dropColumn(['role_id', 'status_id', 'bio', 'avatar', 'last_login_at']);
        });
    }
};
