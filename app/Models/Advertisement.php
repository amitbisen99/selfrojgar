<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'offer_name',
        'description',
        'mobile_no',
        'country_id',
        'state_id',
        'city_id',
        'address',
        'images',
        'status',
        'pin_code',
    ];

    public function genres()
    {
        return $this->hasOne(Genres::class, 'id', 'genres_id');
    }

    public function sponsor()
    {
        return $this->hasOne(User::class, 'id', 'user_id')->select('id', 'name', 'contact_number', 'profile_pic', 'firebase_token');
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
}
