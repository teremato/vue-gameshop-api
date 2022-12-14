<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'avatar',
        "nickname",
        "country",
        "favorite_game"
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /** Отношения */

    public function media() {
        return $this->hasMany(Media::class, "user_id");
    }

    public function posts() {
        return $this->hasMany(Post::class, "user_id");
    }

    public function friends() {
        return $this->hasMany(Friend::class, "friend_id");
    }

    public function likes() {
        return $this->hasMany(Like::class, "user_id");
    }

    public function favorites() {
        return $this->hasMany(Favorite::class, "user_id");
    }

    public function cart() {
        return $this->hasMany(Cart::class, "user_id");
    }

    /** Проверки */

    public function hasFriend($id) {
        return $this->friends()->where("user_id", $id)
            ->count() ? true : false;
    }

    public function hasLikePost($id) {
        return $this->likes()->where("entity_id", $id)
            ->where("entity_type", Like::TYPE_POST)
            ->count() ? true : false;
    }

    public function hasLikeGame($id) {
        return $this->likes()->where("entity_id", $id)
            ->where("entity_type", Like::TYPE_GAME)
            ->count() ? true : false;
    }

    public function hasFavoritePost($id) {
        return $this->favorites()->where("entity_id", $id)
            ->where("entity_type", Favorite::TYPE_POST)
            ->count() ? true : false;
    }

    public function hasFavoriteGame($id) {
        return $this->favorites()->where("entity_id", $id)
            ->where("entity_type", Favorite::TYPE_GAME)
            ->count() ? true : false;
    }
}
