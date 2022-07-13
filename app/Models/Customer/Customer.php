<?php

namespace App\Models\Customer;

use App\HELP;
use App\Models\Position\Position;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table = 'customers';
    protected $guarded = ['id'];

    public function getPosition(){
        return $this->belongsTo(Position::class, 'position_id');
    }

    public static function getAllCustomer($request){
        
        $data = Customer::query();

        if($request->search){
            $data->where('name', 'LIKE', "%".$request->search."%")
            ->orWhere('email', 'LIKE', "%".$request->search."%")
            ->orWhere('phone', 'LIKE', "%".$request->search."%")
            ->orWhere('age', 'LIKE', "%".$request->search."%")
            ->orWhere('address', 'LIKE', "%".$request->search."%");
        }

        return HELP::pagination($request, $data);
    }

    public static function editCustomer($request, $id){

        $customer = self::find($id);
        if($request->date_of_birth){
            $date = HELP::dateCoverterToDB($request->date_of_birth);
            $request->merge(["date_of_birth" => $date]);
        }
        $customer->update($request->all());
        return $customer;

    }

    public static function addCustomer($request){

        $date = HELP::dateCoverterToDB($request->date_of_birth);
        $request->merge(["date_of_birth" => $date]);

        $filename = HELP::storeImage($request);
        $customer = Customer::create($request->all());
        $customer->image = $filename;
        $customer->save();
        
        return $customer;

    }
}
