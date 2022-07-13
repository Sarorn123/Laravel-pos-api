<?php

namespace App\Models\Employee;

use App\HELP;
use App\Models\Position\Position;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\URL;

class Employee extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table = 'employees';
    protected $guarded = ['id'];

    public function getPosition(){
        return $this->belongsTo(Position::class, 'position_id');
    }

    public static function getAllEmployees($request){
        
        $data = Employee::query();

        if($request->search){
            $data->where('name', 'LIKE', "%".$request->search."%")
            ->orWhere('email', 'LIKE', "%".$request->search."%")
            ->orWhere('salary', 'LIKE', "%".$request->search."%");
        }

        return HELP::pagination($request, $data);
    }

    public static function editEmployee($request, $id){

        $employee = Employee::find($id);
        if($request->date_of_birth){
            $date = HELP::dateCoverterToDB($request->date_of_birth);
            $request->merge(["date_of_birth" => $date]);
        }
        $employee->update($request->all());
        return $employee;
    }

    public static function addEmployee($request){

        $date_of_birth = HELP::dateCoverterToDB($request->date_of_birth);
        $request->merge([
            "date_of_birth" => $date_of_birth,
        ]);
        $employee = Employee::create($request->all());
        $filename = HELP::storeImage($request);
        $employee->image = $filename;
        $employee->save();
        
        return $employee;
    }
}
