<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Plot extends Model
{
    use HasFactory;

    protected $fillable = [
        'plot_id',
        'display_name', 
        'block',
        'coordinates',
        'price',
        'area',
        'status',
        'amenities',
        'layout',
        'description',
        'layout_id'
    ];

    protected $casts = [
        'coordinates' => 'array',
        'amenities' => 'array',
        'price' => 'decimal:2'
    ];

    // Scope for available plots
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    // Scope for booked plots
    public function scopeBooked($query)
    {
        return $query->where('status', 'booked');
    }

    // Scope for specific layout
    public function scopeForLayout($query, $layoutId)
    {
        return $query->where('layout_id', $layoutId);
    }

    // Accessor for formatted price
    public function getFormattedPriceAttribute()
    {
        return '₹' . number_format($this->price, 0, '.', ',');
    }

    // Accessor for price per square foot
    public function getPricePerSqftAttribute()
    {
        return $this->area > 0 ? round($this->price / $this->area) : 0;
    }
}
