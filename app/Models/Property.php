<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'property_for',
        'area_value',
        'area_for',
        'area_price_for',
        'area_price',
        'total_price',
        'rent_price',
        'rent_price_type',
        'country_id',
        'state_id',
        'city_id',
        'address',
        'pincode',
        'contactno',
        'latitude',
        'longitude',
        'images',
        'status',
        'dimension',
    ];

    /**
     * Get the post that owns the comment.
     */
    public function owner()
    {
        return $this->hasOne(User::class, 'id', 'user_id')->select(['id', 'name', 'profile_pic', 'contact_number', 'firebase_token']);
    }

    /**
     * Get the post that owns the comment.
     */
    public function city()
    {
        return $this->hasOne(City::class, 'id', 'city_id');
    }

    public function state()
    {
        return $this->hasOne(State::class, 'id', 'state_id');
    }

    public function country()
    {
        return $this->hasOne(Country::class, 'id', 'country_id');
    }

    public function propertyCategory()
    {
        return $this->hasOne(PropertyCategory::class, 'id', 'category_id');
    }

    public function ratings()
    {
        return $this->hasMany(BusinessRating::class, 'business_id');
    }

    public function getImagesAttribute($value)
    {
        if(!is_null($value)){
            $newImages = [];
            $images = explode(" || ", $value);
            if (!empty($images)) {
                foreach ($images as $image) {
                    if (!empty($image)) {
                        $newImages[] = asset($image);
                    }
                }
            }

            $newImages = implode(" || ", $newImages);
            return $newImages;
        }else{
            return null;
        }
    }
}
