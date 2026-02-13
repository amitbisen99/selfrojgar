<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
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
        'states_id',
    ];

    /**
     * Get the post that owns the comment.
     */
    public function state()
    {
        return $this->hasOne(State::class, 'id', 'states_id')->latest('created_at');
    }
}
