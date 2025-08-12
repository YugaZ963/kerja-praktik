<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Display customer's orders
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Get status filter
        $status = $request->get('status', 'all');
        
        // Build query
        $query = Order::where('user_id', $user->id)
                     ->with(['items.product'])
                     ->orderBy('created_at', 'desc');
        
        // Apply status filter
        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }
        
        $orders = $query->paginate(10);
        
        // Get status counts for tabs
        $statusCounts = $this->getStatusCounts($user->id);
        
        return view('customer.orders.index', compact('orders', 'status', 'statusCounts'));
    }
    
    /**
     * Show specific order details
     */
    public function show($orderNumber)
    {
        $user = Auth::user();
        
        $order = Order::where('order_number', $orderNumber)
                     ->where('user_id', $user->id)
                     ->with(['items.product'])
                     ->firstOrFail();
        
        return view('customer.orders.show', compact('order'));
    }
    
    /**
     * Track order by order number
     */
    public function track(Request $request)
    {
        $order = null;
        
        if ($request->has('order_number')) {
            $order = Order::where('order_number', $request->order_number)
                          ->where('user_id', auth()->id())
                          ->first();
        }
        
        return view('customer.orders.track', compact('order'));
    }
    
    /**
     * Get status counts for customer orders
     */
    private function getStatusCounts($userId)
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
     * Upload payment proof
     */
    public function uploadPaymentProof(Request $request, Order $order)
    {
        $user = Auth::user();
        
        // Log the upload attempt
        \Log::info('Payment proof upload attempt', [
            'user_id' => $user->id,
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'has_file' => $request->hasFile('payment_proof')
        ]);
        
        // Verify order belongs to customer
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
                // Delete old payment proof if exists
                if ($order->payment_proof) {
                    \Storage::disk('public')->delete($order->payment_proof);
                    \Log::info('Deleted old payment proof', ['old_path' => $order->payment_proof]);
                }
                
                // Store new payment proof
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
                
                return back()->with('success', 'Bukti pembayaran berhasil diunggah. Pesanan Anda akan segera diverifikasi.');
            } catch (\Exception $e) {
                \Log::error('Payment proof upload failed', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                return back()->withErrors(['payment_proof' => 'Gagal mengunggah bukti pembayaran: ' . $e->getMessage()]);
            }
        }
        
        \Log::warning('No file uploaded for payment proof', [
            'order_id' => $order->id,
            'request_files' => $request->allFiles()
        ]);
        
        return back()->withErrors(['payment_proof' => 'Gagal mengunggah bukti pembayaran.']);
    }

    /**
     * Upload delivery proof
     */
    public function uploadDeliveryProof(Request $request, Order $order)
    {
        $user = Auth::user();
        
        // Log the upload attempt
        \Log::info('Delivery proof upload attempt', [
            'user_id' => $user->id,
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'has_file' => $request->hasFile('delivery_proof')
        ]);
        
        // Verify order belongs to customer
        if ($order->user_id !== $user->id) {
            \Log::warning('Unauthorized delivery proof upload attempt', [
                'user_id' => $user->id,
                'order_id' => $order->id,
                'order_user_id' => $order->user_id
            ]);
            abort(403, 'Unauthorized access to order');
        }

        // Verify order status is delivered
        if ($order->status !== 'delivered') {
            \Log::warning('Invalid status for delivery proof upload', [
                'order_id' => $order->id,
                'current_status' => $order->status,
                'required_status' => 'delivered'
            ]);
            return back()->withErrors(['delivery_proof' => 'Upload foto bukti hanya dapat dilakukan untuk pesanan dengan status "Sudah Sampai".']);
        }
        
        $request->validate([
            'delivery_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'delivery_notes' => 'nullable|string|max:500'
        ]);
        
        if ($request->hasFile('delivery_proof')) {
            try {
                // Delete old delivery proof if exists
                if ($order->delivery_proof) {
                    \Storage::disk('public')->delete($order->delivery_proof);
                    \Log::info('Deleted old delivery proof', ['old_path' => $order->delivery_proof]);
                }
                
                // Store new delivery proof
                $path = $request->file('delivery_proof')->store('delivery-proofs', 'public');
                \Log::info('Stored new delivery proof', ['new_path' => $path]);
                
                $order->update([
                    'delivery_proof' => $path,
                    'admin_notes' => $order->admin_notes . "\n\nCustomer upload bukti barang sudah sampai pada " . now()->format('d/m/Y H:i') . 
                                   ($request->delivery_notes ? "\nCatatan customer: " . $request->delivery_notes : "")
                ]);
                
                // Kurangi stok produk ketika status berubah menjadi delivered (jika belum dikurangi)
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
                
                return back()->with('success', 'Foto bukti barang sudah sampai berhasil diunggah.');
            } catch (\Exception $e) {
                \Log::error('Delivery proof upload failed', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                return back()->withErrors(['delivery_proof' => 'Gagal mengunggah foto bukti: ' . $e->getMessage()]);
            }
        }
        
        \Log::warning('No file uploaded for delivery proof', [
            'order_id' => $order->id,
            'request_files' => $request->allFiles()
        ]);
        
        return back()->withErrors(['delivery_proof' => 'Gagal mengunggah foto bukti.']);
    }

    /**
     * Kurangi stok produk berdasarkan order items
     */
    private function reduceProductStock(Order $order)
    {
        try {
            foreach ($order->items as $orderItem) {
                // Cari produk berdasarkan nama dan ukuran
                $product = Product::where('name', $orderItem->product_name)
                                ->where('size', $orderItem->product_size)
                                ->first();

                if ($product) {
                    // Kurangi stok produk
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
            
            // Tandai bahwa stok sudah dikurangi
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
     * Mark order as completed by customer
     */
    public function markAsCompleted(Order $order)
    {
        $user = Auth::user();
        
        // Log the completion attempt
        \Log::info('Order completion attempt', [
            'user_id' => $user->id,
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'current_status' => $order->status
        ]);
        
        // Verify order belongs to customer
        if ($order->user_id !== $user->id) {
            \Log::warning('Unauthorized order completion attempt', [
                'user_id' => $user->id,
                'order_id' => $order->id,
                'order_user_id' => $order->user_id
            ]);
            abort(403, 'Unauthorized access to order');
        }

        // Verify order status is delivered
        if ($order->status !== Order::STATUS_DELIVERED) {
            \Log::warning('Invalid status for order completion', [
                'order_id' => $order->id,
                'current_status' => $order->status,
                'required_status' => Order::STATUS_DELIVERED
            ]);
            return back()->withErrors(['status' => 'Pesanan hanya dapat diselesaikan jika sudah berstatus "Sudah Sampai".']);
        }
        
        try {
            $order->update([
                'status' => Order::STATUS_COMPLETED,
                'completed_at' => now(),
                'admin_notes' => $order->admin_notes . "\n\nPesanan ditandai selesai oleh customer pada " . now()->format('d/m/Y H:i')
            ]);
            
            \Log::info('Order marked as completed successfully', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'completed_at' => $order->completed_at
            ]);
            
            return back()->with('success', 'Pesanan berhasil ditandai sebagai selesai. Terima kasih atas kepercayaan Anda!');
        } catch (\Exception $e) {
            \Log::error('Failed to mark order as completed', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withErrors(['status' => 'Gagal menandai pesanan sebagai selesai: ' . $e->getMessage()]);
        }
    }
}