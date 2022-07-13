<?php

namespace App\Http\Controllers\Product;

use Illuminate\Http\Request;
use App\Models\Product\Product;
use App\Http\Controllers\Controller;
use App\Http\Resources\Product\ProductResource;
use App\Models\HELP;
use App\Models\Invoice\Invoice;
use App\Models\Sell\Sell;

class ProductController extends Controller
{
    public function getAllProducts(Request $request){
        return response([
            'status' => "OK",
            'message' => "Query Was Successfully",
            'data' => ProductResource::collection( Product::getAllProducts($request)),
            'total_result' => Product::getAllProducts($request)->count(),
            'perPage' => intval($request->perPage) ? : 10,
            'page_number' => intval($request->page_number) ? : 1,
            'page_count' => ceil(Product::all()->count() / ($request->perPage ? intval($request->perPage) : 10)),
        ], 200);
    }

    public function getProduct($id){
        $data = Product::find($id);
        if($data){
            return response([
                'data' => new ProductResource($data),
                'message' => 'Retreived successfully',
                'success' => true,
            ], 200);
        }else{
            return response([
                'data' => null,
                'message' => 'No data available !',
                'success' => false,
            ], 200);
        }
       
    }

    public function addEditProduct(Request $request){
        
        if($request->product_id){
            $data = Product::editProduct($request);
            return response([
                'data' => $data,
                'message' => 'Update successfully',
                'success' => true,
            ], 200);
        }else{

            $request->validate([
                "name" => "required",
                "category_id" => "required",
                "usd" => "required",
                "khr" => "required",
                "total_stock" => "required",
                "date_in_stock" => "required",
            ]);

            $data = Product::addProduct($request);
            return response([
                'data' => $data,
                'message' => 'Add Successfully',
                'success' => true,
            ], 200);
        }
       
    }

    public function deleteProduct(Request $request, $id ){
        
        $data = Product::find($id);
        if($data){
            Sell::where('product_id', $id)->delete();
            $data->delete();
            return response([
                'data' => ProductResource::collection(Product::getAllProducts($request)),
                'message' => 'Delete successfully',
                'success' => true,
            ], 200);
        }else{
            return response([
                'data' => null,
                'message' => 'No data available !'
            ], 200);
        }
       
    }

}
