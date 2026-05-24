<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Listing extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'title',
        'slug',
        'description',
        'price',
        'address',
        'city',
        'province',
        'latitude',
        'longitude',
        'gender_type',
        'room_size',
        'max_people',
        'near_campus',
        'near_mall',
        'near_hospital',
        'near_station',
        'total_rooms',
        'available_rooms',
        'is_verified',
        'status',
        'views',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'max_people' => 'integer',
            'total_rooms' => 'integer',
            'available_rooms' => 'integer',
            'is_verified' => 'boolean',
            'latitude' => 'double',
            'longitude' => 'double',
            'views' => 'integer',
        ];
    }

    /**
     * Relationships
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ListingImage::class);
    }

    public function facilities(): BelongsToMany
    {
        return $this->belongsToMany(Facility::class, 'listing_facilities');
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Scopes for easy filtering
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    public function scopeGender($query, $gender)
    {
        if ($gender && in_array($gender, ['putra', 'putri', 'campur'])) {
            return $query->where('gender_type', $gender);
        }
        return $query;
    }

    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }
        return $query;
    }

    public function scopeFilterPrice($query, $min, $max)
    {
        if ($min) {
            $query->where('price', '>=', $min);
        }
        if ($max) {
            $query->where('price', '<=', $max);
        }
        return $query;
    }

    public function scopeFilterCity($query, $city)
    {
        if ($city) {
            return $query->where('city', $city);
        }
        return $query;
    }

    public function scopeFilterFacilities($query, array $facilities)
    {
        if (!empty($facilities)) {
            return $query->whereHas('facilities', function ($q) use ($facilities) {
                $q->whereIn('facilities.id', $facilities);
            }, '=', count($facilities));
        }
        return $query;
    }
}
