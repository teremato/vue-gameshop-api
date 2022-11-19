<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class PostController extends Controller
{

    public function store(PostRequest $request) {

        /* Получаем юзера */
        $user = $request->user();
        $photo = null;

        /* Проверяем на наличие фото */
        if($request->file("photo")) {
            
            /* Добавляем фото на диск */
            $path = Storage::disk("public")
                ->put("posts", $request->photo);

            $photo = asset(Storage::url($path));
        }

        /* Добавляем пост в бд */
        $post = Post::create([
            "user_id" => $user->id,
            "text" => $request->text,
            "photo" => $photo,
            "game_title" => $request->game_title ?: null
        ]);

        /* Сохраняем */
        $post->save();

        return response([
            "msg" => "Post created!",
            "post" => $post
        ]);
    }

    public function update(Request $request) {

    }

    public function delete() {

    }
}
