<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\Customer\CustomerResource;
use App\Models\Customer\Customer;
use App\Models\Sell\Sell;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return response([
            'data' => CustomerResource::collection(Customer::getAllCustomer($request)),
            'total_result' => Customer::getAllCustomer($request)->count(),
            'perPage' => intval($request->perPage) ? : 10,
            'page_number' => intval($request->page_number) ? : 1,
            'page_count' => ceil(Customer::all()->count() / ($request->perPage ? intval($request->perPage) : 10)),
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
            "name" => "required",
            "gender" => "required",
            "date_of_birth" => "required | date_format:d-m-Y",
            "email" => "required",
            "address" => "required",
            "phone" => "required",
            "age" => "required",
        ]);

        $data['name'] = $request->name;
        $data['email'] = $request->email;
        // $data['role_id'] = 2;
        $data['password'] = Hash::make("123456");

        $status = User::where('email', $request->email)->first();
        if($status){
            return response([
                "message" => "email already exist !",
                "success" =>  false,
            ]);
        }

        $user =  User::create($data);
        $request->merge(['user_id' => $user->id]);
        $customer = Customer::addCustomer($request);

        return response([
            "data" => new CustomerResource($customer),
            "message" => "create successfully",
            "success" => true,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer\Customer  $employee
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Customer::find($id);
        if($data){

            return response([
                "data" => new CustomerResource($data),
                "message" => "Retrieved successfully",
                "success" => true,
            ], 200);
        }else{
            return response([
                "data" => null,
                "message" => "No data available !"
            ], 200);
        }
    }

    public function update(Request $request, $id){
        
        $customer = Customer::find($id);
        if($customer){

            if($request->email){
                $check = User::where("email", $request->email)->where("id", "!=", $customer->user_id)->first();
                if($check){
                    return response([
                        "data" =>  null,
                        "success" => false,
                        "message" => "Email already exist !"
                    ], 200);
                }
            }

            $cus_updated = Customer::editCustomer($request, $id);
            return response([
                "data" => new CustomerResource($cus_updated),
                "message" => "Update successfully",
                "success" => true
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
     * @param  \App\Models\Customer\Customer  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {   
        $data = Customer::find($id);
        if($data){

            User::where("id", $data->user_id)->delete();
            Sell::where('customer_id', $id)->delete();
            $data->delete();
            return response([
                "data" => CustomerResource::collection(Customer::getAllCustomer($request)),
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
}
