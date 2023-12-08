<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersTransactions extends Model
{
    use HasFactory;

    protected $fillable = [
        "type",
        "user_id",
        "username",
        "account_id",
        "account_name",
        "account_number",
        "receiver_id",
        "receiver_username",
        "receiver_account_id",
        "receiver_account_name",
        "receiver_account_number",
        "amount",
    ];

    protected $casts = [
        "amount" => "float",
    ];
}
