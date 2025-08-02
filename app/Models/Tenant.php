<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tenant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'domain',
        'subdomain',
        'status',
        'settings',
        'features_config',
        'subscription_plan',
        'expires_at'
    ];

    protected $casts = [
        'settings' => 'array',
        'features_config' => 'array',
        'expires_at' => 'datetime'
    ];

    // Relationships
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'tenant_users')
                    ->withPivot(['role_id', 'status', 'permissions'])
                    ->withTimestamps();
    }

    public function features()
    {
        return $this->hasMany(TenantFeature::class);
    }

    public function roles()
    {
        return $this->hasMany(Role::class);
    }

    // Feature Management Methods
    public function hasFeature($featureName)
    {
        return $this->features()
                    ->where('feature_name', $featureName)
                    ->where('enabled', true)
                    ->exists();
    }

    public function getFeatureSettings($featureName)
    {
        $feature = $this->features()
                        ->where('feature_name', $featureName)
                        ->first();
        
        return $feature ? $feature->settings : [];
    }

    public function enableFeature($featureName, $settings = [])
    {
        return $this->features()->updateOrCreate(
            ['feature_name' => $featureName],
            ['enabled' => true, 'settings' => $settings]
        );
    }

    public function disableFeature($featureName)
    {
        return $this->features()
                    ->where('feature_name', $featureName)
                    ->update(['enabled' => false]);
    }

    // Predefined feature list
    public static function getAvailableFeatures()
    {
        return [
            'emi_calculator' => [
                'name' => 'EMI Calculator',
                'description' => 'Loan EMI calculation tool',
                'category' => 'financial'
            ],
            'location_map' => [
                'name' => 'Location Map',
                'description' => 'Google Maps integration',
                'category' => 'location'
            ],
            'plot_comparison' => [
                'name' => 'Plot Comparison',
                'description' => 'Side-by-side plot comparison',
                'category' => 'analysis'
            ],
            'whatsapp_support' => [
                'name' => 'WhatsApp Support',
                'description' => 'WhatsApp integration for support',
                'category' => 'communication'
            ],
            'video_tours' => [
                'name' => 'Video Tours',
                'description' => 'Virtual video tours of properties',
                'category' => 'media'
            ],
            'photo_gallery' => [
                'name' => 'Photo Gallery',
                'description' => 'Property image galleries',
                'category' => 'media'
            ],
            'booking_queue' => [
                'name' => 'Booking Queue',
                'description' => 'Queue system for blocked properties',
                'category' => 'booking'
            ],
            'price_alerts' => [
                'name' => 'Price Alerts',
                'description' => 'Price change notifications',
                'category' => 'notification'
            ],
            'advanced_search' => [
                'name' => 'Advanced Search',
                'description' => 'Multi-criteria property search',
                'category' => 'search'
            ],
            'customer_portal' => [
                'name' => 'Customer Portal',
                'description' => 'Customer dashboard and booking history',
                'category' => 'customer'
            ]
        ];
    }
}
