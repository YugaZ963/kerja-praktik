<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class SalesReportController extends Controller
{
    /**
     * Display sales report dashboard
     */
    public function index(Request $request)
    {
        // Default date range (last 30 days)
        $startDate = $request->get('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
        
        // Convert to Carbon instances for database queries
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();
        
        // Summary statistics
        $totalRevenue = Order::whereBetween('created_at', [$start, $end])
            ->whereIn('status', ['completed', 'delivered'])
            ->sum('total_amount');
            
        $totalOrders = Order::whereBetween('created_at', [$start, $end])
            ->whereIn('status', ['completed', 'delivered'])
            ->count();
            
        $averageOrder = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;
        
        $totalProductsSold = OrderItem::whereHas('order', function($query) use ($start, $end) {
            $query->whereBetween('created_at', [$start, $end])
                  ->whereIn('status', ['completed', 'delivered']);
        })->sum('quantity');
        
        // Daily sales trend (last 7 days for chart)
        $dailySales = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as revenue'),
                DB::raw('COUNT(*) as orders')
            )
            ->whereBetween('created_at', [Carbon::now()->subDays(6)->startOfDay(), Carbon::now()->endOfDay()])
            ->whereIn('status', ['completed', 'delivered'])
            ->groupBy('date')
            ->orderBy('date')
            ->get();
            
        // Top selling products
        $topProducts = OrderItem::select(
                'products.name',
                'products.category',
                DB::raw('SUM(order_items.quantity) as total_sold'),
                DB::raw('SUM(order_items.quantity * order_items.price) as total_revenue')
            )
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->whereHas('order', function($query) use ($start, $end) {
                $query->whereBetween('created_at', [$start, $end])
                      ->whereIn('status', ['completed', 'delivered']);
            })
            ->groupBy('products.id', 'products.name', 'products.category')
            ->orderBy('total_sold', 'desc')
            ->limit(10)
            ->get();
            
        // Sales by category
        $salesByCategory = OrderItem::select(
                'products.category',
                DB::raw('SUM(order_items.quantity) as total_sold'),
                DB::raw('SUM(order_items.quantity * order_items.price) as total_revenue')
            )
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->whereHas('order', function($query) use ($start, $end) {
                $query->whereBetween('created_at', [$start, $end])
                      ->whereIn('status', ['completed', 'delivered']);
            })
            ->groupBy('products.category')
            ->orderBy('total_revenue', 'desc')
            ->get();
            
        // Recent completed orders
        $recentOrders = Order::with(['user', 'items.product'])
            ->whereBetween('created_at', [$start, $end])
            ->whereIn('status', ['completed', 'delivered'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        return view('admin.sales.index', [
            'titleShop' => '📊 Laporan Penjualan - Admin RAVAZKA | Analisis Bisnis',
            'title' => '📊 Laporan Penjualan - Admin RAVAZKA | Analisis Bisnis',
            'metaDescription' => '📈 Dashboard laporan penjualan lengkap RAVAZKA. Analisis revenue, tren penjualan, produk terlaris, dan performa bisnis dengan grafik interaktif.',
            'metaKeywords' => 'laporan penjualan RAVAZKA, analisis bisnis, revenue report, sales dashboard, tren penjualan',
            'totalRevenue' => $totalRevenue,
            'totalOrders' => $totalOrders,
            'averageOrder' => $averageOrder,
            'totalProductsSold' => $totalProductsSold,
            'dailySales' => $dailySales,
            'topProducts' => $topProducts,
            'salesByCategory' => $salesByCategory,
            'recentOrders' => $recentOrders,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
    }
    
    /**
     * Get sales data for AJAX requests
     */
    public function getData(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
        
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();
        
        // Get daily sales for the specified period
        $dailySales = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as revenue'),
                DB::raw('COUNT(*) as orders')
            )
            ->whereBetween('created_at', [$start, $end])
            ->whereIn('status', ['completed', 'delivered'])
            ->groupBy('date')
            ->orderBy('date')
            ->get();
            
        return response()->json([
            'dailySales' => $dailySales,
            'period' => [
                'start' => $startDate,
                'end' => $endDate
            ]
        ]);
    }
    
    /**
     * Export sales report to PDF
     */
    public function exportPdf(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
        
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();
        
        // Get all data for PDF
        $totalRevenue = Order::whereBetween('created_at', [$start, $end])
            ->whereIn('status', ['completed', 'delivered'])
            ->sum('total_amount');
            
        $totalOrders = Order::whereBetween('created_at', [$start, $end])
            ->whereIn('status', ['completed', 'delivered'])
            ->count();
            
        $topProducts = OrderItem::select(
                'products.name',
                'products.category',
                DB::raw('SUM(order_items.quantity) as total_sold'),
                DB::raw('SUM(order_items.quantity * order_items.price) as total_revenue')
            )
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->whereHas('order', function($query) use ($start, $end) {
                $query->whereBetween('created_at', [$start, $end])
                      ->whereIn('status', ['completed', 'delivered']);
            })
            ->groupBy('products.id', 'products.name', 'products.category')
            ->orderBy('total_sold', 'desc')
            ->get();
            
        $salesByCategory = OrderItem::select(
                'products.category',
                DB::raw('SUM(order_items.quantity) as total_sold'),
                DB::raw('SUM(order_items.quantity * order_items.price) as total_revenue')
            )
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->whereHas('order', function($query) use ($start, $end) {
                $query->whereBetween('created_at', [$start, $end])
                      ->whereIn('status', ['completed', 'delivered']);
            })
            ->groupBy('products.category')
            ->orderBy('total_revenue', 'desc')
            ->get();
        
        $data = [
            'totalRevenue' => $totalRevenue,
            'totalOrders' => $totalOrders,
            'topProducts' => $topProducts,
            'salesByCategory' => $salesByCategory,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'generatedAt' => Carbon::now()->format('d/m/Y H:i:s')
        ];
        
        $pdf = Pdf::loadView('admin.sales.pdf', $data);
        
        return $pdf->download('laporan-penjualan-' . $startDate . '-to-' . $endDate . '.pdf');
    }
}