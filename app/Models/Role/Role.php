<?php

namespace App\Models\Role;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table = 'roles';
    protected $guarded = ['id'];

    public static function roleAcessAdminData(){
        
        return self::getAccessModule();
     
    }

    public static function getAccessModule(){
        $entries = Role_access_module::where('parent', 0)->get();
        if($entries){
            $result = [];
            foreach($entries as $value){
                $result[] = [
                    "id" => $value->id,
                    "name" => $value->name,
                    "child" => self::getChild($value->id),
                ];
            }

            return $result;
        }
    }

    public static function getChild($parent){
        return Role_access_module::where('parent' , $parent)->get();
    }


    
}
