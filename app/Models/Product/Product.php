<?php

namespace App\Models\Product;

use App\HELP;
use App\Models\Category\Category;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Product extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table = 'product';
    protected $guarded = ['id'];


    public function getCategory(){
        return $this->belongsTo(Category::class, 'category_id');
    }

    public static function getAllProducts($request){

        $query = Product::query();

        if($request->search){
            $query->where("name", 'LIKE' , '%' . $request->search . '%')
            ->orWhere("usd", $request->search)
            ->orWhere("khr", $request->search)
            ->orWhere("total_stock", $request->search)
            ->orWhere("date_in_stock", $request->search);
        }
        if($request->date_in_stock){
            $query->where('date_in_stock', HELP::dateCoverterToDB($request->date_in_stock));
        }

        if($request->perPage){
            if($request->page_number) {
                $offset = ($request->page_number-1) * $request->perPage;
                $query->offset($offset)->limit($request->perPage);
            }else{
                $query->limit($request->perPage);
            }
        }else{
            if($request->page_number) {
                $offset = ($request->page_number-1) * 10;
                $query->offset($offset)->limit(10);
            }else{
                $query->limit(10);
            }
        }
        
        return $query->get();
    }

    public static function editProduct($request){

       $product = Product::find($request->product_id);
        if($product){

            if($request->stock == 0){
                $date_out_stock = Carbon::today();
            }else{
                $date_out_stock = null;
            }

            $date = HELP::dateCoverterToDB($request->date_in_stock);
            $request->merge(["date_in_stock" => $date, "date_out_stock" => $date_out_stock]);
            $data = $request->all();
            $product->update($data);
            return Product::find($request->product_id);
        }

    }

    public static function addProduct($request){

        $date = HELP::dateCoverterToDB($request->date_in_stock);
        $request->merge(["date_in_stock" => $date]);
        $data = $request->all();
        $product = Product::create($data);
        return Product::find($product->id);

    }

    public static function deleteAllProductsByCategoryId($id){
        $entries = Product::where('category_id', $id)->get(); 
        if($entries){
            foreach($entries as $value){
                $value->delete();
            }
        }
    }

    public static function actionSellProduct($request){

        $data = explode(",", $request->product_ids);
        $price_result = [];
        foreach($data as $value){
            $product = Product::find($value);
            $price_result[] = [
                "id" => $product->id,
                "name" => $product->name,
                "price" => $product->price,
            ];
        }

        return $price_result;
        // return round(array_sum($price_result),3);
    }
}
