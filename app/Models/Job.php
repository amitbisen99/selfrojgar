<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'job_industry_id',
        'role',
        'company_name',
        'company_logo',
        'start_salary',
        'end_salary',
        'type',
        'work_type',
        'address',
        'city_id',
        'state_id',
        'country_id',
        'skills',
        'about',
        'description',
        'mobile',
        'salary_type',
        'employe_level',
        'latitude',
        'longitude',
        'status',
        'pin_code',
    ];

    /**
     * Get the post that owns the comment.
     */
    public function getUser()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * Get the post that owns the comment.
     */
    public function jobIndustry()
    {
        return $this->hasOne(JobIndustry::class, 'id', 'job_industry_id');
    }

    public function getCompanyLogoAttribute($value)
    {
        if(!is_null($value)){
            return asset($value);
        }else{
            return null;
        }
    }

    public function jobApplies()
    {
        return $this->hasMany(JobApply::class);
    }
}
