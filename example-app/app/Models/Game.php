<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\GameSettings;
use App\Models\GamePhotos;

class Game extends Model
{
    use HasFactory;

    protected $fillable  = [
        "title",
        "description",
        "price",
        "slug",
        "main_photo",
        "publisher_id"
    ];
    
    public function gamePhotos() {
        return $this->hasMany(GamePhotos::class);
    }

    public function gameSettings() {
        return $this->hasOne(GameSettings::class);
    }

    public function publisher() {
        return $this->belongsTo(Publisher::class);
    }
}
