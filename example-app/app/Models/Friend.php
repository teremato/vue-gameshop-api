<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Friend extends Model
{
    use HasFactory;
    
    public const FRIEND_NOT_ACCEPT = false;
    public const FRIEND_ACCEPT = true;

    protected $fillable = [ "user_id", "friend_id", "accept" ];
    protected $casts = ['accept' => 'boolean'];


    public function user() {
        return $this->belongsTo(User::class, "user_id");
    }

    public function posts() {
        return $this->belongsTo(Post::class, "friend_id", $this->user()->id);
    }
}
