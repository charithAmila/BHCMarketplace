<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    use HasFactory;
    protected $table = "sales";

    protected $fillable = [
        "collection",
        "current_owner",
        "token_id",
        "signed_to",
        "price",
        'is_instant',
        "currency",
        "signature",
        "salt","sold"
    ];
}
