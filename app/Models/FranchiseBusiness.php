<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FranchiseBusiness extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'franchise_categories_id',
        'name',
        'description',
        'city_id',
        'state_id',
        'country_id',
        'address',
        'pin_code',
        'industry_experience',
        'investment',
        'other',
        'images',
        'status',
        'mobile',
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

    public function franchiseCategory()
    {
        return $this->hasOne(FranchiseCategory::class, 'id', 'franchise_categories_id');
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
