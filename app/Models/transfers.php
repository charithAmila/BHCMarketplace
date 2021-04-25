<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transfers extends Model
{
    use HasFactory;
    protected $table = "transfers";
    protected $fillable = [
        'block','collection','owner','token_id'
    ];
}
