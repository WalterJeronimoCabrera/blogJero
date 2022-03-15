<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeGusta extends Model
{
    use HasFactory;
    protected $fillable = [
        'userID',
        'posteoID',
        'liked',
    ];
}
