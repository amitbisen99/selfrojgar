<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'status',
        'countries_id',
    ];

    /**
     * Get the post that owns the comment.
     */
    public function country()
    {
        return $this->hasOne(Country::class, 'id', 'countries_id')->latest('created_at');
    }
}
