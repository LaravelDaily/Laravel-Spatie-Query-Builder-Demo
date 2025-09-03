<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::whereIn('role_id', [2, 3])->get(); // editors and authors
        
        // Create some specific demo posts with realistic content
        $demoPosts = [
            [
                'title' => 'Getting Started with Laravel 12: A Complete Guide',
                'content' => 'Laravel 12 brings exciting new features and improvements to the framework we all love. In this comprehensive guide, we\'ll explore the key changes, new features, and best practices for getting started with Laravel 12. From installation to deployment, this tutorial covers everything you need to know.',
                'category_id' => 2, // tutorials
                'status_id' => 2, // published
                'published_at' => now()->subDays(5),
            ],
            [
                'title' => 'Advanced Query Builder Techniques in Laravel',
                'content' => 'The Laravel Query Builder is a powerful tool for constructing database queries. This article dives deep into advanced techniques, including subqueries, joins, and complex where clauses. Learn how to optimize your database interactions and write more efficient code.',
                'category_id' => 1, // technology
                'status_id' => 2, // published
                'published_at' => now()->subDays(10),
            ],
            [
                'title' => 'Building RESTful APIs with Laravel Sanctum',
                'content' => 'API development is a crucial skill for modern web developers. This tutorial walks through creating secure, scalable APIs using Laravel Sanctum for authentication. We\'ll cover token management, middleware, and best practices for API design.',
                'category_id' => 2, // tutorials
                'status_id' => 2, // published
                'published_at' => now()->subDays(15),
            ],
            [
                'title' => 'The Future of Web Development: Trends for 2025',
                'content' => 'As we look ahead to 2025, several trends are shaping the future of web development. From AI integration to improved performance optimization, this article explores what developers need to know to stay ahead of the curve.',
                'category_id' => 4, // opinion
                'status_id' => 2, // published
                'published_at' => now()->subDays(3),
            ],
            [
                'title' => 'Laravel Performance Optimization: Tips and Tricks',
                'content' => 'Performance is crucial for any web application. This guide covers essential optimization techniques for Laravel applications, including database optimization, caching strategies, and code profiling techniques.',
                'category_id' => 1, // technology
                'status_id' => 1, // draft
                'published_at' => null,
            ],
            [
                'title' => 'Modern CSS Grid Layouts: Beyond the Basics',
                'content' => 'CSS Grid has revolutionized how we approach web layouts. This article explores advanced grid techniques, responsive design patterns, and real-world implementation strategies for complex layouts.',
                'category_id' => 2, // tutorials
                'status_id' => 3, // scheduled
                'published_at' => now()->addDays(7),
            ],
            [
                'title' => 'Breaking: Laravel 12 Release Candidate Available',
                'content' => 'The Laravel team has announced the release candidate for Laravel 12. This version includes major improvements to performance, new features for developers, and enhanced security measures. Read about all the changes and how to upgrade.',
                'category_id' => 3, // news
                'status_id' => 2, // published
                'published_at' => now()->subDays(1),
            ],
            [
                'title' => 'Database Design Best Practices for Large Applications',
                'content' => 'Designing scalable database schemas is critical for large applications. This comprehensive guide covers normalization, indexing strategies, and performance considerations for growing applications.',
                'category_id' => 1, // technology
                'status_id' => 2, // published
                'published_at' => now()->subDays(20),
            ],
            [
                'title' => 'Why I Love Laravel: A Developer\'s Perspective',
                'content' => 'After years of working with various PHP frameworks, Laravel continues to impress me. This opinion piece explores what makes Laravel special from a developer\'s perspective, covering everything from Eloquent ORM to the vibrant community.',
                'category_id' => 4, // opinion
                'status_id' => 1, // draft
                'published_at' => null,
            ],
            [
                'title' => 'Testing Laravel Applications: From Unit to Feature Tests',
                'content' => 'Comprehensive testing is essential for maintainable applications. This tutorial covers testing strategies in Laravel, from simple unit tests to complex feature tests, including database testing and mocking.',
                'category_id' => 2, // tutorials
                'status_id' => 3, // scheduled
                'published_at' => now()->addDays(14),
            ],
        ];

        foreach ($demoPosts as $index => $postData) {
            $user = $users->random();
            
            Post::create([
                'title' => $postData['title'],
                'slug' => Str::slug($postData['title']),
                'excerpt' => Str::limit($postData['content'], 200),
                'content' => $postData['content'],
                'featured_image' => 'https://picsum.photos/800/400?random=' . ($index + 1),
                'user_id' => $user->id,
                'category_id' => $postData['category_id'],
                'status_id' => $postData['status_id'],
                'published_at' => $postData['published_at'],
            ]);
        }

        // Generate additional random posts for pagination testing
        Post::factory(50)->create([
            'user_id' => fn() => $users->random()->id,
        ]);

        // Create some specific posts for different statuses
        Post::factory(15)->published()->create([
            'user_id' => fn() => $users->random()->id,
        ]);

        Post::factory(8)->draft()->create([
            'user_id' => fn() => $users->random()->id,
        ]);

        Post::factory(5)->scheduled()->create([
            'user_id' => fn() => $users->random()->id,
        ]);
    }
}
