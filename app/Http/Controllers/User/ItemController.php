<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Stock;
use App\Models\PrimaryCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;

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
        // dd($request);
        Mail::to('test@example.com')
        ->send(new TestMail());

        $categories = PrimaryCategory::with('secondary')
        ->get();

        $products = Product::availableItems()
        ->selectCategory($request->category ?? '0')
        ->searchKeyword($request->keyword)
        ->sortOrder($request->sort)
        ->paginate($request->pagination ?? 20);

        return view('user.index',
        compact('products','categories'));
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
