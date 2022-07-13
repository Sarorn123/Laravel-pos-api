<?php

namespace App\Http\Resources\Sell;

use App\HELP as AppHELP;
use App\Models\HELP;
use App\Models\Position\Position;
use Illuminate\Http\Resources\Json\JsonResource;

class SellResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data['id'] = $this->id;
        $data['product_id'] = $this->product_id;
        $data['product_name'] = $this->getProduct ? $this->getProduct->name: "";
        $data['customer_id'] = $this->customer_id;
        $data['customer_name'] = $this->getCustomer ? $this->getCustomer->name: "";
        $data['customer_address'] = $this->getCustomer ? $this->getCustomer->address: "";
        $data['date'] = AppHELP::dateCoverter($this->date);
        $data['quantity'] = $this->quantity;
        $data['status'] = $this->status == 1 ? "Paid" : "Unpaid";
        $data['usd'] = $this->usd;
        $data['khr'] = $this->khr;
        $data['description'] = $this->description;

        return $data;
    }
}
