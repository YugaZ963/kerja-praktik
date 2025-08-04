<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function index()
    {
        $sessionId = Session::getId();
        $cartItems = Cart::with('product')
            ->where('session_id', $sessionId)
            ->get();

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
        $sessionId = Session::getId();
        $quantity = $request->input('quantity', 1);

        // Cek apakah produk sudah ada di keranjang
        $cartItem = Cart::where('session_id', $sessionId)
            ->where('product_id', $productId)
            ->first();

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
        $sessionId = Session::getId();
        Cart::where('session_id', $sessionId)->delete();

        return redirect()->back()->with('success', 'Keranjang berhasil dikosongkan!');
    }

    public function checkout()
    {
        $sessionId = Session::getId();
        $cartItems = Cart::with('product')
            ->where('session_id', $sessionId)
            ->get();

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
            'notes' => 'nullable|string'
        ]);

        $sessionId = Session::getId();
        $cartItems = Cart::with('product')
            ->where('session_id', $sessionId)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong!');
        }

        // Buat pesan WhatsApp
        $message = $this->generateWhatsAppMessage($cartItems, $request->all());
        
        // Kosongkan keranjang setelah order
        Cart::where('session_id', $sessionId)->delete();

        // Redirect ke WhatsApp
        $whatsappNumber = '6289677754918'; // Nomor WhatsApp toko
        $whatsappUrl = "https://wa.me/{$whatsappNumber}?text=" . urlencode($message);

        return redirect()->away($whatsappUrl);
    }

    private function generateWhatsAppMessage($cartItems, $customerData)
    {
        $message = "*PESANAN BARU - RAVAZKA*\n\n";
        $message .= "📋 *Detail Pesanan:*\n";
        
        $total = 0;
        foreach ($cartItems as $item) {
            $subtotal = $item->quantity * $item->price;
            $total += $subtotal;
            
            $message .= "• {$item->product->name}\n";
            $message .= "  Ukuran: {$item->product->size}\n";
            $message .= "  Qty: {$item->quantity} x Rp " . number_format($item->price, 0, ',', '.') . "\n";
            $message .= "  Subtotal: Rp " . number_format($subtotal, 0, ',', '.') . "\n\n";
        }
        
        $message .= "💰 *Total: Rp " . number_format($total, 0, ',', '.') . "*\n\n";
        
        $message .= "👤 *Data Pelanggan:*\n";
        $message .= "Nama: {$customerData['name']}\n";
        $message .= "No. HP: {$customerData['phone']}\n";
        $message .= "Alamat: {$customerData['address']}\n";
        
        if (!empty($customerData['notes'])) {
            $message .= "Catatan: {$customerData['notes']}\n";
        }
        
        $message .= "\n📅 Tanggal: " . date('d/m/Y H:i') . "\n";
        $message .= "\nTerima kasih telah berbelanja di RAVAZKA! 🙏";
        
        return $message;
    }

    public function getCartCount()
    {
        $sessionId = Session::getId();
        $count = Cart::where('session_id', $sessionId)->sum('quantity');
        
        return response()->json(['count' => $count]);
    }
}
