<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Dashboard\Dashboard;
use App\Models\Employee\Employee;
use App\Models\Product\Product;
use App\Models\Sell\Sell;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function getDashboardData(Request $request)
    {
        return response([
            "data" => Dashboard::getDashboardData(),
            "success" => true,
        ], 200);

    }
    
}
