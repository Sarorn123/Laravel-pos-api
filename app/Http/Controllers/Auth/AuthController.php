<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Role\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Employee\Employee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            "name" => "required",
            "email" => "required|email",
            "password" => "required",
        ]);
        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['password'] = Hash::make($request->password);

        if(User::where('email' , $request->email)->first()){
            return response([
                "message" => "Email Already Exist !",
                "success" => false,
            ]);
        }

        $user = User::create($data);
        Employee::create(["user_id" => $user->id, "name" => $request->name, "email" => $request->email]);
        return response([
            "data" => "resgister successfully",
            "status" => "please login !",
        ]);
    }

    public function login(Request $request)
    {
        $loginData = (object) $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $user = User::where('email', $loginData->email)->first();
        if (!$user || !Hash::check($loginData->password, $user->password)) {
            return response([
                'message' => "Invalid login !",
                'success' => false,
            ]);
        }
        if ($user) {

            $accessToken = $user->createToken('authToken')->plainTextToken;
            $employee = Employee::where('user_id', $user->id)->first();

            if ($user->role_id == 1) {

                $data['user'] = $employee;
                $data['role'] = Role::find($user->role_id);
                // $data['admin_menu'] = Role::roleAcessAdminData();
                $data['token'] = $accessToken;
            }
        }

        return response([
            "message" => "login_successfully",
            "status" => "OK",
            'success' => true,
            "data" => $data
        ], 200);
    }

    public function getAllusers()
    {
        $users = User::all();
        if ($users) {
            $result = [];
            foreach ($users as $user) {
                $data['id'] = $user->id;
                $data['name'] = $user->name;
                $data['email'] = $user->email;
                $result[] = $data;
            }
            return response([
                'data' => $result,
                'message' => 'Retrieved successfully',
            ]);
        }
    }

    public function AddRole(Request $request){

        $request->validate([
            "name" => 'required',
        ]);

        Role::create($request->all());
        return response([
            "message" => "Role Added",
        ]);

    }

    public function all_movies()
    {
        $response = Http::get("https://yts.torrentbay.to/api/v2/list_movies.json");
        return response(["data" => json_decode($response->body())]);
    }
}
