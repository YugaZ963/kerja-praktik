<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Class OrderController
 *
 * Handles customer order management.
 */
class OrderController extends Controller
{
    /**
     * Display the customer's order history.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        
        $status = $request->get('status', 'all');
        
        $query = Order::where('user_id', $user->id)
                     ->with(['items.product'])
                     ->orderBy('created_at', 'desc');
        
        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }
        
        $orders = $query->paginate(10);
        
        $statusCounts = $this->getStatusCounts($user->id);
        
        return view('customer.orders.index', [
            'titleShop' => '📦 Pesanan Saya - RAVAZKA | Riwayat Order Seragam Sekolah',
            'title' => '📦 Pesanan Saya - RAVAZKA | Riwayat Order Seragam Sekolah',
            'metaDescription' => '🛍️ Lihat riwayat pesanan seragam sekolah Anda di RAVAZKA. Cek status pembayaran, pengiriman, dan tracking pesanan dengan mudah dan real-time.',
            'metaKeywords' => 'pesanan RAVAZKA, riwayat order seragam, status pesanan, tracking pengiriman, history belanja',
            'orders' => $orders,
            'status' => $status,
            'statusCounts' => $statusCounts
        ]);
    }
    
    /**
     * Display the details of a specific order.
     *
     * @param string $orderNumber
     * @return View
     */
    public function show(string $orderNumber): View
    {
        $user = Auth::user();
        
        $order = Order::where('order_number', $orderNumber)
                     ->where('user_id', $user->id)
                     ->with(['items.product'])
                     ->firstOrFail();
        
        return view('customer.orders.show', [
            'titleShop' => '🔍 Detail Pesanan - RAVAZKA | Info Lengkap Order Seragam',
            'title' => '🔍 Detail Pesanan - RAVAZKA | Info Lengkap Order Seragam',
            'metaDescription' => '📋 Detail lengkap pesanan seragam sekolah Anda di RAVAZKA. Informasi produk, harga, status pembayaran, dan tracking pengiriman secara real-time.',
            'metaKeywords' => 'detail pesanan RAVAZKA, info order seragam, status pembayaran, tracking pesanan, detail produk',
            'order' => $order
        ]);
    }

    
    /**
     * Get the count of orders for each status for the current customer.
     *
     * @param int $userId
     * @return array
     */
    private function getStatusCounts(int $userId): array
    {
        return [
            'all' => Order::where('user_id', $userId)->count(),
            'pending' => Order::where('user_id', $userId)->where('status', Order::STATUS_PENDING)->count(),
            'payment_pending' => Order::where('user_id', $userId)->where('status', Order::STATUS_PAYMENT_PENDING)->count(),
            'payment_verified' => Order::where('user_id', $userId)->where('status', Order::STATUS_PAYMENT_VERIFIED)->count(),
            'processing' => Order::where('user_id', $userId)->where('status', Order::STATUS_PROCESSING)->count(),
            'packaged' => Order::where('user_id', $userId)->where('status', Order::STATUS_PACKAGED)->count(),
            'shipped' => Order::where('user_id', $userId)->where('status', Order::STATUS_SHIPPED)->count(),
            'delivered' => Order::where('user_id', $userId)->where('status', Order::STATUS_DELIVERED)->count(),
            'completed' => Order::where('user_id', $userId)->where('status', Order::STATUS_COMPLETED)->count(),
            'cancelled' => Order::where('user_id', $userId)->where('status', Order::STATUS_CANCELLED)->count(),
        ];
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
        $user = Auth::user();
        
        \Log::info('Payment proof upload attempt', [
            'user_id' => $user->id,
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'has_file' => $request->hasFile('payment_proof')
        ]);
        
        if ($order->user_id !== $user->id) {
            \Log::warning('Unauthorized payment proof upload attempt', [
                'user_id' => $user->id,
                'order_id' => $order->id,
                'order_user_id' => $order->user_id
            ]);
            abort(403, 'Unauthorized access to order');
        }
        
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);
        
        if ($request->hasFile('payment_proof')) {
            try {
                if ($order->payment_proof) {
                    \Storage::disk('public')->delete($order->payment_proof);
                    \Log::info('Deleted old payment proof', ['old_path' => $order->payment_proof]);
                }
                
                $path = $request->file('payment_proof')->store('payment-proofs', 'public');
                \Log::info('Stored new payment proof', ['new_path' => $path]);
                
                $order->update([
                    'payment_proof' => $path,
                    'status' => 'payment_pending'
                ]);
                
                \Log::info('Payment proof upload successful', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'path' => $path,
                    'status' => 'payment_pending'
                ]);
                
                return back()->with('success', 'Payment proof uploaded successfully. Your order will be verified soon.');
            } catch (\Exception $e) {
                \Log::error('Payment proof upload failed', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                return back()->withErrors(['payment_proof' => 'Failed to upload payment proof: ' . $e->getMessage()]);
            }
        }
        
