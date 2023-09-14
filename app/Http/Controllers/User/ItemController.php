<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:users');

        $this->middleware(function($request, $next){
            $id = $request->route()->parameter('item'); //itemのid取得
        // 取得されてくる商品のIDは is_sellingが 1 のものだけなので
            if(!is_null($id)){ // null判定
                $itemId = Product::availableItems()->where('products.id',$id)->exists();
                if(!$itemId){ // 同じでなかったら
                  abort(404); // 404画面表示
                }
            }
            // dd($request->route()->parameter('image'));
            return$next($request);
        });
    }


    public function index(Request $request){
        $products = Product::availableItems()
        ->sortOrder($request->sort)
        ->get();


        return view('user.index',compact('products'));
    }

    public function show($id){
    // 今回はクエリービルダではなくエロクアンとでデータを取得(eloquentメソッドを使用できる)
        $product = Product::findOrFail($id);
        $quantity = Stock::where('product_id', $product->id)->sum('quantity');
        if($quantity > 9){
            $quantity = 9;
        } 

        return view('user.show',compact('product','quantity'));
    }
}
