<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'profile_pic',
        'profession',
        'about',
        'experience',
        'education',
        'contact_number',
        'city',
        'state',
        'country',
        'latitude',
        'longitude',
        'address',
        'firebase_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_verified_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the post that owns the comment.
     */
    public function payment()
    {
        return $this->hasOne(Payment::class, 'user_id', 'id')->latest('created_at');
    }

    public function getProfilePicAttribute($value)
    {
        return $this->modifyProfilePicUrl($value);
    }

    // Define a method to modify the profile pic URL
    private function modifyProfilePicUrl($url)
    {
        if (!is_null($url)) {
            return asset($url);
        } else {
            return null;
        }
    }

    public function properties()
    {
        return $this->hasMany(Property::class, 'user_id');
    }

    public function jobs()
    {
        return $this->hasMany(Job::class, 'user_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'user_id');
    }

    public function wholeSellProducts()
    {
        return $this->hasMany(WholeSellProduct::class, 'user_id');
    }

    public function onDemandServices()
    {
        return $this->hasMany(OnDemandService::class, 'user_id');
    }

    public function advertisements()
    {
        return $this->hasMany(Advertisement::class, 'user_id');
    }

    public function tourisms()
    {
        return $this->hasMany(Tourism::class, 'user_id');
    }

    public function franchiseBusinesses()
    {
        return $this->hasMany(FranchiseBusiness::class, 'user_id');
    }

    public function businesses()
    {
        return $this->hasMany(Business::class, 'user_id');
    }
}
