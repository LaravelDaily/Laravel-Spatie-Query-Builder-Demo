<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create specific demo admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@demo.com',
            'password' => Hash::make('password'),
            'role_id' => 1, // admin
            'status_id' => 1, // active
            'bio' => 'System administrator with full access to manage the blog platform.',
            'avatar' => 'https://ui-avatars.com/api/?name=Admin+User&background=3b82f6&color=ffffff',
            'last_login_at' => now()->subHours(2),
            'email_verified_at' => now(),
        ]);

        // Create specific demo editor
        User::create([
            'name' => 'Jane Editor',
            'email' => 'editor@demo.com',
            'password' => Hash::make('password'),
            'role_id' => 2, // editor
            'status_id' => 1, // active
            'bio' => 'Senior content editor responsible for reviewing and managing published content.',
            'avatar' => 'https://ui-avatars.com/api/?name=Jane+Editor&background=10b981&color=ffffff',
            'last_login_at' => now()->subDays(1),
            'email_verified_at' => now(),
        ]);

        // Create specific demo authors
        $authors = [
            [
                'name' => 'John Developer',
                'email' => 'john@demo.com',
                'bio' => 'Full-stack developer passionate about Laravel and modern web technologies.',
                'avatar' => 'https://ui-avatars.com/api/?name=John+Developer&background=8b5cf6&color=ffffff',
            ],
            [
                'name' => 'Sarah Tech Writer',
                'email' => 'sarah@demo.com',
                'bio' => 'Technical writer specializing in tutorials and documentation.',
                'avatar' => 'https://ui-avatars.com/api/?name=Sarah+Writer&background=f59e0b&color=ffffff',
            ],
            [
                'name' => 'Mike Designer',
                'email' => 'mike@demo.com',
                'bio' => 'UI/UX designer with a focus on creating beautiful and functional interfaces.',
                'avatar' => 'https://ui-avatars.com/api/?name=Mike+Designer&background=ef4444&color=ffffff',
            ],
        ];

        foreach ($authors as $author) {
            User::create([
                'name' => $author['name'],
                'email' => $author['email'],
                'password' => Hash::make('password'),
                'role_id' => 3, // author
                'status_id' => 1, // active
                'bio' => $author['bio'],
                'avatar' => $author['avatar'],
                'last_login_at' => fake()->dateTimeBetween('-7 days', 'now'),
                'email_verified_at' => now(),
            ]);
        }

        // Create some inactive/pending users for demo filtering
        User::create([
            'name' => 'Inactive User',
            'email' => 'inactive@demo.com',
            'password' => Hash::make('password'),
            'role_id' => 3, // author
            'status_id' => 2, // inactive
            'bio' => 'This user account has been deactivated.',
            'last_login_at' => now()->subMonths(2),
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Pending User',
            'email' => 'pending@demo.com',
            'password' => Hash::make('password'),
            'role_id' => 3, // author
            'status_id' => 3, // pending
            'bio' => 'New user account awaiting approval.',
            'last_login_at' => null,
            'email_verified_at' => null,
        ]);

        // Generate additional random users for testing pagination/search
        User::factory(25)->create();
    }
}
