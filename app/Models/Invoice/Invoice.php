<?php

namespace App\Models\Invoice;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table = 'invoice';
    protected $guarded = ['id'];

    public static function sqlSearchInvoice($request){
        $data = Invoice::query();

        $start_date = date("Y-m-d", strtotime($request->start_date));
        $end_date = date("Y-m-d", strtotime($request->end_date));

        if($request->invoice_number) $data->where('invoice_number', $request->invoice_number);
        if($request->invoice_date) $data->where('invoice_date', $request->invoice_date);
        if($request->user_id) $data->where('user_id', $request->user_id);

        if($start_date && $end_date){
            $data->whereBetween('invoice_date', [$start_date ,$end_date]);
        }

        return $data->get();
    }

    public static function editInvoice($request){

        $invoice = Invoice::find($request->invoice_id);

        if($request->name) $data['invoice_number'] = $request->invoice_number;
        if($request->description) $data['description'] = $request->description;
        if($request->total_amount_usd) $data['total_amount_usd'] = $request->total_amount_usd;
        if($request->phone) $data['phone'] = $request->phone;
        if($request->user_id) $data['user_id'] = $request->user_id;
        $data['user_id'] = auth()->user()->id;
        if($request->invoice_date) $data['invoice_date'] = $request->invoice_date;

        $Invoice = $invoice->update($data);
        return $Invoice;

    }

    public static function addInvoice($request){

        if($request->invoice_number) $data['invoice_number'] = $request->invoice_number;
        if($request->description) $data['description'] = $request->description;
        if($request->total_amount_usd) $data['total_amount_usd'] = $request->total_amount_usd;
        if($request->user_id) $data['user_id'] = $request->user_id;
        $data['currency'] = "USD";
        $data['user_id'] = auth()->user()->id;
        $data['invoice_date'] = Carbon::now()->toDateTimeString();

        $Invoice = Invoice::create($data);
        return $Invoice;

    }

    
}
