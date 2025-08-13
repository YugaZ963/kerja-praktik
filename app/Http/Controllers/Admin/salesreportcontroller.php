<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class SalesReportController extends Controller
{
    public function index(Request $request)
    {
        // Get date filters as strings
        $startDateString = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDateString = $request->get('end_date', now()->format('Y-m-d'));
        
        // Convert to Carbon instances for view
        $startDate = Carbon::parse($startDateString);
        $endDate = Carbon::parse($endDateString);
        
        // Get summary data using string dates
        $summaryData = $this->getSummaryData($startDateString, $endDateString);
        
        // Get chart data
        $chartData = $this->getChartData($startDateString, $endDateString);
        
        // Get top products
        $topProducts = $this->getTopProducts($startDateString, $endDateString);
        
        // Get category sales data
        $categorySales = $this->getCategoryData($startDateString, $endDateString);
        
        // Get completed orders
        $completedOrders = $this->getCompletedOrders($startDateString, $endDateString);
        
        // Get daily sales data
        $dailySales = $this->getDailySalesData($startDateString, $endDateString);
        
        // Extract summary data for individual variables
        $totalRevenue = $summaryData['total_revenue'];
        $totalOrders = $summaryData['total_orders'];
        $totalItems = $summaryData['total_items'];
        $averageOrderValue = $summaryData['average_order_value'];
        $revenueGrowth = $summaryData['revenue_growth'];
        
        return view('admin.sales.index', compact(
            'summaryData',
            'chartData', 
            'topProducts',
            'categorySales',
            'completedOrders',
            'dailySales',
            'startDate',
            'endDate',
            'totalRevenue',
            'totalOrders',
            'totalItems',
            'averageOrderValue',
            'revenueGrowth'
        ));
    }
    
    public function exportPdf(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));
        
        $summaryData = $this->getSummaryData($startDate, $endDate);
        $topProducts = $this->getTopProducts($startDate, $endDate, 20);
        $categoryData = $this->getCategoryData($startDate, $endDate);
        
        $pdf = Pdf::loadView('admin.sales.pdf', compact(
            'summaryData',
            'topProducts',
            'categoryData',
            'startDate',
            'endDate'
        ));
        
        $filename = 'sales-report-' . $startDate . '-to-' . $endDate . '.pdf';
        
        return $pdf->download($filename);
    }
    
    public function getData(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));
        
        return response()->json([
            'summary' => $this->getSummaryData($startDate, $endDate),
            'chart' => $this->getChartData($startDate, $endDate),
            'topProducts' => $this->getTopProducts($startDate, $endDate)
        ]);
    }
    
    private function getSummaryData($startDate, $endDate)
    {
        $orders = Order::whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
                      ->whereIn('status', ['completed', 'delivered']);
        
        $totalRevenue = $orders->sum('total_amount');
        $totalOrders = $orders->count();
        $totalItems = OrderItem::whereHas('order', function($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
                  ->whereIn('status', ['completed', 'delivered']);
        })->sum('quantity');
        
        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;
        
        // Previous period comparison
        $periodDays = Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate)) + 1;
        $prevStartDate = Carbon::parse($startDate)->subDays($periodDays)->format('Y-m-d');
        $prevEndDate = Carbon::parse($startDate)->subDay()->format('Y-m-d');
        
        $prevRevenue = Order::whereBetween('created_at', [$prevStartDate, $prevEndDate . ' 23:59:59'])
                           ->whereIn('status', ['completed', 'delivered'])
                           ->sum('total_amount');
        
        $revenueGrowth = $prevRevenue > 0 ? (($totalRevenue - $prevRevenue) / $prevRevenue) * 100 : 0;
        
        return [
            'total_revenue' => $totalRevenue,
            'total_orders' => $totalOrders,
            'total_items' => $totalItems,
            'average_order_value' => $averageOrderValue,
            'revenue_growth' => $revenueGrowth
        ];
    }
    
    private function getChartData($startDate, $endDate)
    {
        $dailySales = Order::selectRaw('DATE(created_at) as date, SUM(total_amount) as revenue, COUNT(*) as orders')
                          ->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
                          ->whereIn('status', ['completed', 'delivered'])
                          ->groupBy('date')
                          ->orderBy('date')
                          ->get();
        
        return [
            'labels' => $dailySales->pluck('date')->map(function($date) {
                return Carbon::parse($date)->format('d M');
            }),
            'revenue' => $dailySales->pluck('revenue'),
            'orders' => $dailySales->pluck('orders')
        ];
    }
    
    private function getTopProducts($startDate, $endDate, $limit = 10)
    {
        return OrderItem::select('product_id', 'products.name', 'products.category')
                       ->selectRaw('SUM(order_items.quantity) as total_quantity')
                       ->selectRaw('SUM(order_items.quantity * order_items.price) as total_revenue')
                       ->join('products', 'order_items.product_id', '=', 'products.id')
                       ->whereHas('order', function($query) use ($startDate, $endDate) {
                           $query->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
                                 ->whereIn('status', ['completed', 'delivered']);
                       })
                       ->groupBy('product_id', 'products.name', 'products.category')
                       ->orderBy('total_quantity', 'desc')
                       ->limit($limit)
                       ->get();
    }
    
    private function getCategoryData($startDate, $endDate)
    {
        return OrderItem::select('products.category')
                       ->selectRaw('SUM(order_items.quantity) as total_quantity')
                       ->selectRaw('SUM(order_items.quantity * order_items.price) as total_revenue')
                       ->join('products', 'order_items.product_id', '=', 'products.id')
                       ->whereHas('order', function($query) use ($startDate, $endDate) {
                           $query->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
                                 ->whereIn('status', ['completed', 'delivered']);
                       })
                       ->groupBy('products.category')
                       ->orderBy('total_revenue', 'desc')
                       ->get();
    }
    
    private function getCompletedOrders($startDate, $endDate)
    {
        return Order::whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
                   ->whereIn('status', ['completed', 'delivered'])
                   ->with('items.product')
                   ->orderBy('created_at', 'desc')
                   ->get();
    }
    
    private function getDailySalesData($startDate, $endDate)
    {
        return Order::selectRaw('DATE(created_at) as date, SUM(total_amount) as daily_revenue, COUNT(*) as daily_orders')
                   ->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
                   ->whereIn('status', ['completed', 'delivered'])
                   ->groupBy('date')
                   ->orderBy('date')
                   ->get();
    }
}