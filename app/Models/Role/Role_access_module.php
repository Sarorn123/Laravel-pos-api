<?php

namespace App\Models\Role;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role_access_module extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table = 'access_modules';
    protected $guarded = ['id'];
}
