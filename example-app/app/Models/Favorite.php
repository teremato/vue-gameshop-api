<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    public const TYPE_POST = "post";
    public const TYPE_GAME = "game";

    protected $fillable = [
        "user_id",
        "entity_id",
        "entity_type"
    ];
}
