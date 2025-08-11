<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $sessionId = Session::getId();
        
        $cartItems = Cart::getCartItems($userId, $sessionId);

        $total = $cartItems->sum('total');
        $itemCount = $cartItems->sum('quantity');

        return view('cart.index', [
            'titleShop' => 'RAVAZKA - Keranjang Belanja',
            'cartItems' => $cartItems,
            'total' => $total,
            'itemCount' => $itemCount
        ]);
    }

    public function add(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        $userId = Auth::id();
        $sessionId = Session::getId();
        $quantity = $request->input('quantity', 1);

        // Cek apakah produk sudah ada di keranjang
        $cartQuery = Cart::where('product_id', $productId);
        
        if ($userId) {
            // Jika user login, cari berdasarkan user_id
            $cartQuery->where('user_id', $userId);
        } else {
            // Jika guest, cari berdasarkan session_id
            $cartQuery->where('session_id', $sessionId)->whereNull('user_id');
        }
        
        $cartItem = $cartQuery->first();

        if ($cartItem) {
            // Update quantity jika sudah ada
            $newQuantity = $cartItem->quantity + $quantity;
            
            // Cek stok
            if ($newQuantity > $product->stock) {
                return redirect()->back()->with('error', 'Stok tidak mencukupi!');
            }
            
            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            // Cek stok
            if ($quantity > $product->stock) {
                return redirect()->back()->with('error', 'Stok tidak mencukupi!');
            }
            
            // Tambah item baru ke keranjang
            Cart::create([
                'session_id' => $sessionId,
                'user_id' => $userId,
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $product->price
            ]);
        }

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function update(Request $request, $cartId)
    {
        $cartItem = Cart::findOrFail($cartId);
        $quantity = $request->input('quantity');

        // Cek stok
        if ($quantity > $cartItem->product->stock) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi!');
        }

        if ($quantity <= 0) {
            $cartItem->delete();
        } else {
            $cartItem->update(['quantity' => $quantity]);
        }

        return redirect()->back()->with('success', 'Keranjang berhasil diperbarui!');
    }

    public function remove($cartId)
    {
        $cartItem = Cart::findOrFail($cartId);
        $cartItem->delete();

        return redirect()->back()->with('success', 'Produk berhasil dihapus dari keranjang!');
    }

    public function clear()
    {
        $userId = Auth::id();
        $sessionId = Session::getId();
        
        if ($userId) {
            // Jika user login, hapus berdasarkan user_id
            Cart::where('user_id', $userId)->delete();
        } else {
            // Jika guest, hapus berdasarkan session_id
            Cart::where('session_id', $sessionId)->whereNull('user_id')->delete();
        }

        return redirect()->back()->with('success', 'Keranjang berhasil dikosongkan!');
    }

    public function checkout()
    {
        $userId = Auth::id();
        $sessionId = Session::getId();
        
        $cartItems = Cart::getCartItems($userId, $sessionId);

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong!');
        }

        $total = $cartItems->sum('total');

        return view('cart.checkout', [
            'titleShop' => 'RAVAZKA - Checkout',
            'cartItems' => $cartItems,
            'total' => $total
        ]);
    }

    public function processOrder(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'payment_method' => 'required|string|in:bri,dana',
            'notes' => 'nullable|string'
        ]);

        $userId = Auth::id();
        $sessionId = Session::getId();
        
        $cartItems = Cart::getCartItems($userId, $sessionId);

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong!');
        }

        try {
            DB::beginTransaction();

            // Hitung total
            $subtotal = $cartItems->sum('total');
            $shippingCost = 0; // Akan dihitung nanti
            $totalAmount = $subtotal + $shippingCost;

            // Buat order
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => Auth::check() ? Auth::id() : null,
                'customer_name' => $request->name,
                'customer_phone' => $request->phone,
                'customer_address' => $request->address,
                'notes' => $request->notes,
                'payment_method' => $request->payment_method,
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'total_amount' => $totalAmount,
                'status' => Order::STATUS_PENDING
            ]);

            // Buat order items
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'product_name' => $cartItem->product->name,
                    'product_size' => $cartItem->product->size,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->price,
                    'total' => $cartItem->total
                ]);
            }

            DB::commit();

            // Buat pesan WhatsApp dengan nomor order
            $message = $this->generateWhatsAppMessage($cartItems, $request->all(), $order->order_number);
            
            // Kosongkan keranjang setelah order
            if ($userId) {
                // Jika user login, hapus berdasarkan user_id
                Cart::where('user_id', $userId)->delete();
            } else {
                // Jika guest, hapus berdasarkan session_id
                Cart::where('session_id', $sessionId)->whereNull('user_id')->delete();
            }

            // Redirect ke WhatsApp
            $whatsappNumber = '6289677754918'; // Nomor WhatsApp toko
            $whatsappUrl = "https://wa.me/{$whatsappNumber}?text=" . urlencode($message);

            return redirect()->away($whatsappUrl);

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses pesanan. Silakan coba lagi.');
        }
    }

    private function generateWhatsAppMessage($cartItems, $customerData, $orderNumber = null)
    {
        $message = "*PESANAN BARU - RAVAZKA*\n\n";
        
        if ($orderNumber) {
            $message .= "🔖 *No. Pesanan: {$orderNumber}*\n\n";
        }
        
        $message .= "📋 *Detail Pesanan:*\n";
        
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $itemSubtotal = $item->quantity * $item->price;
            $subtotal += $itemSubtotal;
            
            $message .= "• {$item->product->name}\n";
            $message .= "  Ukuran: {$item->product->size}\n";
            $message .= "  Qty: {$item->quantity} x Rp " . number_format($item->price, 0, ',', '.') . "\n";
            $message .= "  Subtotal: Rp " . number_format($itemSubtotal, 0, ',', '.') . "\n\n";
        }
        
        $total = $subtotal;
        
        $message .= "💰 *Ringkasan Biaya:*\n";
        $message .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        $message .= "*TOTAL: Rp " . number_format($total, 0, ',', '.') . "*\n\n";
        
        $message .= "👤 *Data Pelanggan:*\n";
        $message .= "Nama: {$customerData['name']}\n";
        $message .= "No. HP: {$customerData['phone']}\n";
        $message .= "Alamat: {$customerData['address']}\n";
        
        if (!empty($customerData['notes'])) {
            $message .= "Catatan: {$customerData['notes']}\n";
        }
        
        // Informasi pembayaran
        $message .= "\n💳 *Metode Pembayaran:*\n";
        if ($customerData['payment_method'] === 'bri') {
            $message .= "Bank BRI\n";
            $message .= "No. Rekening: 1234-5678-9012-3456\n";
            $message .= "Atas Nama: RAVAZKA STORE\n";
        } else if ($customerData['payment_method'] === 'dana') {
            $message .= "DANA E-Wallet\n";
            $message .= "No. DANA: 0896-7775-4918\n";
            $message .= "Atas Nama: RAVAZKA STORE\n";
        }
        
        $message .= "\n📅 Tanggal: " . date('d/m/Y H:i') . "\n";
        $message .= "\nTerima kasih telah berbelanja di RAVAZKA! 🙏";
        
        return $message;
    }



    public function getCartCount()
    {
        $userId = Auth::id();
        $sessionId = Session::getId();
        
        $cartItems = Cart::getCartItems($userId, $sessionId);
        $count = $cartItems->sum('quantity');
        
        return response()->json(['count' => $count]);
    }
}
