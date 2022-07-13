<?php

namespace App\Http\Resources\Employee;

use App\HELP as AppHELP;
use App\Models\HELP;
use App\Models\Position\Position;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class EmployeeResource extends JsonResource
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
        $data['user_id'] = $this->user_id;
        $data['name'] = $this->name;
        $data['email'] = $this->email;
        $data['phone'] = $this->phone;
        $data['gender'] = $this->gender;
        $data['date_of_birth'] = AppHELP::dateCoverter($this->date_of_birth);
        $data['age'] = $this->age;
        $data['age'] = $this->age;
        $data['salary'] = $this->salary;
        $data['address'] = $this->address;
        $data['description'] = $this->description;
        $data['position'] = $this->getPosition;
        $data['image'] = $this->image ? asset("/storage/images/" . $this->image) : null;
        
        return $data;
    }
}
