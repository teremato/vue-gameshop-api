<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * @api [user/avatar]
     * @var File photo
     * @return string
     */
    
    public function changeUserAvatar(Request $request) {

        $user = $request->user();

        if($request->hasFile("photo")) {

            if(($user->avatar) ? true : false) {

                $avatar = $user->media()
                    ->where("media_type", Media::TYPE_USER_AVATAR)
                    ->first();

                Storage::disk("public")
                    ->delete($avatar["file_path"]);
                
                $avatar->delete();
            }

            $path = Storage::disk("public")
                ->put("users", $request->file("photo"));

            $media = Media::create([
                "user_id" => $user->id,
                "media_type" => Media::TYPE_USER_AVATAR,
                "file_path" => $path,
                "url" => asset(Storage::url($path)),
            ]);

            $user->update([
                "avatar" => asset(Storage::url($media["url"]))
            ]);

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

        $user = $request->user();
        $status = $request->validate([ "status" => ["string", "max:250"] ]);
        
        $user->update([
            "status" => $status["status"]
        ]);

        $user->save();

        return response([
            "msg" => "status is update!",
            "status" => $status
        ]);
    }


}
