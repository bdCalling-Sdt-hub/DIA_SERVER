<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'username',
        'description',
        'avatar',
        'servay_name'
    ];

    public function Comments(): HasMany
    {
        return $this->hasMany(Comments::class);
    }

    public function Likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }
}
