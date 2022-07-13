<?php

namespace App\Http\Resources\Customer;

use App\HELP as AppHELP;
use App\Http\Resources\Sell\SellResource;
use App\Models\HELP;
use App\Models\Position\Position;
use App\Models\Sell\Sell;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $request->merge(["customer_id" => $this->id]);

        $data['id'] = $this->id;
        $data['user_id'] = $this->user_id;
        $data['name'] = $this->name;
        $data['email'] = $this->email;
        $data['phone'] = $this->phone;
        $data['gender'] = $this->gender;
        $data['date_of_birth'] = AppHELP::dateCoverter($this->date_of_birth);
        $data['age'] = $this->age;
        $data['image'] = $this->image ? asset("/storage/images/" . $this->image) : null;
        $data['address'] = $this->address;
        $data['position'] = $this->position;
        $data['company_name'] = $this->company_name;
        $data['company_address'] = $this->company_address;
        $data['skill'] = $this->skill;
        $data['history_buy'] = SellResource::collection(Sell::getAllSells($request));

        return $data;
    }
}
