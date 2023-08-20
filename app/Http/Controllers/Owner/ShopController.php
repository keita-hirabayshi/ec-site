<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shop;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use InterventionImage;
use App\Http\Requests\UploadImageRequest;
use App\Services\ImageService;

class ShopController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:owners');

    // リクエストがあったshop->ownerとログイン時のowner情報が一致した場合のみ、店舗情報を公開する

        $this->middleware(function($request, $next){
            $id = $request->route()->parameter('shop'); //shopのid取得
            if(!is_null($id)){ // null判定
                $shopsOwnerId = Shop::findOrFail($id)->owner->id;
                $shopId = (int)$shopsOwnerId; // キャスト 文字列→数値に型変換
                $ownerId = Auth::id();
                if($shopId !== $ownerId){ // 同じでなかったら
                  abort(404); // 404画面表示
                }
            }
            return $next($request);
            });
    }

    public function index(){
        // $ownerId = Auth::id();
        $shops = Shop::where('owner_id', Auth::id())->get();

        return view('owner.shops.index',
        compact('shops'));
    }

    public function edit($id){
        $shop = Shop::findOrFail($id);
        // dd(Shop::findOrFail($id));
        return view('owner.shops.edit',compact('shop'));
    }

    public function update(UploadImageRequest $request){
        $imageFile = $request->image; //一時保存
        if(!is_null($imageFile) && $imageFile->isValid() ){
        // アップロード処理はコードの多いので、別フォルダに分けて読み込んでいる
        // 複数シーンで使用する場合は別ファイルに分けると、すっきりする(staticで記載すると、 :: だけで読み込める)
            $fileNameToStore = ImageService::upload($imageFile, 'shops');

        }

        return redirect()->route('owner.shops.index');
    }


}
