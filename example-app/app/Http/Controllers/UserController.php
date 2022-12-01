<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\Media;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{

    /**
     * @api [user/avatar]
     * @var File photo
     * @return string
     */

    public function getUser(Request $request) {
        return new UserResource($request->user());
    }

    public function getUserById(User $user) {
        return new UserResource($user);
    }
    
    public function changeUserAvatar(Request $request) {

        /* Получаем юзера через token */
        $user = $request->user();

        /* Делаем проверку на наличие файла */
        if($request->hasFile("photo")) {

            /* Проверяем, есть ли фото у юзера */
            if(($user->avatar) ? true : false) {

                /* Если есть, находим его */
                $avatar = $user->media()
                    ->where("media_type", Media::TYPE_USER_AVATAR)
                    ->first();

                /* Удаляем с диска */
                Storage::disk("public")
                    ->delete($avatar["file_path"]);
                
                /* Удаляем из бд */
                $avatar->delete();
            }

            /* Добавляем фото на диск */
            $path = Storage::disk("public")
                ->put("users", $request->file("photo"));

            /* Создаем запись в бд */
            $media = Media::create([
                "user_id" => $user->id,
                "media_type" => Media::TYPE_USER_AVATAR,
                "file_path" => $path,
                "url" => asset(Storage::url($path)),
            ]);

            /* Обновляем аватар у юзера */
            $user->update([
                "avatar" => $media["url"]
            ]);

            /* Сохраняем фотку и юзера */
            $media->save();
            $user->save();

            return response([
                "msg" => "avatar update!",
                "avatar" => $user->avatar
            ]);
        }
        else return response([
            "msg" => "not file!"
        ]);
    }

    /**
     * @api [user/status]
     * @var status status
     * @return string
     */

    public function changeUserStatus(Request $request) {

        /* Получаем юзера и проверяем request */
        $user = $request->user();
        $status = $request->validate([ "status" => ["string", "max:250"] ]);
        
        /* Обновляем статус в бд */
        $user->update([
            "status" => $status["status"]
        ]);

        /* Сохраняем юзера */
        $user->save();

        return response([
            "msg" => "status is update!",
            "status" => $status
        ]);
    }

    public function getUserSettings(Request $request) {
        
    }

    public function changeUserSettings(Request $request) {

    }

}
