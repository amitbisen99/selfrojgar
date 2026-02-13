<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'payment_id',
        'amount',
        'start_date',
        'end_date',
        'status',
    ];

    /**
     * Get the post that owns the comment.
     */
    public function getUser()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
