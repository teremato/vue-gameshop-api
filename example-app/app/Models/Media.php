<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;


class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        "game_id", "user_id",
        "publisher_id", "media_type",
        "file_path", "url"
    ];

    public const TYPE_USER_AVATAR = "user_avatar";
    public const TYPE_USER_MEDIA = "user_media";
    public const TYPE_GAME_MAIN_PHOTO = "game_main_photo";
    public const TYPE_GAME_MEDIA = "game_media";
    public const TYPE_PUBLISHER_MAIN_PHOTO = "publisher_main_photo";
    public const TYPE_PUBLISHER_MEDIA = "publisher_media";


    public function game() {
        return $this->belongsTo(Game::class, "game_id");
    }

    public function user() {
        return $this->belongsTo(User::class, "user_id");
    }
}
