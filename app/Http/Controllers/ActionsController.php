<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\Basket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActionsController extends Controller
{
    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }

    public function login(Request $request)
    {
        $request->validate([
            'user.email'=> 'required|email',
            'user.password'=> 'required|min:8|alpha_dash',
        ], [
            'user.email.reqired' => 'Поле "Электронная почта" обязательно для заполнения',
            'user.email.email'=> 'Поле "Электронная почта" должно быть предоставлено в виде валидного адреса электронной почты',
            'user.password.required'=> 'Поле "Пароль" обязательно для заполнения',
            'user.password.min'=> 'Поле "Пароль" должно быть не менее, чем 8 символов',
            'user.password.alpha_dash'=> 'Поле "Пароль" должно содержать только строчные и прописные символы латиницы, цифры, а также символы "-" и "_"',
        ]);
        if(Auth::attempt($request -> input('user'))) {
            return redirect('/');
        } else {
            return back() -> withErrors([
                'user.email' => 'Предоставленная почта или пароль не подходят'
            ]);
        }
    }

    public function addToCart(Request $request, Product $product)
    {
        $basket = Basket::where('user_id', $request->user()->id)
                        ->where('product_id', $product->id)
                        ->first();

        if ($basket) {
            if($product->count < $basket->count + 1) {
                return redirect()->back()->with('error', 'Превышено количество товара на складе');
            }
            $basket->count += 1;
            $basket->save();
        } else {
            Basket::create([
                'user_id' => $request->user()->id,
                'product_id' => $product->id,
                'count' => 1,
            ]);
        }

        return redirect()->back()->with('success', 'Продукт добавлен в корзину');
    }

    public function cart_decrease(Request $request, Product $product)
    {
        $basket = Basket::where('user_id', $request->user()->id)
                        ->where('product_id', $product->id)
                        ->first();

        if ($basket) {
            if ($basket->count > 1) {
                $basket->count -= 1;
                $basket->save();
                return redirect()->back()->with('success', 'Количество продукта изменено');
            } else {
                $basket->delete();
                return redirect()->back()->with('success', 'Продукт удален из корзины');
            }
        }
    }

    public function cart_increase(Request $request, Product $product)
    {
        $basket = Basket::where('user_id', $request->user()->id)
                        ->where('product_id', $product->id)
                        ->first();

        if ($basket) {
            if($product->count < $basket->count + 1) {
                return redirect()->back()->with('error', 'Превышено количество товара на складе');
            }
            $basket->count += 1;
            $basket->save();
            return redirect()->back()->with('success', 'Количество продукта изменено');
        }
    }

    public function cart_checkout(Request $request)
    {
        $basket = Basket::where('user_id', $request->user()->id)->get();
        $total = 0;
        foreach ($basket as $item) {
            $total += $item->product->price * $item->count;
            $item->delete();
        }

        $receipt = new Order;
        $receipt->user_id = $request->user()->id;
        $receipt->status = 'paid';
        $receipt->save();

        foreach ($basket as $item) {
            $item->product->count -= $item->count;
            $item->product->save();
            $receipt->products()->attach($item->product_id, ['count' => $item->count]);
            $item->delete();
        }

        return redirect()->back()->with('success', 'Оплата прошла успешно. Сумма: ' . $total);
    }
}
