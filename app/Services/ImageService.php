<?php 

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use InterventionImage;


class ImageService{
    public static function upload($imageFile,$folderName){
        if(is_array($imageFile)){
            $file = $imageFile['image'];
        }else{
            $file = $imageFile;
        }

        $fileName = uniqid(rand().'_');         //ファイル名作成
        $extension = $file->extension();   //拡張子の取得
        $fileNameToStore = $fileName. '.' . $extension; //画像データ
        $resizedImage = InterventionImage::make($file)->resize(1920, 1080)->encode(); //リサイズ
        Storage::put('public/' . $folderName . '/' . $fileNameToStore,$resizedImage );

        return $fileNameToStore;
    }
}


?>