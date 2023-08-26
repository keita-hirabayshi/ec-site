<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Image;
use App\Models\Product;
use App\Models\Shop;
use App\Models\PrimaryCategory;
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
        $shops = Shop::where('owner_id',Auth::id())
        ->select('id','name')
        ->get();

        $images = Image::where('owner_id',Auth::id())
        ->select('id','title','filename')
        ->orderBy('updated_at','desc')
        ->get();

    // secondarycategorの取得には、all()を使ってもいいが、「n + 1」問題を解決するために、ここでは Model のメソッド用にて取得している
        $categories = PrimaryCategory::with('secondary')
        ->get();

        return view('owner.products.create',
            compact('shops','images','categories'));
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
