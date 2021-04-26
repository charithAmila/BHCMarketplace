<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ipfs extends Model
{
    use HasFactory;
    protected $table="ipfs";
    protected $fillable = [
        "hash",
        "data"
    ];
}
