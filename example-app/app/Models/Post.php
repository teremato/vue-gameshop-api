<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id", "publisher_id",
        "text", "photo"
    ];

    public function user() {
        return $this->belongsTo(User::class, "user_id");
    }

    public function publisher() {
        return $this->belongsTo(Publisher::class, "publisher_id");
    }

    public function media() {
        return $this->hasMany(Media::class, "id");
    }
}
