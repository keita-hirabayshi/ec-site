<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Shop;
use App\Models\SecondaryCategory;
use App\Models\Image1;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Support\Facades\DB;


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

    public function users(){
        return $this->belongsToMany(User::class,'carts')
        ->withPivot(['id', 'quantity']);
    }

    public function scopeAvailableItems($query){
        $stocks = DB::table('t_stocks')
        ->select('product_id',
        DB::raw('sum(quantity) as quantity'))
        ->groupBy('product_id')
        ->having('quantity', '>=', 1);

        return $query
        ->joinSub($stocks, 'stock', function($join){
            $join->on('products.id', '=', 'stock.product_id');
            })
            ->join('shops', 'products.shop_id', '=', 'shops.id')
            ->join('secondary_categories', 'products.secondary_category_id', '=','secondary_categories.id')
            ->join('images as image1', 'products.image1', '=', 'image1.id')
            ->where('shops.is_selling', true)
            ->where('products.is_selling', true)
            ->select('products.id as id', 'products.name as name', 'products.price'
            ,'products.sort_order as sort_order'
            ,'products.information', 'secondary_categories.name as category'
            ,'image1.filename as filename');
    }


    public function scopeSortOrder($query, $sortOrder){

        // 共通の内容はcommon.phpにて定数として定義し、リクエストの種類に応じて、データの取得順番を変更している
        if($sortOrder === null || $sortOrder === \Constant::SORT_ORDER['recommend']){
            return $query->orderBy('sort_order', 'asc') ;
            }
            if($sortOrder === \Constant::SORT_ORDER['higherPrice']){
            return $query->orderBy('price', 'desc') ;
            }
            if($sortOrder === \Constant::SORT_ORDER['lowerPrice']){
            return $query->orderBy('price', 'asc') ;
            }
            if($sortOrder === \Constant::SORT_ORDER['later']){
            return $query->orderBy('products.created_at', 'desc') ;
            }
            if($sortOrder === \Constant::SORT_ORDER['older']){
            return $query->orderBy('products.created_at', 'asc') ;
            }
    }

    public function scopeSelectCategory($query,$categoryId){
        if($categoryId !== '0'){
            return $query->where('secondary_category_id',$categoryId);
        }else{
            return;
        }
    }

}
