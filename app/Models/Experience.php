<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'role',
        'company_name',
        'joining_date',
        'end_date',
        'is_present',
        'company_logo',
    ];

    public function getCompanyLogoAttribute($value)
    {
        if(!is_null($value)){
            return asset($value);
        }else{
            return null;
        }
    }
}
