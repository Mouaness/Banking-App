<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id", 
        "currency", 
        "balance", 
        "status",
    ];

    protected $casts = [
        "balance" => "float",
    ];
} 
