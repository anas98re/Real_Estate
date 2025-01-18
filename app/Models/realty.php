<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Realty extends Model
{
    use HasFactory, HasTranslations;

    public $translatable = ['name','description','location' ];

    protected $fillable = [
        'name',
        'description',
        'price',
        'photo',
        'location',
        'country',
        'city',
        'num_rooms',
        'num_bathrooms',
        'space_sqft', // Space in square feet
        'year_built', // Year the property was built'
        'parking_spaces', // Number of parking spaces
        'lot_size', // Lot size of the property
        'status',
        'average_rating'
    ];

    // Define the attributes that should be cast to native types
    protected $casts = [
        'price' => 'int',
    ];

    // Mutator to set the price attribute as a int
    public function setPriceAttribute($value)
    {
        $this->attributes['price'] = (int) $value;
    }

    // Accessor to retrieve the price attribute as a int
    public function getPriceAttribute($value)
    {
        return (int) $value;
    }

    public function owner()
    {
        return $this->hasManyThrough(
            User::class,
            Realty_user::class,
            'realty_id',
            'id',
            'id',
            'user_id'
        )
            ->where('type_user', 'owner');
    }

    public function agent()
    {
        return $this->hasManyThrough(
            User::class,
            Realty_user::class,
            'realty_id',  // Foreign key on the Realty_user table
            'id',         // Foreign key on the User table
            'id',         // Local key on the Realty table
            'user_id'     // Local key on the Realty_user table
        );
    }

    public function photos()
    {
        return $this->morphMany(Photo::class, 'model');
    }

    public function features()
    {
        return $this->hasMany(RealtyFeatures::class, 'realty_id');
    }

    public function important_points()
    {
        return $this->hasMany(RealtyImportantPoints::class, 'realty_id');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'realty_id');
    }

    // For Filters
    public function scopeFilter($query, $filters)
    {
        // $query = self::query();

        if (isset($filters['min_price'])) {
            $query->whereRaw('CAST(price AS DECIMAL(10,2)) >= ?', [(float)$filters['min_price']]);
        }

        if (isset($filters['max_price'])) {
            $query->whereRaw('CAST(price AS DECIMAL(10,2)) <= ?', [(float)$filters['max_price']]);
        }

        if (isset($filters['location'])) {
            $query->where('location', 'like', '%' . $filters['location'] . '%');
        }

        if (isset($filters['country'])) {
            $query->where('country', $filters['country']);
        }

        // Filter by multiple cities
        if (isset($filters['cities']) && is_array($filters['cities']) && count($filters['cities']) > 0) {
            $query->whereIn('city', $filters['cities']);
        }

        // Keyword search in name, description and location and related tables
        if (isset($filters['keyword'])) {
            $query->where(function ($query) use ($filters) {
                $query->where('name', 'like', '%' . $filters['keyword'] . '%')
                    ->orWhere('description', 'like', '%' . $filters['keyword'] . '%')
                    ->orWhere('location', 'like', '%' . $filters['keyword'] . '%')
                    ->orWhereHas('features', function ($query) use ($filters) {
                        $query->where('name', 'like', '%' . $filters['keyword'] . '%')
                            ->orWhere('description', 'like', '%' . $filters['keyword'] . '%');
                    })
                    ->orWhereHas('important_points', function ($query) use ($filters) {
                        $query->where('name', 'like', '%' . $filters['keyword'] . '%')
                            ->orWhere('description', 'like', '%' . $filters['keyword'] . '%');
                    });
            });
        }

        if (isset($filters['num_rooms'])) {
            $query->whereRaw('CAST(num_rooms AS DECIMAL(10,2)) >= ?', [(float)$filters['num_rooms']]);
        }

        if (isset($filters['num_bathrooms'])) {
            $query->whereRaw('CAST(num_bathrooms AS DECIMAL(10,2)) >= ?', [(float)$filters['num_bathrooms']]);
        }

        if (isset($filters['parking_spaces'])) {
            $query->whereRaw('CAST(parking_spaces AS DECIMAL(10,2)) >= ?', [(float)$filters['parking_spaces']]);
        }

        if (isset($filters['min_space_sqft'])) {
            $query->whereRaw('CAST(space_sqft AS DECIMAL(10,2)) >= ?', [(float)$filters['min_space_sqft']]);
        }

        if (isset($filters['max_space_sqft'])) {
            $query->whereRaw('CAST(space_sqft AS DECIMAL(10,2)) <= ?', [(float)$filters['max_space_sqft']]);
        }

        if (isset($filters['year_built'])) {
            $query->whereRaw('CAST(year_built AS DECIMAL(10,2)) >= ?', [(float)$filters['year_built']]);
        }

        if (isset($filters['max_lot_size'])) {
            $query->whereRaw('CAST(lot_size AS DECIMAL(10,2)) <= ?', [(float)$filters['max_lot_size']]);
        }

        if (isset($filters['min_lot_size'])) {
            $query->whereRaw('CAST(lot_size AS DECIMAL(10,2)) >= ?', [(float)$filters['min_lot_size']]);
        }

        return $query;
    }
}
