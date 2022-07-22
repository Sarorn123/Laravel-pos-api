<?php

namespace App\Models\Sell;

use App\HELP;
use App\Models\Customer\Customer;
use App\Models\Position\Position;
use App\Models\Product\Product;
use App\Models\User;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Sell extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table = 'sell';
    protected $guarded = ['id'];

    public function getCustomer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function getProduct()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public static function getAllSells($request)
    {

        $data = Sell::query();

        if ($request->search) {
            $data->where('name', 'LIKE', "%" . $request->search . "%");
        }
        if ($request->product_id) {
            $data->where('product_id', $request->product_id);
        }
        if ($request->customer_id) {
            $data->where('customer_id', $request->customer_id);
        }
        if (isset($request->status)) {
            $data->where('status', $request->status);
        }
        if ($request->start_date && $request->end_date) {
            $data->whereBetween('date', [HELP::dateCoverterToDB($request->start_date), HELP::dateCoverterToDB($request->end_date)]);
        }
        $data->orderBy('date', 'desc');

        return HELP::pagination($request, $data);
    }


    public static function addSell($request)
    {
        $product = Product::find($request->product_id);
        $usd = $product->usd  * $request->quantity;
        $khr = $product->khr  * $request->quantity;
        $date = HELP::dateCoverterToDB($request->date);
        $array = explode("-", $date);
        $month_year = $array[0]."-".$array[1];
        $year = $array[0];

        if($product->stock- $request->quantity == 0){
            $date_out_stock = Carbon::today();
        }else{
            $date_out_stock = null;
        }

        $request->merge(["usd" => $usd, "khr" => $khr, "date" => $date, "month_year" => $month_year, "year" => $year]);

        $data = $request->all();

        Log::error($date_out_stock);

        Product::where('id', $request->product_id)->update(
            [   
                "stock" => $product->stock - $request->quantity,
                "date_out_stock" => $date_out_stock
            ],
        );

        return self::create($data);
    }

    public static function updateSell($request, $id)
    {
        $sell = self::find($id);
        $product = Product::find($sell->product_id);
        if ($product) {
            if ($request->quantity) {

                $usd = $product->usd  * $request->quantity;
                $khr = $product->khr  * $request->quantity;
                $new_quantity = $sell->quantity + $product->stock - $request->quantity;

                if($new_quantity == 0){
                    $date_out_stock = Carbon::today();
                }else{
                    $date_out_stock = null;
                }

                Product::where('id', $sell->product_id)->update([
                    "stock" =>  $new_quantity, 
                    "date_out_stock" => $date_out_stock
                ]);
                $request->merge(["usd" => $usd, "khr" => $khr]);
            }
            if ($request->date) {
                $date = HELP::dateCoverterToDB($request->date);
                $request->merge(["date" => $date]);
            }
        }

        $data = $request->all();
        $sell->update($data);
        return $sell;
    }

    public static function getAnalyticeToday()
    {
        $sell_today = Sell::where('date', Carbon::today())->get();
        return $sell_today;
    }

    public static function getAnalyticeInWeek()
    {
        $current_week = Sell::whereBetween('date', [Carbon::today()->startOfWeek(), Carbon::today()->endOfWeek()])->get();
        $groups = $current_week->groupBy('date');
        $groups_array = $groups->all();

        $result = [];

        $days = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];
        $colors = ["red", "gold", "green", "pink", "purple", "blue", "yellow"];
        $index = 0;

        foreach($days as $day){

            $data = [
                "day" =>  $day,
                "money" => 0,
                "color" => "black",
            ];

            foreach($groups_array as $group){

                if(HELP::dateCoverterToDay($group[0]['date']) == $day){
                    $data = [
                        "day" =>  HELP::dateCoverterToDay($group[0]['date']),
                        "money" => $group->sum('usd'),
                        "color" => $colors[$index],
                    ];
                }
                
            }
            $result[] = $data;
            $index+=1;

        }
        return $result;
    }
}
