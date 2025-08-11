<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status');
        $search = $request->get('search');
        
        $query = Order::with(['items', 'user'])->recent();
        
        if ($status && $status !== 'all') {
            // Untuk tab "completed", tampilkan pesanan dengan status "delivered" dan "completed"
            if ($status === 'completed') {
                $query->whereIn('status', [Order::STATUS_DELIVERED, Order::STATUS_COMPLETED]);
            } else {
                $query->byStatus($status);
            }
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
        
        return view('admin.orders.index', compact('orders', 'statusCounts', 'status', 'search'));
    }

    public function show(Order $order)
    {
        $order->load(['items.product', 'user']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,payment_pending,payment_verified,processing,packaged,shipped,delivered,completed,cancelled',
            'admin_notes' => 'nullable|string',
            'tracking_number' => 'nullable|string|max:255'
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        // Update status, admin notes, dan tracking number
        $order->update([
            'status' => $newStatus,
            'admin_notes' => $request->admin_notes,
            'tracking_number' => $request->tracking_number
        ]);

        // Update timestamp berdasarkan status
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

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui!');
    }

    public function uploadPaymentProof(Request $request, Order $order)
    {
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($request->hasFile('payment_proof')) {
            // Hapus file lama jika ada
            if ($order->payment_proof) {
                Storage::disk('public')->delete($order->payment_proof);
            }

            // Upload file baru
            $path = $request->file('payment_proof')->store('payment-proofs', 'public');
            
            $order->update([
                'payment_proof' => $path,
                'status' => Order::STATUS_PAYMENT_PENDING
            ]);

            return redirect()->back()->with('success', 'Bukti pembayaran berhasil diupload!');
        }

        return redirect()->back()->with('error', 'Gagal mengupload bukti pembayaran!');
    }

    public function uploadDeliveryProof(Request $request, Order $order)
    {
        $request->validate([
            'delivery_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($request->hasFile('delivery_proof')) {
            // Hapus file lama jika ada
            if ($order->delivery_proof) {
                Storage::disk('public')->delete($order->delivery_proof);
            }

            // Upload file baru
            $path = $request->file('delivery_proof')->store('delivery-proofs', 'public');
            
            $order->update([
                'delivery_proof' => $path,
                'status' => Order::STATUS_DELIVERED,
                'delivered_at' => now()
            ]);

            return redirect()->back()->with('success', 'Bukti pengiriman berhasil diupload!');
        }

        return redirect()->back()->with('error', 'Gagal mengupload bukti pengiriman!');
    }

    public function destroy(Order $order)
    {
        // Hapus file bukti pembayaran dan pengiriman jika ada
        if ($order->payment_proof) {
            Storage::disk('public')->delete($order->payment_proof);
        }
        if ($order->delivery_proof) {
            Storage::disk('public')->delete($order->delivery_proof);
        }

        $order->delete();

        return redirect()->route('admin.orders.index')->with('success', 'Pesanan berhasil dihapus!');
    }

    private function getStatusCounts()
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
            'completed' => Order::whereIn('status', [Order::STATUS_DELIVERED, Order::STATUS_COMPLETED])->count(),
            'cancelled' => Order::byStatus(Order::STATUS_CANCELLED)->count(),
        ];
    }
}
