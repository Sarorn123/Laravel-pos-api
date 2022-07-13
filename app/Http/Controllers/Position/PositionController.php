<?php

namespace App\Http\Controllers\Position;

use App\Http\Controllers\Controller;
use App\Models\Employee\Employee;
use App\Models\Position\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return response([
            'data' => Position::sqlSearchPosition($request),
            'message' => "Retrieved successfully"
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
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
                "data" => $data,
                "message" => "Retrieved successfully"
            ], 200);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Employee\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function addEditPosition(Request $request)
    {
        if($request->employee_id){
            Position::editPosition($request);
        }else{

            $request->validate([
                "name" => "required",
            ]);
            $data = Position::addPosition($request);
            return response([
                "data" => $data,
                "message" => "Retrieved successfully"
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Employee\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   $data = Employee::find($id);
        if($data){
            return response([
                "data" => $data,
                "message" => "Retrieved successfully"
            ], 200);
        }else{
            return response([
                "data" => null,
                "message" => "No data available!"
            ], 200);
        }
        
    }
}
