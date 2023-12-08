<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "username",
        "account_name",
        "currency",
        "status",
    ];

    protected $casts = [
        "amount" => "float",
    ];
}
