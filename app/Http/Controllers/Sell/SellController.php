<?php

namespace App\Http\Controllers\Sell;

use App\Http\Controllers\Controller;
use App\Http\Resources\Sell\SellResource;
use App\Models\Customer\Customer;
use App\Models\Product\Product;
use App\Models\Sell\Sell;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SellController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return response([
            'data' => SellResource::collection(Sell::getAllSells($request)),
            'total_result' => Sell::getAllSells($request)->count(),
            'perPage' => intval($request->perPage) ? : 10,
            'page_number' => intval($request->page_number) ? : 1,
            'page_count' => ceil(Sell::all()->count() / ($request->perPage ? intval($request->perPage) : 10)),
            'status' => "OK",
            'success' => true,
            'message' => "Retrieved successfully"
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            "product_id" => "required",
            "customer_id" => "required",
            "date" => "required",
            "quantity" => "required",
            "status" => "required",
        ]);

        $product = Product::find($request->product_id);
        if(!$product){
            return response([
                "data" => "",
                "message" => "Product Not Found !",
                "success" => false,
            ]);
        }

        if($product->stock < $request->quantity){
            return response([
                "data" => null,
                "message" => "Product Out Of Stock ! In stock is " . (string) $product->stock,
                "success" => false,
            ]);
        }

        $customer = Customer::find($request->customer_id);
        if(!$customer){
            return response([
                "data" => "",
                "message" => "Customer Not Found !",
                "success" => false,
            ]);
        }

        $sell = Sell::addSell($request);

        return response([
            "data" => new SellResource($sell),
            "message" => "Create Successfully",
            "success" => true,
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Sell\Sell  $employee
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Sell::find($id);
        if($data){

            return response([
                "data" => new SellResource($data),
                "message" => "Retrieved successfully"
            ], 200);
        }else{
            return response([
                "data" => null,
                "message" => "No data available !"
            ], 200);
        }
    }

    public function update(Request $request, $id){
        
        $sell = Sell::find($id);
        if($sell){

            $product = Product::find($request->product_id);

            if($product){
                if($request->quantity){
                    $old_quantity = $sell->quantity + $product->stock;
                    if($old_quantity < $request->quantity){
                        return response([
                            "data" => null,
                            "message" => "Out Of Stock ! In Stock Is " . $product->stock,
                            "success" => false,
                        ], 200);
                    }
                }
            }

            $sell_updated = Sell::updateSell($request, $id);
           
            return response([
                "data" => new SellResource($sell_updated),
                "message" => "Update Successfully",
                "success" => true,
            ], 200);
        }else{
            return response([
                "data" => null,
                "message" => "No data available !",
                "success" => false
            ], 200);
        }

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sell\Sell  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {   
        $data = Sell::find($id);
        if($data){
            $data->delete();
            return response([
                "data" => SellResource::collection(Sell::getAllSells($request)),
                "success" => true,
                "message" => "Delete Successfully"
            ], 200);
        }else{
            return response([
                "data" => null,
                "success" => false,
                "message" => "No data available!"
            ], 200);
        }
        
    }

    public function getSellFormData(){

        $all_product = Product::all();
        $all_customer = Customer::all();

        return response([
            'data' => [
                'product' => $all_product,
                'customer' => $all_customer,
            ],
            "success" => true,
        ], 200);
    }

    public function getAnalyticeToday(){

        return response([
            'data' => [
                'today' => Carbon::today()->format('l d-m-Y'),
                'sell_today' => SellResource::collection( Sell::getAnalyticeToday()),
                'chart_in_week_data' =>Sell::getAnalyticeInWeek(),
            ],
            "success" => true,
        ], 200);
    }

}
