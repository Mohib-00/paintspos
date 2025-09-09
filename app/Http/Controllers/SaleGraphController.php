<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SaleGraphController extends Controller
{
 public function salegraph(){ 
    $user = Auth::user();      
    $currentYear = date('Y');
    $currentMonth = date('m');
    $today = date('Y-m-d');

    $sales = Sale::select(
        DB::raw('MONTH(created_at) as month'),
        DB::raw('SUM(subtotal) as subtotal_sales')
    )
    ->whereYear('created_at', $currentYear)
    ->groupBy('month')
    ->orderBy('month')
    ->get();

    $months = [];
    $totals = [];
    foreach($sales as $sale) {
        $months[] = date('F', mktime(0, 0, 0, $sale->month, 10));
        $totals[] = $sale->subtotal_sales;
    }

    $currentMonthTotal = Sale::whereYear('created_at', $currentYear)
        ->whereMonth('created_at', $currentMonth)
        ->sum('subtotal');

    $startOfWeek = Carbon::now()->startOfWeek(); 
    $endOfWeek = Carbon::now()->endOfWeek();     

    $dailySales = Sale::select(
        DB::raw('DAYNAME(created_at) as day'),
        DB::raw('SUM(subtotal) as subtotal_sales'),
        DB::raw('DAYOFWEEK(created_at) as day_number')
    )
    ->whereDate('created_at', '>=', $startOfWeek)
    ->whereDate('created_at', '<=', $endOfWeek)
    ->groupBy(DB::raw('DAYNAME(created_at)'), DB::raw('DAYOFWEEK(created_at)'))
    ->orderBy('day_number')
    ->get();

    $days = [];
    $dailyTotals = [];
    foreach($dailySales as $sale) {
        $days[] = $sale->day;
        $dailyTotals[] = $sale->subtotal_sales;
    }

    $todaySales = Sale::whereDate('created_at', $today)->sum('subtotal');


    $userSales = Sale::select(
    'user_id',
    DB::raw('SUM(subtotal) as subtotal_sales')
)
->groupBy('user_id')
->get();

$userLabels = [];
$userTotals = [];
foreach($userSales as $sale) {
    $user = \App\Models\User::find($sale->user_id);
    $userLabels[] = $user ? $user->name : 'Unknown';
    $userTotals[] = $sale->subtotal_sales;
}


  $daysOfMonth = range(1, date('d'));

    $monthDailySales = Sale::select(
        DB::raw('DAY(created_at) as day'),
        DB::raw('SUM(subtotal) as subtotal_sales')
    )
    ->whereYear('created_at', $currentYear)
    ->whereMonth('created_at', $currentMonth)
    ->whereDay('created_at', '<=', date('d'))
    ->groupBy(DB::raw('DAY(created_at)'))
    ->orderBy('day')
    ->get();

    $monthDailyLabels = [];
    $monthDailyTotals = [];

    foreach($monthDailySales as $sale) {
        $monthDailyLabels[] = $sale->day;
        $monthDailyTotals[] = $sale->subtotal_sales;
    }

    return view('adminpages.salegraph', [
        'userName' => $user->name,
        'userEmail' => $user->email,
        'months' => $months,
        'totals' => $totals,
        'currentMonthTotal' => $currentMonthTotal,
        'days' => $days,
        'dailyTotals' => $dailyTotals,
        'todaySales' => $todaySales,
        'userLabels' => $userLabels,
        'userTotals' => $userTotals,
        'monthDailyLabels' => $monthDailyLabels,
        'monthDailyTotals' => $monthDailyTotals
    ]);
}


}
