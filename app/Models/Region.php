<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    /** @use HasFactory<\Database\Factories\RegionFactory> */
    use HasFactory;
    protected $fillable = [
        'region',
        'region_desc',
        'sort_order',
        'parent_region_id',
        'region_category_id',
        'region_type_id'
    ];
    // Define the parent region relationship
    public function parent_region()
    {
        return $this->belongsTo(Region::class, 'parent_region_id');
    }

    // Define the child regions relationship
    public function child_regions()
    {
        return $this->hasMany(Region::class, 'parent_region_id');
    }
    public function region_category()
    {
        return $this->belongsTo(RegionCategory::class);
    }
    public function region_type()
    {
        return $this->belongsTo(RegionType::class);
    }
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'cruising_location');
    }
}
