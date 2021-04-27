<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NftData extends Model
{
    use HasFactory;
    protected $table = "nft_data";
    protected $fillable = [
        "collection",
        "token_id",
        "uri"
    ];
}
