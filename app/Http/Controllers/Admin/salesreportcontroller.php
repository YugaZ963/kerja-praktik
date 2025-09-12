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
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\View\View;

/**
 * Class SalesReportController
 *
 * Handles sales reporting for the admin panel.
 */
class SalesReportController extends Controller
{
    /**
     * Display the sales report dashboard.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $startDate = $request->get('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
        
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();
        
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
     * Get sales data for AJAX requests.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getData(Request $request): JsonResponse
    {
        $startDate = $request->get('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
        
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();
        
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
     * Export the sales report to a PDF file.
     *
     * @param Request $request
     * @return Response
     */
    public function exportPdf(Request $request): Response
    {
        $startDate = $request->get('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
        
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();
        
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