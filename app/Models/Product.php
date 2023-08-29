<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Shop;
use App\Models\SecondaryCategory;
use App\Models\Image1;
use App\Models\Stock;


class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'information',	
        'price',
        'is_selling',
        'sort_order',
        'shop_id',
        'secondary_category_id',
        'image1',
        'image2',	
        'image3',	
        'image4',
    ];

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
    public function imageSecond(){
        return $this->belongsTo(Image::class,'image2','id');
    }
    public function imageThird(){
        return $this->belongsTo(Image::class,'image3','id');
    }
    public function imageFourth(){
        return $this->belongsTo(Image::class,'image4','id');
    }
  
    public function shop(){
        return $this->belongsTo(Shop::class);
    }
    public function stock(){
        return $this->hasMany(Stock::class);
    }

}
