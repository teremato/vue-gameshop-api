<?php

namespace App\Http\Controllers;

use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\Game;
use Illuminate\Http\Request;

class CartController extends Controller
{

    public function getUserCart(Request $request) {

        $user = $request->user();
        $cart = $user->cart()->get();

        return CartResource::collection($cart);
    }

    public function addToCart(Request $request, Game $game) {

        $user = $request->user();
        $isCart = $user->cart()
            ->where("game_id", $game->id)
            ->count();

        if($isCart) {

            $item = $user->cart()->where("game_id", $game->id)
                ->first();
            $item->update([
                "count" => $item->count + 1
            ]);
        }
        else {

            Cart::create([
                "user_id" => $user->id,
                "game_id" => $game->id
            ]);
        }
    
        return response([
            "message" => "Игра добавлена в корзину!"
        ], 201);
    }

    public function removeToCart(Request $request, Cart $cart) {

        $cart_item = Cart::where('id', $cart->id)
            ->first();
        $cart_item->delete();

        return response([
            "message" => "Игра удалена из корзины!"
        ], 201);
    }

    public function decrementCount(Request $request, Cart $cart) {

        $cart->update([ "count" => $cart->count - 1 ]);
        $cart->save();

        return response([
            "message" => "Игра удалена из корзины"
        ], 201);
    }

    public function doOrder(Request $request) {

        $user = $request->user();
        $cart_items = $user->cart()->get();

        foreach($cart_items as $item) {
            $item->delete();
        }

        return response([
            "message" => "Спасибо за покупку!"
        ]);
    }
}
