<?php

namespace App\Models\Dashboard;

use App\HELP;
use App\Models\Employee\Employee;
use App\Models\Product\Product;
use App\Models\Sell\Sell;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class Dashboard extends Model
{
    use HasFactory;

    public static function getDashboardData()
    {

        $all_money = Sell::whereMonth('date', Carbon::now()->month)->where('status', 1)->get();
        $all_money_usd = $all_money->sum('usd');

        $money_buy_product = Product::whereMonth('date_in_stock', Carbon::now()->month)->get();

        $product_usd = 0;
        foreach ($money_buy_product as $single_product) {
            $stock_price_usd = $single_product->total_stock * $single_product->stock_usd;
            $product_usd += $stock_price_usd;
        }

        $employee_salary = Employee::query();
        $salary_usd = $employee_salary->sum('salary');

        $total_earning_usd = $all_money_usd - $product_usd - $salary_usd;

        $summary_money = [
            "total_earning_usd" => $total_earning_usd,
            "all_money_usd"  => $all_money_usd,
            "product_usd" => $product_usd,
            "salary_usd" => $salary_usd,
        ];

        $all_month_in_current_year = Sell::whereYear('date', Carbon::now()->year)->get();
        $groupby = $all_month_in_current_year->groupBy('month_year');
        $array_groupBy = $groupby->all();

        $months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

        $chart_month_in_year = [];

        foreach ($months as $month) {
            $defualt = [
                "month" => $month,
                "money" => 0,
            ];

            foreach ($array_groupBy as $each_month) {
                if (HELP::dateCoverterToMonth($each_month[0]['date']) == $month) {
                    $defualt = [
                        "month" => $month,
                        "money" => $each_month->sum('usd'),
                    ];
                }
            }
            $chart_month_in_year[] = $defualt;
        }

        // Year 

        $all = Sell::all();
        $groupby_year = $all->groupBy('year');
        $array_groupBy_year = $groupby_year->all();

        $years = ["2021", '2022', '2023', '2024', '2025'];
        $colors = ["blue", "green", "yellow", "pink", "purple"];
        $index = 0;

        $chart_year = [];

        foreach ($years as $year) {
            $defualt = [
                "year" => $year,
                "money" => 0,
                "color" => "black"
            ];

            foreach ($array_groupBy_year as $each_year) {
                if ($each_year[0]['year'] == $year) {
                    $defualt = [
                        "year" => $year,
                        "money" => $each_year->sum('usd'),
                        "color" => $colors[$index]
                    ];
                }
            }

            $chart_year[] = $defualt;
            $index += 1;
        }

        return [
            "summary_money" => $summary_money,
            "chart_month_in_year" =>  $chart_month_in_year,
            "chart_year" => $chart_year,
            "chart_in_week" => Sell::getAnalyticeInWeek(),
        ];
    }
}
