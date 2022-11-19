<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Media;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class MediaController extends Controller
{

    /**
     * @api [/media/game/{game:id}/add]
     * @param photos [files:.jpg,.png]
     */

    public function addGameMedia(Request $request, Game $game) {

        /* Проверка на наличие файлов в реквесте */
        if($request->hasFile("photos")) {
            
            /* Перебираем файлы */
            foreach($request->file("photos") as $file) {

                /* Добавляем файл на диск и получаем путь */
                $path = Storage::disk("public")
                    ->put("/games", $file);

                /* Создаем запись в бд */
                $game->media()->create([
                    "game_id" => $game->id,
                    "media_type" => Media::TYPE_GAME_MEDIA,
                    "file_path" => $path,
                    "url" => asset(Storage::url($path)),
                ])->save();
            }

            return response([
                "msg" => "photos is upload!"
            ]);
        }
        else return response([ "msg" => "Not files" ]);
    }

    /**
     * @api [/media/game/{media:id}/delete]
     * @param photos [files:.jpg,.png]
     */

    public function deleteGameMedia(Request $request, Media $media) {

        /* Получаем игру */
        $game = $media->game()
            ->first();
            
        /* Удаляем файл с диска */
        Storage::disk("public")
            ->delete($media->file_path);

        /* Удаляем файл из бд */
        $game->media()
            ->where("media_type", Media::TYPE_GAME_MEDIA)
            ->where("id", $media->id)
            ->delete();

        return response([
            "msg" => "media is deleted!"
        ]);
    }

    /**
     * @api [/media/user/{user:id}/add]
     * @param photos [file:.jpg,.png]
     */

    public function addUserMedia(Request $request, User $user) {
        
        /* Проверяем на наличие файла */
        if($request->file("photo")) {

            /* Сохраняем на диск */
            $path = Storage::disk("public")
                ->put("/users", $request->file("photo"));

            /* Создаем запись о файле в бд */
            $photo = Media::create([
                "user_id" => $user->id,
                "media_type" => Media::TYPE_USER_MEDIA,
                "file_path" => $path,
                "url" => asset(Storage::url($path)),
            ]);

            return response([
                "msg" => "photo is added",
                "photo" => [
                    "id" => $photo["id"],
                    "image" => $photo["url"]
                ]
            ]);
        }
        else return response([ "msg" => "Not files" ]);
    }

    /**
     * @api [/media/user/{media:id}/delete]
     * @param photos [files:.jpg,.png]
     */

    public function deleteUserMedia(Request $request, Media $media) {

        /* Получаем юзера */
        $user = $media->game()
            ->first();
        
        /* Удаляем файл */
        Storage::disk("public")
            ->delete($media->file_path);

        /* Удаляем файл из бд */
        $user->media()
            ->where("media_type", Media::TYPE_USER_MEDIA)
            ->where("id", $media->id)
            ->delete();

        return response([
            "msg" => "media is deleted!"
        ]);
    }

}
