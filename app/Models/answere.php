<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class answere extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'cate_id',
        'sub_catecory_id',
        'mark',
    ];
}
