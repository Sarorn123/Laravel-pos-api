<?php

namespace App\Http\Resources\Product;

use App\HELP;
use App\Http\Resources\Category\CategoryResource;
use Illuminate\Http\Resources\Json\JsonResource;


class ProductResource extends JsonResource
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
        $data['name'] = $this->name;
        $data['usd'] = $this->usd;
        $data['khr'] = $this->khr;
        $data['stock'] = $this->stock;
        $data['total_stock'] = $this->total_stock;
        $data['stock_usd'] = $this->stock_usd;
        $data['date_in_stock'] = HELP::dateCoverter($this->date_in_stock);
        $data['date_out_stock'] = HELP::dateCoverter($this->date_out_stock);
        $data['description'] = $this->description;
        $data['category'] = $this->getCategory;

        return $data;
    }
}
