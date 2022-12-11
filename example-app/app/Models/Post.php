<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id", "publisher_id",
        "text", "photo_id"
    ];

    public function user() {
        return $this->belongsTo(User::class, "user_id");
    }

    public function publisher() {
        return $this->belongsTo(Publisher::class, "publisher_id");
    }

    public function media() {
        return $this->belongsTo(Media::class, "photo_id");
    }

    public function likes() {
        return $this->hasMany(Like::class, "entity_id");
    }

    public function favorites() {
        return $this->hasMany(Favorite::class, "entity_id");
    }
}
