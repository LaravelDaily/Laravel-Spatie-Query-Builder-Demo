<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostViewSeeder extends Seeder
{
    private array $userAgents = [
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
        'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
        'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
        'Mozilla/5.0 (iPhone; CPU iPhone OS 14_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Mobile/15E148 Safari/604.1',
        'Mozilla/5.0 (iPad; CPU OS 14_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Mobile/15E148 Safari/604.1',
        'Mozilla/5.0 (Android 11; Mobile; rv:68.0) Gecko/68.0 Firefox/88.0',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:89.0) Gecko/20100101 Firefox/89.0',
        'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.1.1 Safari/605.1.15',
        'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)',
        'Mozilla/5.0 (compatible; Bingbot/2.0; +http://www.bing.com/bingbot.htm)',
    ];

    private array $devices = ['desktop', 'mobile', 'tablet'];
    private array $browsers = ['Chrome', 'Firefox', 'Safari', 'Edge', 'Opera'];
    private array $platforms = ['Windows', 'macOS', 'Linux', 'iOS', 'Android'];
    private array $countries = ['US', 'GB', 'CA', 'AU', 'DE', 'FR', 'ES', 'IT', 'NL', 'BE', 'SE', 'NO', 'DK', 'FI', 'PL', 'CZ', 'AT', 'CH', 'IE', 'PT'];
    private array $cities = ['New York', 'London', 'Toronto', 'Sydney', 'Berlin', 'Paris', 'Madrid', 'Rome', 'Amsterdam', 'Brussels', 'Stockholm', 'Oslo', 'Copenhagen', 'Helsinki', 'Warsaw', 'Prague', 'Vienna', 'Zurich', 'Dublin', 'Lisbon'];
    private array $utmSources = ['google', 'facebook', 'twitter', 'linkedin', 'youtube', 'instagram', 'reddit', 'pinterest', 'direct', 'email'];
    private array $utmMediums = ['organic', 'cpc', 'social', 'email', 'referral', 'display'];
    private array $referers = [
        'https://www.google.com/',
        'https://www.facebook.com/',
        'https://twitter.com/',
        'https://www.linkedin.com/',
        'https://news.ycombinator.com/',
        'https://reddit.com/',
        'https://stackoverflow.com/',
        'https://github.com/',
        null, null, null, null, null, // more direct visits
    ];

    public function run(): void
    {
        $this->command->info('Starting PostViewSeeder for 1,000,000 records...');
        
        // Get all published posts and users
        $publishedPosts = Post::whereHas('status', function ($query) {
            $query->where('name', 'published');
        })->pluck('id')->toArray();
        
        $userIds = User::pluck('id')->toArray();
        
        if (empty($publishedPosts)) {
            $this->command->warn('No published posts found. Please run PostSeeder first.');
            return;
        }

        $this->command->info('Found ' . count($publishedPosts) . ' published posts');
        $this->command->info('Found ' . count($userIds) . ' users');

        // Disable foreign key checks for better performance
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Truncate existing data if any
        DB::table('post_views')->truncate();

        $batchSize = 1000; // Insert 1k records at a time (MySQL placeholder limit)
        $totalRecords = 1000000;
        $batches = ceil($totalRecords / $batchSize);

        $startDate = Carbon::now()->subDays(30);
        $endDate = Carbon::now();

        for ($batch = 0; $batch < $batches; $batch++) {
            $this->command->info("Processing batch " . ($batch + 1) . " of {$batches}...");
            
            $viewsData = [];
            $currentBatchSize = min($batchSize, $totalRecords - ($batch * $batchSize));

            for ($i = 0; $i < $currentBatchSize; $i++) {
                $viewsData[] = $this->generateViewRecord($publishedPosts, $userIds, $startDate, $endDate);
            }

            // Bulk insert the batch
            DB::table('post_views')->insert($viewsData);
            
            // Memory cleanup
            unset($viewsData);
            
            if ($batch % 10 === 0) {
                $this->command->info('Memory usage: ' . round(memory_get_usage(true) / 1024 / 1024, 2) . ' MB');
            }
        }

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('✅ Successfully seeded 1,000,000 post views!');
        
        // Show some statistics
        $this->showStatistics();
    }

    private function generateViewRecord(array $postIds, array $userIds, Carbon $startDate, Carbon $endDate): array
    {
        $deviceType = $this->devices[array_rand($this->devices)];
        $isMobile = in_array($deviceType, ['mobile', 'tablet']);
        $isBot = rand(1, 100) <= 5; // 5% chance of being a bot
        $hasUser = rand(1, 100) <= 25; // 25% chance of authenticated user (realistic percentage)
        $hasUtm = rand(1, 100) <= 20; // 20% chance of UTM parameters
        
        // Generate realistic view distribution (some posts get more views)
        $postId = $this->getWeightedRandomPost($postIds);
        
        // Generate realistic time distribution (more views during day, weekdays)
        $viewedAt = $this->getRealisticTimestamp($startDate, $endDate);
        
        return [
            'post_id' => $postId,
            'user_id' => $hasUser ? $userIds[array_rand($userIds)] : null,
            'ip_address' => $this->generateIpAddress(),
            'user_agent' => $this->userAgents[array_rand($this->userAgents)],
            'referer' => $this->referers[array_rand($this->referers)],
            'device_type' => $deviceType,
            'browser' => $this->browsers[array_rand($this->browsers)],
            'browser_version' => rand(80, 120) . '.0',
            'platform' => $this->platforms[array_rand($this->platforms)],
            'country_code' => $this->countries[array_rand($this->countries)],
            'region' => $this->generateRegion(),
            'city' => $this->cities[array_rand($this->cities)],
            'timezone' => $this->generateTimezone(),
            'utm_source' => $hasUtm ? $this->utmSources[array_rand($this->utmSources)] : null,
            'utm_medium' => $hasUtm ? $this->utmMediums[array_rand($this->utmMediums)] : null,
            'utm_campaign' => $hasUtm ? $this->generateCampaignName() : null,
            'utm_term' => $hasUtm && rand(1, 100) <= 60 ? $this->generateUtmTerm() : null,
            'utm_content' => $hasUtm && rand(1, 100) <= 40 ? $this->generateUtmContent() : null,
            'session_duration' => rand(10, 3600), // 10 seconds to 1 hour
            'is_bot' => $isBot,
            'is_mobile' => $isMobile,
            'extra_data' => rand(1, 100) <= 15 ? json_encode([
                'screen_resolution' => ['1920x1080', '1366x768', '1440x900', '1536x864'][array_rand(['1920x1080', '1366x768', '1440x900', '1536x864'])],
                'color_depth' => [24, 32][array_rand([24, 32])],
                'javascript_enabled' => true,
                'language' => ['en-US', 'en-GB', 'fr-FR', 'de-DE'][array_rand(['en-US', 'en-GB', 'fr-FR', 'de-DE'])],
            ]) : null,
            'viewed_at' => $viewedAt,
            'created_at' => $viewedAt,
            'updated_at' => $viewedAt,
        ];
    }

    private function getWeightedRandomPost(array $postIds): int
    {
        // Simulate real-world scenario where some posts get more views
        // First 20% of posts get 60% of views (popular posts)
        // Next 30% get 30% of views (medium popularity)
        // Remaining 50% get 10% of views (less popular)
        
        $random = rand(1, 100);
        $postCount = count($postIds);
        
        if ($random <= 60) {
            // Popular posts (first 20%)
            $index = rand(0, max(0, floor($postCount * 0.2) - 1));
        } elseif ($random <= 90) {
            // Medium popularity (next 30%)
            $start = floor($postCount * 0.2);
            $end = floor($postCount * 0.5) - 1;
            $index = rand($start, max($start, $end));
        } else {
            // Less popular (remaining 50%)
            $start = floor($postCount * 0.5);
            $index = rand($start, $postCount - 1);
        }
        
        return $postIds[$index];
    }

    private function getRealisticTimestamp(Carbon $startDate, Carbon $endDate): string
    {
        // Generate more views during business hours and weekdays
        $randomDate = Carbon::createFromTimestamp(rand($startDate->timestamp, $endDate->timestamp));
        
        // Adjust hour based on realistic patterns
        if ($randomDate->isWeekday()) {
            // Weekday: peak hours 9-17, some evening activity
            $hourWeights = [
                0 => 1, 1 => 1, 2 => 1, 3 => 1, 4 => 1, 5 => 2, 6 => 3, 7 => 4, 8 => 6,
                9 => 10, 10 => 12, 11 => 14, 12 => 15, 13 => 16, 14 => 15, 15 => 14,
                16 => 12, 17 => 10, 18 => 8, 19 => 7, 20 => 6, 21 => 5, 22 => 3, 23 => 2
            ];
        } else {
            // Weekend: more afternoon/evening activity
            $hourWeights = [
                0 => 2, 1 => 1, 2 => 1, 3 => 1, 4 => 1, 5 => 1, 6 => 2, 7 => 3, 8 => 4,
                9 => 6, 10 => 8, 11 => 10, 12 => 12, 13 => 14, 14 => 16, 15 => 15,
                16 => 14, 17 => 12, 18 => 10, 19 => 9, 20 => 8, 21 => 7, 22 => 5, 23 => 3
            ];
        }
        
        $weightedHour = $this->getWeightedRandomHour($hourWeights);
        $randomDate->setHour($weightedHour)->setMinute(rand(0, 59))->setSecond(rand(0, 59));
        
        return $randomDate->format('Y-m-d H:i:s');
    }

    private function getWeightedRandomHour(array $weights): int
    {
        $totalWeight = array_sum($weights);
        $random = rand(1, $totalWeight);
        $currentWeight = 0;
        
        foreach ($weights as $hour => $weight) {
            $currentWeight += $weight;
            if ($random <= $currentWeight) {
                return $hour;
            }
        }
        
        return 12; // fallback
    }

    private function generateIpAddress(): string
    {
        // Generate realistic IP addresses (avoiding private ranges for public views)
        $publicRanges = [
            ['8.8.8', 254], // Google DNS range example
            ['1.1.1', 254], // Cloudflare DNS range example
            ['208.67.222', 254], // OpenDNS range example
        ];
        
        $range = $publicRanges[array_rand($publicRanges)];
        return $range[0] . '.' . rand(1, $range[1]);
    }

    private function generateRegion(): string
    {
        $regions = ['California', 'New York', 'Texas', 'Florida', 'Ontario', 'London', 'Bavaria', 'Île-de-France'];
        return $regions[array_rand($regions)];
    }

    private function generateTimezone(): string
    {
        $timezones = ['America/New_York', 'America/Los_Angeles', 'Europe/London', 'Europe/Paris', 'Europe/Berlin', 'Australia/Sydney'];
        return $timezones[array_rand($timezones)];
    }

    private function generateCampaignName(): string
    {
        $campaigns = ['summer_2024', 'black_friday', 'new_year', 'spring_launch', 'product_promo', 'retargeting'];
        return $campaigns[array_rand($campaigns)];
    }

    private function generateUtmTerm(): string
    {
        $terms = ['laravel tutorial', 'php development', 'web programming', 'coding tips', 'software engineering'];
        return $terms[array_rand($terms)];
    }

    private function generateUtmContent(): string
    {
        $content = ['header_banner', 'sidebar_ad', 'footer_link', 'inline_cta', 'popup_modal'];
        return $content[array_rand($content)];
    }

    private function showStatistics(): void
    {
        $totalViews = DB::table('post_views')->count();
        $uniqueUsers = DB::table('post_views')->whereNotNull('user_id')->distinct('user_id')->count();
        $mobileViews = DB::table('post_views')->where('is_mobile', true)->count();
        $botViews = DB::table('post_views')->where('is_bot', true)->count();
        $utmViews = DB::table('post_views')->whereNotNull('utm_source')->count();
        
        $this->command->info("\n📊 Post Views Statistics:");
        $this->command->info("Total views: " . number_format($totalViews));
        $this->command->info("Unique authenticated users: " . number_format($uniqueUsers));
        $this->command->info("Mobile views: " . number_format($mobileViews) . " (" . round(($mobileViews / $totalViews) * 100, 1) . "%)");
        $this->command->info("Bot views: " . number_format($botViews) . " (" . round(($botViews / $totalViews) * 100, 1) . "%)");
        $this->command->info("UTM tracked views: " . number_format($utmViews) . " (" . round(($utmViews / $totalViews) * 100, 1) . "%)");
    }
}
