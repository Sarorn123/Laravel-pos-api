<?php

namespace App\Http\Controllers\Category;

use Illuminate\Http\Request;
use App\Models\Category\Category;
use App\Http\Controllers\Controller;
use App\Http\Resources\Category\CategoryResource;
use App\Models\Product\Product;

class CategoryController extends Controller
{
    public function getAllCategorys(Request $request){
        return response([
            'data' => Category::sqlSearchCategory($request),
            'message' => 'Retreived successfully'
        ], 200);
    }

    public function getCategory($id){
        $data = Category::find($id);
        if($data){
            return response([
                'data' => new CategoryResource($data),
                'message' => 'Retreived successfully'
            ], 200);
        }else{
            return response([
                'data' => null,
                'message' => 'No data available !'
            ], 200);
        }
       
    }

    public function addEditCategory(Request $request){
        
        if($request->Category_id){
            $data = Category::editCategory($request);
            return response([
                'data' => $data,
                'message' => 'Retreived successfully'
            ], 200);
        }else{

            $request->validate([
                "name" => "required",
                
            ]);

            $data = Category::addCategory($request);
            return response([
                'data' => $data,
                'message' => 'Retreived successfully'
            ], 200);
        }
       
    }

    public function deleteCategory($id , Request $request){
        
        $data = Category::find($id);
        if($data){
            Product::deleteAllProductsByCategoryId($id);
            $data->delete();
            return response([
                'data' => Category::sqlSearchCategory($request),
                'message' => 'Delete successfully'
            ], 200);
        }else{
            return response([
                'data' => null,
                'message' => 'No data available !'
            ], 200);
        }
       
    }
}
