<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Media;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class PostController extends Controller
{

    public function store(Request $request) {

        /* Получаем юзера */
        $user = $request->user();
        $photo = null;

        /* Проверяем на наличие фото */
        if($request->file("photo")) {
            
            /* Добавляем фото на диск */
            $path = Storage::disk("public")
                ->put("posts", $request->file("photo"));

            /* Добавляем в бд */
            $photo = $user->media()->create([
                "user_id" => $user->id,
                "media_type" => Media::TYPE_USER_MEDIA,
                "file_path" => $path,
                "url" => asset(Storage::url($path)),
            ]);

            $photo->save();
        }

        /* Добавляем пост в бд */
        $post = Post::create([
            "user_id" => $user->id,
            "text" => $request->text,
            "photo_id" => $photo->id,
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
