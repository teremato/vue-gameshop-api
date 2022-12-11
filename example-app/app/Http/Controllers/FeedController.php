<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class FeedController extends Controller
{
    /** Получаем новые записи, свои и друзей */
    public function getNewFeed(Request $request) {
        
        $user = $request->user();
        $friends =  array_merge([$user->id], $user->friends()
            ->pluck("user_id")->toArray());

        $posts = Post::whereIn("user_id", $friends)
            ->orderBy("created_at")
            ->forPage(
                $request->get("page", 1),
                $request->get("per_page", 10)
            )->get();
        
        return PostResource::collection($posts);

    }

    /** Получаем записи друзей */
    public function getFriendsFeed(Request $request) {

        $user = $request->user();
        $friends = $user->friends()
            ->pluck("user_id")
            ->toArray();

        $posts = Post::whereIn("user_id", $friends)
            ->orderBy("created_at")
            ->forPage(
                $request->get("page", 1),
                $request->get("per_page", 10)
            )->get();

        return PostResource::collection($posts);
    }

    /** Получаем популярные записи по всему приложения */
    public function getPopularityFeed(Request $request) {

        $posts = Post::with(["likes", "favorites"])
            ->withCount("likes", "favorites")
            ->orderBy("likes_count", "desc")
            ->orderBy("favorites_count", "desc")
            ->forPage(
                $request->get("page", 1),
                $request->get("per_page", 10)
            )->get();

        return PostResource::collection($posts);
    }

    /** Получаем избранные записи пользователя */
    public function getFavoriteFeed(Request $request) {

        $user = $request->user();
        $favorites = $user->favorites()
            ->orderBy("created_at", "desc")
            ->pluck("entity_id")
            ->toArray();

        $posts = Post::whereIn("id", $favorites)
            ->forPage(
                $request->get("page", 1),
                $request->get("per_page", 10)
            )->get();

        return PostResource::collection($posts);
    }
}
