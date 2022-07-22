<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Http\Resources\Employee\EmployeeResource;
use App\Models\Employee\Employee;
use App\Models\Position\Position;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return response([

            'data' => EmployeeResource::collection(Employee::getAllEmployees($request)),
            'total_result' => Employee::getAllEmployees($request)->count(),
            'perPage' => intval($request->perPage) ? : 10,
            'page_number' => intval($request->page_number) ? : 1,
            'page_count' => ceil(Employee::all()->count() / ($request->perPage ? intval($request->perPage) : 10)),
            'position_data' => Position::all(),
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
            "date_of_birth" => "required",
            "email" => "required",
            "salary" => "required",
            "address" => "required",
            "age" => "required",
            "position_id" => "required",
        ]);

        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['password'] = Hash::make("123456");

        $status = User::where('email', $request->email)->first();
        if($status){
            return response([
                "message" => "email already exist !",
                "success" =>  false,
            ]);
        }

        try {
            $user =  User::create($data);
            $request->merge(['user_id' => $user->id]);
            $employee = Employee::addEmployee($request);
            return response([
                "data" => new EmployeeResource($employee),
                "message" => "create successfully",
                "success" => true,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Employee\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Employee::find($id);
        if($data){

            return response([
                "data" => new EmployeeResource($data),
                "message" => "Retrieved successfully",
                "success" => true,
            ], 200);
        }else{
            return response([
                "data" => null,
                "success" => false,
                "message" => "No data available !"
            ], 200);
        }
    }

    public function update(Request $request, $id)
    {
        $employee = Employee::find($id);
        if($employee){

            if($request->email){
                $check = User::where("email", $request->email)->where("id", "!=", $employee->user_id)->first();
                if($check){
                    return response([
                        "data" =>  null,
                        "success" => false,
                        "message" => "email already exist!"
                    ], 200);
                }
            }

            

            $employee_updated = Employee::editEmployee($request, $id);
            return response([
                "data" => new EmployeeResource($employee_updated),
                "message" => "Update successfully",
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
     * @param  \App\Models\Employee\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {   
        $data = Employee::find($id);
        if($data){
            $data->delete();
            User::where('id', $data->user_id)->delete();
            return response([
                "data" => EmployeeResource::collection(Employee::getAllEmployees($request)),
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
