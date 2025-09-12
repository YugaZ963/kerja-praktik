<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Class OrderController
 *
 * Handles order management for the admin panel.
 */
class OrderController extends Controller
{
    /**
     * Display a listing of the orders.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $status = $request->get('status');
        $search = $request->get('search');
        
        $query = Order::with(['items', 'user'])->recent();
        
        if ($status && $status !== 'all') {
            $query->byStatus($status);
        }
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }
        
        $orders = $query->paginate(15);
        $statusCounts = $this->getStatusCounts();
        
        return view('admin.orders.index', [
            'titleShop' => '📋 Manajemen Pesanan - Admin RAVAZKA | Kelola Order Seragam',
            'title' => '📋 Manajemen Pesanan - Admin RAVAZKA | Kelola Order Seragam',
            'metaDescription' => '🛒 Panel admin untuk mengelola pesanan seragam sekolah RAVAZKA. Monitor status pesanan, verifikasi pembayaran, dan kelola pengiriman dengan sistem tracking lengkap.',
            'metaKeywords' => 'manajemen pesanan RAVAZKA, admin order seragam, kelola pesanan, status pembayaran, tracking pengiriman',
            'orders' => $orders,
            'statusCounts' => $statusCounts,
            'status' => $status,
            'search' => $search
        ]);
    }

    /**
     * Display the specified order.
     *
     * @param Order $order
     * @return View
     */
    public function show(Order $order): View
    {
        $order->load(['items.product', 'user']);
        return view('admin.orders.show', [
            'titleShop' => '🔍 Detail Pesanan - Admin RAVAZKA | Info Lengkap Order',
            'title' => '🔍 Detail Pesanan - Admin RAVAZKA | Info Lengkap Order',
            'metaDescription' => '📋 Lihat detail lengkap pesanan seragam sekolah di panel admin RAVAZKA. Informasi pelanggan, item pesanan, status pembayaran, dan riwayat pengiriman.',
            'metaKeywords' => 'detail pesanan RAVAZKA, info order seragam, admin pesanan, status pembayaran, detail pelanggan',
            'order' => $order
        ]);
    }

    /**
     * Update the status of the specified order.
     *
     * @param Request $request
     * @param Order $order
     * @return RedirectResponse
     */
    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:pending,payment_pending,payment_verified,processing,packaged,shipped,delivered,completed,cancelled',
            'admin_notes' => 'nullable|string',
            'tracking_number' => 'nullable|string|max:255'
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        $order->update([
            'status' => $newStatus,
            'admin_notes' => $request->admin_notes,
            'tracking_number' => $request->tracking_number
        ]);

        switch ($newStatus) {
            case Order::STATUS_PAYMENT_VERIFIED:
                $order->update(['payment_verified_at' => now()]);
                break;
            case Order::STATUS_SHIPPED:
                $order->update(['shipped_at' => now()]);
                break;
            case Order::STATUS_DELIVERED:
                $order->update(['delivered_at' => now()]);
                break;
        }

        if ($newStatus === Order::STATUS_DELIVERED && 
            $oldStatus !== Order::STATUS_DELIVERED &&
            !$order->stock_reduced) {
            $this->reduceProductStock($order);
        }

        return redirect()->back()->with('success', 'Order status updated successfully!');
    }

    /**
     * Upload a payment proof for the specified order.
     *
     * @param Request $request
     * @param Order $order
     * @return RedirectResponse
     */
    public function uploadPaymentProof(Request $request, Order $order): RedirectResponse
    {
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($request->hasFile('payment_proof')) {
            if ($order->payment_proof) {
                Storage::disk('public')->delete($order->payment_proof);
            }

            $path = $request->file('payment_proof')->store('payment-proofs', 'public');
            
            $order->update([
                'payment_proof' => $path,
                'status' => Order::STATUS_PAYMENT_PENDING
            ]);

            return redirect()->back()->with('success', 'Payment proof uploaded successfully!');
        }

        return redirect()->back()->with('error', 'Failed to upload payment proof!');
    }

    /**
     * Upload a delivery proof for the specified order.
     *
     * @param Request $request
     * @param Order $order
     * @return RedirectResponse
     */
    public function uploadDeliveryProof(Request $request, Order $order): RedirectResponse
    {
        $request->validate([
            'delivery_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($request->hasFile('delivery_proof')) {
            if ($order->delivery_proof) {
                Storage::disk('public')->delete($order->delivery_proof);
            }

            $path = $request->file('delivery_proof')->store('delivery-proofs', 'public');
            
            $order->update([
                'delivery_proof' => $path,
                'status' => Order::STATUS_DELIVERED,
                'delivered_at' => now()
            ]);

            return redirect()->back()->with('success', 'Delivery proof uploaded successfully!');
        }

        return redirect()->back()->with('error', 'Failed to upload delivery proof!');
    }

    /**
     * Remove the specified order from storage.
     *
     * @param Order $order
     * @return RedirectResponse
     */
    public function destroy(Order $order): RedirectResponse
    {
        if ($order->payment_proof) {
            Storage::disk('public')->delete($order->payment_proof);
        }
        if ($order->delivery_proof) {
            Storage::disk('public')->delete($order->delivery_proof);
        }

        $order->delete();

        return redirect()->route('admin.orders.index')->with('success', 'Order deleted successfully!');
    }

    /**
     * Reduce product stock based on order items.
     *
     * @param Order $order
     * @return void
     */
    private function reduceProductStock(Order $order): void
    {
        try {
            foreach ($order->items as $orderItem) {
                $product = Product::where('name', $orderItem->product_name)
                                ->where('size', $orderItem->product_size)
                                ->first();

                if ($product) {
                    $newStock = max(0, $product->stock - $orderItem->quantity);
                    $product->update(['stock' => $newStock]);

                    Log::info("Stock reduced for product: {$product->name} (Size: {$product->size})", [
                        'order_number' => $order->order_number,
                        'product_id' => $product->id,
                        'quantity_ordered' => $orderItem->quantity,
                        'old_stock' => $product->stock + $orderItem->quantity,
                        'new_stock' => $newStock
                    ]);
                } else {
                    Log::warning("Product not found for stock reduction", [
                        'order_number' => $order->order_number,
                        'product_name' => $orderItem->product_name,
                        'product_size' => $orderItem->product_size,
                        'quantity' => $orderItem->quantity
                    ]);
                }
            }
            
            $order->update([
                'stock_reduced' => true,
                'stock_reduced_at' => now()
            ]);
            
        } catch (\Exception $e) {
            Log::error("Failed to reduce product stock for order: {$order->order_number}", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Get the count of orders for each status.
     *
     * @return array
     */
    private function getStatusCounts(): array
    {
        return [
            'all' => Order::count(),
            'pending' => Order::byStatus(Order::STATUS_PENDING)->count(),
            'payment_pending' => Order::byStatus(Order::STATUS_PAYMENT_PENDING)->count(),
            'payment_verified' => Order::byStatus(Order::STATUS_PAYMENT_VERIFIED)->count(),
            'processing' => Order::byStatus(Order::STATUS_PROCESSING)->count(),
            'packaged' => Order::byStatus(Order::STATUS_PACKAGED)->count(),
            'shipped' => Order::byStatus(Order::STATUS_SHIPPED)->count(),
            'delivered' => Order::byStatus(Order::STATUS_DELIVERED)->count(),
            'completed' => Order::byStatus(Order::STATUS_COMPLETED)->count(),
            'cancelled' => Order::byStatus(Order::STATUS_CANCELLED)->count(),
        ];
    }
}
