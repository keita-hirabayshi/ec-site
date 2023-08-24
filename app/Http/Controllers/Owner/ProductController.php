<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Image;
use App\Models\Product;
use App\Models\SecondaryCategory;
use App\Models\Owner;


class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:owners');


        $this->middleware(function($request, $next){
            $id = $request->route()->parameter('product'); //imageのid取得
            if(!is_null($id)){ // null判定
                $productsOwnerId = Product::findOrFail($id)->shop->owner->id;
                $productId = (int)$productsOwnerId; // キャスト 文字列→数値に型変換
                if($productId !==  Auth::id()){ // 同じでなかったら
                  abort(404); // 404画面表示
                }

            }
            // dd($request->route()->parameter('image'));
            return $next($request);
            });
    }

    public function index()
    {
    // 下記の方法だと、表示する件数分SQLを繰り返し使う必要がある
    // $products = Owner::findOrFail(Auth::id())->shop->product;

        $ownerInfo = Owner::with('shop.product.imageFirst')
        ->where('id',Auth::id())->get();
        // foreach($ownerInfo as $owner){
        //     foreach($owner->shop->product as $product)
        //     dd($product->imageFirst->filename);
        // }
        return view('owner.products.index',compact('ownerInfo'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
