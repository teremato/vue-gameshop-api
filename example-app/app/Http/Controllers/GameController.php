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
     * @api POST [/games/] Получить игры 
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
    * @param App\Models\Game $game
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

        /* Создаем игру */
        $game = Game::create([
            "title" => $request->title,
            "description" => $request->description,
            "price" => $request->price,
            "publisher_id" => 1,
            "slug" => Str::slug($request->title, '-')
        ]);

        /* Добавляем фото и получаем его путь */
        $path = Storage::disk("public")
            ->put("/games", $request->file("main_photo"));

        /* Создаем запись в бд о фото */
        $main_photo = Media::create([
            "game_id" => $game->id,
            "media_type" => Media::TYPE_GAME_MAIN_PHOTO,
            "file_path" => $path,
            "url" => asset(Storage::url($path)),
        ]);

        /* Добавляем к игре поле с ссылкой на фото */
        $game->main_photo = $main_photo["url"];

        /* Сохраняем */
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

        /* Определяем поля для реквеста */
        $fields = $request->only([
            "title", "description", 
            "price", "main_photo"
        ]);

        /* Переписываем данные игры */
        $game->update($fields,[
            "title" => $request->title ?: $game->title,
            "description" => $request->description ?: $game->description,
            "price" => $request->price ?: $game->price
        ]);

        /* Проверяем на наличие фото */
        if($request->hasFile("main_photo")) {

            /* Получаем старое фото из бд */
            $main_photo = $game->media()
                ->where("media_type", Media::TYPE_GAME_MAIN_PHOTO)
                ->first();

            /* Удаляем старое фото */
            Storage::disk("public")
                ->delete($main_photo->file_path);

            /* Добавляем фото и получаем его путь */
            $path = Storage::disk("public")
                ->put("/games", $request->file("main_photo"));

            /* Обновляем запись в бд */
            $main_photo->update([
                "file_path" => $path,
                "url" => asset(Storage::url($path))
            ]);

            /* Сохраняем фото */
            $main_photo->save();

            /* Добавляем поле с ссылкой на фото */
            $game->main_photo = $main_photo->url;
        }
        
        /* Сохраняем игру в бд */
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

        /* Удаляем медиа игры */
        $game->media()->delete();

        /* Удаляем игру */
        $game->delete();

        return response([
            "msg" => "game removed!"
        ]);
    }
}
