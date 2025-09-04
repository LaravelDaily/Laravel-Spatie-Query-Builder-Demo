<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostView extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'user_id',
        'ip_address',
        'user_agent',
        'referer',
        'device_type',
        'browser',
        'browser_version',
        'platform',
        'country_code',
        'region',
        'city',
        'timezone',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_term',
        'utm_content',
        'session_duration',
        'is_bot',
        'is_mobile',
        'extra_data',
        'viewed_at',
    ];

    protected function casts(): array
    {
        return [
            'viewed_at' => 'datetime',
            'is_bot' => 'boolean',
            'is_mobile' => 'boolean',
            'extra_data' => 'array',
            'session_duration' => 'integer',
        ];
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeAuthenticated($query)
    {
        return $query->whereNotNull('user_id');
    }

    public function scopeAnonymous($query)
    {
        return $query->whereNull('user_id');
    }

    public function scopeNotBot($query)
    {
        return $query->where('is_bot', false);
    }

    public function scopeMobile($query)
    {
        return $query->where('is_mobile', true);
    }

    public function scopeDesktop($query)
    {
        return $query->where('is_mobile', false);
    }

    public function scopeByCountry($query, string $countryCode)
    {
        return $query->where('country_code', $countryCode);
    }

    public function scopeWithUtm($query)
    {
        return $query->whereNotNull('utm_source');
    }
}
