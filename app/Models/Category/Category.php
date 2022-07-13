<?php

namespace App\Models\Category;

use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table = 'category';
    protected $guarded = ['id'];

    public function getAllProducts(){
        return $this->hasMany(Product::class, 'category_id');
    }

    public static function sqlSearchCategory($request){
        $data = Category::query();
        if($request->name) $data->where('name', 'LIKE', "%".$request->name."%");
        return $data->get();
    }

    public static function editCategory($request){

       $Category = Category::find($request->Category_id);
        if($Category){
            if($request->name) $data['name'] = $request->name;
            if($request->description) $data['description'] = $request->description;

            $Category->update($data);
            return $Category;
        }

    }

    public static function addCategory($request){

        if($request->name) $data['name'] = $request->name;
        if($request->description) $data['description'] = $request->description;

        $Category = Category::create($data);
        return $Category;

    }
}
