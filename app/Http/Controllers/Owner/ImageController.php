<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UploadImageRequest;
use App\Services\ImageService;

class ImageController extends Controller
{
 
    public function __construct()
    {
        $this->middleware('auth:owners');


        $this->middleware(function($request, $next){
            $id = $request->route()->parameter('image'); //imageのid取得
            if(!is_null($id)){ // null判定
                $imageOwnerId = Image::findOrFail($id)->owner->id;
                $imageId = (int)$imageOwnerId; // キャスト 文字列→数値に型変換
                if($imageId !==  Auth::id()){ // 同じでなかったら
                  abort(404); // 404画面表示
                }

            }
            // dd($request->route()->parameter('image'));
            return $next($request);
            });
    }

    public function index()
    {
        $images = Image::where('owner_id', Auth::id())
        ->orderBy('updated_at','desc')
        ->paginate(20);

        return view('owner.images.index',
        compact('images'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('owner.images.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UploadImageRequest $request)
    {


        $imageFiles = $request->file('files');

        if(!is_null($imageFiles)){

            foreach($imageFiles as $imageFile){
                $fileNameToStore = ImageService::upload($imageFile,'products');
                Image::create([
                    'owner_id' => Auth::id(),
                    'filename' => $fileNameToStore
                ]);
            }
            
        }

        return redirect()
        ->route('owner.images.index')
        ->with(['message' => '画像登録を実施しました。',
        'status' => 'info']);
    }


    public function edit(string $id)
    {
        $image = Image::findOrFail($id);
        return view('owner.images.edit',compact('image'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        
        $request->validate([
            'title' => [ 'string', 'max:250']
        ]);

        $image = Image::findOrFail($id);
        $image->title = $request->title;

        $image->save();

        return redirect()
        ->route('owner.images.index')
        ->with(['message' => '画像情報を更新しました。',
        'status' => 'info']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // ストレージ内の画像削除
        $image = Image::findOrFail($id);
        $filePath = 'public/products/' . $image->filename;

        if(Storage::exists($filePath)){
            Storage::delete($filePath);
        }

        // DB内の削除
        Image::findOrFail($id)->delete(); 

        return redirect()
        ->route('owner.images.index')
        ->with(['message' => '画像を削除しました。',
        'status' => 'alert']);
    }
}
