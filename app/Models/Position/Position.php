<?php

namespace App\Models\Position;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Position extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table = 'employee_position';
    protected $guarded = ['id'];

    public static function sqlSearchPosition($request){
        $data = Position::query();

        if($request->name) $data->where('name', 'LIKE', "%".$request->name."%");
        if($request->name_en) $data->where('name_en', 'LIKE', "%".$request->name_en."%");

        return $data->get();
    }

    public static function editPosition($request){

       $Category = Position::find($request->employee_id);

       if($request->name) $data['name'] = $request->name;
       if($request->name_en) $data['name_en'] = $request->name_en;

       $position = $Category->update($data);
       return $position;

    }

    public static function addPosition($request){

        if($request->name) $data['name'] = $request->name;
        if($request->name_en) $data['name_en'] = $request->name_en;

        $position = Position::create($data);
        return $position;

    }
}