        \Log::warning('No file uploaded for payment proof', [
            'order_id' => $order->id,
            'request_files' => $request->allFiles()
        ]);
        
        return back()->withErrors(['payment_proof' => 'Failed to upload payment proof.']);
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
        $user = Auth::user();
        
        \Log::info('Delivery proof upload attempt', [
            'user_id' => $user->id,
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'has_file' => $request->hasFile('delivery_proof')
        ]);
        
        if ($order->user_id !== $user->id) {
            \Log::warning('Unauthorized delivery proof upload attempt', [
                'user_id' => $user->id,
                'order_id' => $order->id,
                'order_user_id' => $order->user_id
            ]);
            abort(403, 'Unauthorized access to order');
        }

        if ($order->status !== 'delivered') {
            \Log::warning('Invalid status for delivery proof upload', [
                'order_id' => $order->id,
                'current_status' => $order->status,
                'required_status' => 'delivered'
            ]);
            return back()->withErrors(['delivery_proof' => 'You can only upload a delivery proof for orders with "Delivered" status.']);
        }
        
        $request->validate([
            'delivery_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'delivery_notes' => 'nullable|string|max:500'
        ]);
        
        if ($request->hasFile('delivery_proof')) {
            try {
                if ($order->delivery_proof) {
                    \Storage::disk('public')->delete($order->delivery_proof);
                    \Log::info('Deleted old delivery proof', ['old_path' => $order->delivery_proof]);
                }
                
                $path = $request->file('delivery_proof')->store('delivery-proofs', 'public');
                \Log::info('Stored new delivery proof', ['new_path' => $path]);
                
                $order->update([
                    'delivery_proof' => $path,
                    'admin_notes' => $order->admin_notes . "\n\nCustomer uploaded delivery proof on " . now()->format('d/m/Y H:i') .
                                   ($request->delivery_notes ? "\nCustomer notes: " . $request->delivery_notes : "")
                ]);
                
        if (!$order->stock_reduced) {
            $this->reduceProductStock($order);
        }
                
                \Log::info('Delivery proof upload successful', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'path' => $path,
                    'status' => 'delivered',
                    'delivered_at' => $order->delivered_at
                ]);
                
                return back()->with('success', 'Delivery proof uploaded successfully.');
            } catch (\Exception $e) {
                \Log::error('Delivery proof upload failed', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                return back()->withErrors(['delivery_proof' => 'Failed to upload delivery proof: ' . $e->getMessage()]);
            }
        }
        
        \Log::warning('No file uploaded for delivery proof', [
            'order_id' => $order->id,
            'request_files' => $request->allFiles()
        ]);
        
        return back()->withErrors(['delivery_proof' => 'Failed to upload delivery proof.']);
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
     * Mark an order as completed by the customer.
     *
     * @param Order $order
     * @return RedirectResponse
     */
    public function markAsCompleted(Order $order): RedirectResponse
    {
        $user = Auth::user();
        
        \Log::info('Order completion attempt', [
            'user_id' => $user->id,
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'current_status' => $order->status
        ]);
        
        if ($order->user_id !== $user->id) {
            \Log::warning('Unauthorized order completion attempt', [
                'user_id' => $user->id,
                'order_id' => $order->id,
                'order_user_id' => $order->user_id
            ]);
            abort(403, 'Unauthorized access to order');
        }

        if ($order->status !== Order::STATUS_DELIVERED) {
            \Log::warning('Invalid status for order completion', [
                'order_id' => $order->id,
                'current_status' => $order->status,
                'required_status' => Order::STATUS_DELIVERED
            ]);
            return back()->withErrors(['status' => 'An order can only be completed if its status is "Delivered".']);
        }
        
        try {
            $order->update([
                'status' => Order::STATUS_COMPLETED,
                'completed_at' => now(),
                'admin_notes' => $order->admin_notes . "\n\nOrder marked as completed by customer on " . now()->format('d/m/Y H:i')
            ]);
            
            \Log::info('Order marked as completed successfully', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'completed_at' => $order->completed_at
            ]);
            
            return back()->with('success', 'Order marked as completed successfully. Thank you for your trust!');
        } catch (\Exception $e) {
            \Log::error('Failed to mark order as completed', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withErrors(['status' => 'Failed to mark order as completed: ' . $e->getMessage()]);
        }
    }
}