<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class questions extends Model
{
    use HasFactory;

    protected $fillable = [
        'categoryes',
        'sub_catergory',
        'questions',
        'ans',
        'mark',
        'image_one',
        'image_two',
    ];
}
