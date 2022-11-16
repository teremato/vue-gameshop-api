<?php

namespace App\Http\Controllers;

use App\Http\Requests\GameRequest;
use App\Http\Resources\GameResource;
use App\Http\Resources\GameShortResource;

use App\Models\Game;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GameController extends Controller
{
    /**
     * @api [/games/] Получить игры 
     * @param sort [string] Тип сортировки
     * @param page [number] Колчество страниц
     * @param per_page [number] Количество игр за страницу
     */

    public function games(Request $request) {

        return GameShortResource::collection(Game::all()
            ->sortBy($request->get('sort', 'created_at'))
            ->forPage(
                $request->get('page', 1),
                $request->get('per_page', 9)
            ));
    }

    /**
    * @api [/games/slug] Получить одну игру
    * @param slug [string] slug игры
    */

    public function gameItem(Game $game) {

        return new GameResource(Game::findOrFail($game->id)
            ->first()
        );
    }

    /**
    * @api [/games/create] Создать игру
    * @param => @var game =>
    */
    /*
    {
        game: {
            title: string,
            description: string,
            main_photo: File[.jpg, .phg],
            price: int,
        },
        photos: Array<File[.jpg, .phg]>
    }
    */

    public function store(GameRequest $request) {

        $game = Game::create([
            "title" => $request->title,
            "description" => $request->description,
            "price" => $request->price,
            "publisher_id" => 1,
            "slug" => Str::slug($request->title, '-')
        ]);

        $path = Storage::disk("public")
            ->put("/games", $request->file("main_photo"));

        $main_photo = Media::create([
            "game_id" => $game->id,
            "media_type" => Media::TYPE_GAME_MAIN_PHOTO,
            "file_path" => $path,
            "url" => asset(Storage::url($path)),
        ]);

        $game->main_photo = $main_photo["url"];

        $game->save();
        $main_photo->save();

        
        return response([
            "msg" => "game created!",
            "game" => new GameShortResource($game)
        ]);
    }

    /**
    * @api [/games/update/{@var game->id}] Обновить игру
    * @param => @var game =>
    * @var title [string] 
    * @var description [string] 
    * @var price [number] 
    */

    public function update(Request $request, Game $game) {

        $fields = $request->only([
            "title", "description", 
            "price", "main_photo"
        ]);

        $game->update($fields,[
            "title" => $request->title ?: $game->title,
            "description" => $request->description ?: $game->description,
            "price" => $request->price ?: $game->price
        ]);

        if($request->hasFile("main_photo")) {

            $main_photo = $game->media()
                ->where("media_type", Media::TYPE_GAME_MAIN_PHOTO)
                ->first();

            Storage::disk("public")
                ->delete($main_photo->file_path);

            $path = Storage::disk("public")
                ->put("/games", $request->file("main_photo"));

            $main_photo->update([
                "file_path" => $path,
                "url" => asset(Storage::url($path))
            ]);

            $main_photo->save();
            $game->main_photo = $main_photo->url;
        }
        

        $game->save();

        return response([
            "msg" => "game updated!",
            "game" => new GameShortResource($game)
        ]);
    }

    /**
    * @api [/games/delete/{@var game->id}] Удалить игру
    */

    public function destroy(Game $game) {

        $game->media()->delete();
        $game->delete();

        return response([
            "msg" => "game removed!"
        ]);
    }
}
