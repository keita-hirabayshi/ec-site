<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Shop;
use App\Models\SecondaryCategory;
use App\Models\Image1;


class Product extends Model
{
    use HasFactory;

    public function category(){
    // メソッド名とFK名が異なる場合には、第二引数に対象となるカラム名を記載する
    // secondary_categoryだと省略可能
        return $this->belongsTo(SecondaryCategory::class,'secondary_category_id');
    }
    //メソッド名はカラム名と同一ではいけないのでずらす 
    // (FK先のモデル名,現ポジのカラム名,FK先のカラム名)
    public function imageFirst(){
        return $this->belongsTo(Image::class,'image1','id');
    }
  
    public function shop(){
        return $this->belongsTo(Shop::class);
    }
}
