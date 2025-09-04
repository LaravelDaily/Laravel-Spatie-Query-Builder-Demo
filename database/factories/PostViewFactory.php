<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PostView>
 */
class PostViewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $userAgents = [
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            'Mozilla/5.0 (iPhone; CPU iPhone OS 14_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Mobile/15E148 Safari/604.1',
            'Mozilla/5.0 (iPad; CPU OS 14_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Mobile/15E148 Safari/604.1',
            'Mozilla/5.0 (Android 11; Mobile; rv:68.0) Gecko/68.0 Firefox/88.0',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:89.0) Gecko/20100101 Firefox/89.0',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.1.1 Safari/605.1.15',
        ];

        $devices = ['desktop', 'mobile', 'tablet'];
        $browsers = ['Chrome', 'Firefox', 'Safari', 'Edge', 'Opera'];
        $platforms = ['Windows', 'macOS', 'Linux', 'iOS', 'Android'];
        $countries = ['US', 'GB', 'CA', 'AU', 'DE', 'FR', 'ES', 'IT', 'NL', 'BE', 'SE', 'NO', 'DK', 'FI', 'PL', 'CZ', 'AT', 'CH', 'IE', 'PT'];
        $cities = ['New York', 'London', 'Toronto', 'Sydney', 'Berlin', 'Paris', 'Madrid', 'Rome', 'Amsterdam', 'Brussels', 'Stockholm', 'Oslo', 'Copenhagen', 'Helsinki', 'Warsaw', 'Prague', 'Vienna', 'Zurich', 'Dublin', 'Lisbon'];
        $utmSources = ['google', 'facebook', 'twitter', 'linkedin', 'youtube', 'instagram', 'reddit', 'pinterest', 'tiktok', 'snapchat', 'direct', 'email', 'newsletter'];
        $utmMediums = ['organic', 'cpc', 'social', 'email', 'referral', 'display', 'video', 'affiliate'];
        $referers = [
            'https://www.google.com/',
            'https://www.facebook.com/',
            'https://twitter.com/',
            'https://www.linkedin.com/',
            'https://news.ycombinator.com/',
            'https://reddit.com/',
            'https://stackoverflow.com/',
            'https://github.com/',
            null, null, null, // more direct visits
        ];

        $deviceType = fake()->randomElement($devices);
        $isMobile = in_array($deviceType, ['mobile', 'tablet']);
        $isBot = fake()->boolean(5); // 5% chance of being a bot
        $hasUser = fake()->boolean(30); // 30% chance of authenticated user
        $hasUtm = fake()->boolean(25); // 25% chance of UTM parameters
        
        return [
            'post_id' => Post::factory(),
            'user_id' => $hasUser ? User::factory() : null,
            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->randomElement($userAgents),
            'referer' => fake()->randomElement($referers),
            'device_type' => $deviceType,
            'browser' => fake()->randomElement($browsers),
            'browser_version' => fake()->randomFloat(1, 80, 120) . '.0',
            'platform' => fake()->randomElement($platforms),
            'country_code' => fake()->randomElement($countries),
            'region' => fake()->state(),
            'city' => fake()->randomElement($cities),
            'timezone' => fake()->timezone(),
            'utm_source' => $hasUtm ? fake()->randomElement($utmSources) : null,
            'utm_medium' => $hasUtm ? fake()->randomElement($utmMediums) : null,
            'utm_campaign' => $hasUtm ? fake()->words(2, true) : null,
            'utm_term' => $hasUtm && fake()->boolean(60) ? fake()->words(3, true) : null,
            'utm_content' => $hasUtm && fake()->boolean(40) ? fake()->words(2, true) : null,
            'session_duration' => fake()->numberBetween(10, 3600), // 10 seconds to 1 hour
            'is_bot' => $isBot,
            'is_mobile' => $isMobile,
            'extra_data' => fake()->boolean(20) ? [
                'screen_resolution' => fake()->randomElement(['1920x1080', '1366x768', '1440x900', '1536x864', '1024x768']),
                'color_depth' => fake()->randomElement([24, 32]),
                'javascript_enabled' => fake()->boolean(95),
                'language' => fake()->randomElement(['en-US', 'en-GB', 'fr-FR', 'de-DE', 'es-ES', 'it-IT']),
            ] : null,
            'viewed_at' => fake()->dateTimeBetween('-30 days', 'now'),
        ];
    }

    public function authenticated(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'user_id' => User::factory(),
            ];
        });
    }

    public function anonymous(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'user_id' => null,
            ];
        });
    }

    public function mobile(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'device_type' => 'mobile',
                'is_mobile' => true,
                'user_agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Mobile/15E148 Safari/604.1',
            ];
        });
    }

    public function desktop(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'device_type' => 'desktop',
                'is_mobile' => false,
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            ];
        });
    }

    public function bot(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'is_bot' => true,
                'user_agent' => 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)',
                'user_id' => null,
            ];
        });
    }

    public function withUtm(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'utm_source' => fake()->randomElement(['google', 'facebook', 'twitter', 'linkedin']),
                'utm_medium' => fake()->randomElement(['organic', 'cpc', 'social']),
                'utm_campaign' => fake()->words(2, true),
            ];
        });
    }
}
