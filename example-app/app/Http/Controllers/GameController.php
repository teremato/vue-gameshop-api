<?php

namespace App\Http\Controllers;

use App\Http\Requests\GameRequest;
use App\Http\Resources\GameResource;
use App\Http\Resources\GameShortResource;

use App\Models\Game;

use Illuminate\Http\Request;
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
            "main_photo" => $request->main_photo,
            "publisher_id" => 1,
            "slug" => Str::slug($request->title, '-')
        ]);

        $game->save();

        return response([
            "msg" => "game created!",
            "game" => $game
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
            "title", 
            "description", 
            "price" 
        ]);

        $game->update($fields,[
                "title" => $request->title ?: $game->title,
                "description" => $request->description ?: $game->description,
                "price" => $request->price ?: $game->price
            ]);
        
        $game->save();

        return response([
            "msg" => "game updated!!",
            "game" => $game
        ]);
    }

    /**
    * @api [/games/delete/{@var game->id}] Удалить игру
    */

    public function destroy(Game $game) {
        
        $game->delete();

        return response([
            "msg" => "game removed!"
        ]);
    }
}
