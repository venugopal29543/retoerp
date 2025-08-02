<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenantFeature extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'feature_name',
        'enabled',
        'settings',
        'usage_limits',
        'usage_count'
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'settings' => 'array',
        'usage_limits' => 'array',
        'usage_count' => 'array'
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    // Usage tracking
    public function incrementUsage($metric = 'default')
    {
        $usage = $this->usage_count ?? [];
        $usage[$metric] = ($usage[$metric] ?? 0) + 1;
        
        $this->update(['usage_count' => $usage]);
    }

    public function hasUsageLeft($metric = 'default')
    {
        $limits = $this->usage_limits ?? [];
        $usage = $this->usage_count ?? [];
        
        if (!isset($limits[$metric])) {
            return true; // No limit set
        }
        
        return ($usage[$metric] ?? 0) < $limits[$metric];
    }
}
