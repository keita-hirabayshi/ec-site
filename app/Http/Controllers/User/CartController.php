<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Cart;
use App\Models\Stock;
use App\Services\CartService;
use App\Jobs\SendThanksMail;
use App\Jobs\SendOrderedMail;

class CartController extends Controller
{
    public function index(){
        $user = User::findOrFail(Auth::id());
        $products = $user->products;
        $totalPrice = 0;
        foreach($products as $product){
            $totalPrice += $product->price * $product->pivot->quantity;
            // dd($product->pivot->quantity);
        }

        // dd($products,$totalPrice);
         return view('user.cart',compact('products','totalPrice'));
    }

    public function add(Request $request){
        $itemInCart = Cart::where('product_id',$request->product_id)
        ->where('user_id',Auth::id())->first();

        if($itemInCart){
            $itemInCart->quantity += $request->quantity;
            $itemInCart->save();
        }else{
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
            ]);
        }
        
        return redirect()->route('user.cart.index');
    }
    
    public function delete($id){
        Cart::where('product_id',$id)
        ->where('user_id',Auth::id())
        ->delete();
        
        return redirect()->route('user.cart.index');
    }
    
    public function checkout(){
        
    $user = User::findOrFail(Auth::id());
    $products = $user->products;
    $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));

    // ユーザーに紐づく商品を全取得
    // 購入点数と在庫数を比較
    $lineItems = [];
    foreach($products as $product){
        $quantity = '';
        $quantity = Stock::where('product_id',$product->id)->sum('quantity');

        if($product->pivot->quantity > $quantity){
            return redirect()->route('user.cart.index');
        }else{
            $lineItem = [
                "price_data" => [
                    'unit_amount' => $product->price,
                    'currency' => 'jpy',
                    "product_data" => [
                        'name' => $product->name,
                        'description' => $product->information,
                    ],
                ],
                'quantity' => $product->pivot->quantity
            ];
            array_push($lineItems,$lineItem);

        }
    }
    // stripe処理前に、在庫を差し押さえておく
        foreach($products as $product){
            Stock::create([
                'product_id' => $product->id,
                'type' => \Constant::PRODUCT_LIST['purchase'],
                'quantity' => $product->pivot->quantity * -1
            ]);
        }


        // strpeに合わせて、データを格納
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        $session = $stripe->checkout->sessions->create([
            'line_items' => [$lineItems],
            'mode' => 'payment',
            'success_url' => route('user.cart.success'),
            'cancel_url' => route('user.cart.cancel'),
        ]);
        $publicKey = env('STRIPE_PUBLIC_KEY');
 
        return view('user.checkout', compact('session', 'publicKey'));
    }
    
    public function success(){
    //  注文確認のメールとカート内のものを削除
        $items = Cart::where('user_id',Auth::id())->get();
        $products = CartService::getItemsInCart($items);
        $user = User::findOrFail(Auth::id());
        
        SendThanksMail::dispatch($products,$user);
        foreach ($products as $product) {
            SendOrderedMail::dispatch($product,$user);

        }
        // 
        Cart::where('user_id',Auth::id())->delete();
        
        return redirect()->route('user.items.index');
    }
    
    public function cancel(){
        $user = User::findOrFail(Auth::id());
        
        foreach($user->products as $product){
            Stock::create([
                'product_id' => $product->id,
                'type' => \Constant::PRODUCT_LIST['cancel'],
                'quantity' => $product->pivot->quantity
            ]);
        }
        return redirect()->route('user.cart.index');
    }
}
