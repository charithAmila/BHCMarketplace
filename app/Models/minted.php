<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class minted extends Model
{
    use HasFactory;
    protected $table="minteds";
    protected $fillable=[
        "block",
        "minter",
        "collection",
        "token_id"
    ];
}
