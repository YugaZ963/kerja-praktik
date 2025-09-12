<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

/**
 * Class CartController
 *
 * Handles shopping cart operations for customers.
 */
class CartController extends Controller
{
    /**
     * Display the customer's shopping cart.
     *
     * @return View
     */
    public function index(): View
    {
        $userId = Auth::id();
        $sessionId = Session::getId();

        $cartItems = Cart::getCartItems($userId, $sessionId);

        $total = $cartItems->sum('total');
        $itemCount = $cartItems->sum('quantity');

        return view('cart.index', [
            'titleShop' => '🛒 Keranjang Belanja - RAVAZKA | Review Pesanan Seragam Anda',
            'title' => '🛒 Keranjang Belanja - RAVAZKA | Review Pesanan Seragam Anda',
            'metaDescription' => '🛍️ Review dan kelola pesanan seragam sekolah Anda di keranjang RAVAZKA. Ubah jumlah, hapus item, atau lanjutkan ke checkout dengan mudah dan aman.',
            'metaKeywords' => 'keranjang belanja RAVAZKA, review pesanan seragam, checkout seragam sekolah, kelola pesanan',
            'cartItems' => $cartItems,
            'total' => $total,
            'itemCount' => $itemCount
        ]);
    }

    /**
     * Add a product to the shopping cart.
     *
     * @param Request $request
     * @param int $productId
     * @return RedirectResponse
     */
    public function add(Request $request, int $productId): RedirectResponse
    {
        $product = Product::findOrFail($productId);
        $userId = Auth::id();
        $sessionId = Session::getId();
        $quantity = $request->input('quantity', 1);

        $cartQuery = Cart::where('product_id', $productId);

        if ($userId) {
            $cartQuery->where('user_id', $userId);
        } else {
            $cartQuery->where('session_id', $sessionId)->whereNull('user_id');
        }

        $cartItem = $cartQuery->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $quantity;

            if ($newQuantity > $product->stock) {
                return redirect()->back()->with('error', 'Insufficient stock!');
            }

            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            if ($quantity > $product->stock) {
                return redirect()->back()->with('error', 'Insufficient stock!');
            }

            Cart::create([
                'session_id' => $sessionId,
                'user_id' => $userId,
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $product->price
            ]);
        }

        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }

    /**
     * Update the quantity of a cart item.
     *
     * @param Request $request
     * @param int $cartId
     * @return RedirectResponse
     */
    public function update(Request $request, int $cartId): RedirectResponse
    {
        $cartItem = Cart::findOrFail($cartId);
        $quantity = $request->input('quantity');

        if ($quantity > $cartItem->product->stock) {
            return redirect()->back()->with('error', 'Insufficient stock!');
        }

        if ($quantity <= 0) {
            $cartItem->delete();
        } else {
            $cartItem->update(['quantity' => $quantity]);
        }

        return redirect()->back()->with('success', 'Cart updated successfully!');
    }

    /**
     * Remove an item from the shopping cart.
     *
     * @param int $cartId
     * @return RedirectResponse
     */
    public function remove(int $cartId): RedirectResponse
    {
        $cartItem = Cart::findOrFail($cartId);
        $cartItem->delete();

        return redirect()->back()->with('success', 'Product removed from cart successfully!');
    }

    /**
     * Clear all items from the shopping cart.
     *
     * @return RedirectResponse
     */
    public function clear(): RedirectResponse
    {
        $userId = Auth::id();
        $sessionId = Session::getId();

        if ($userId) {
            Cart::where('user_id', $userId)->delete();
        } else {
            Cart::where('session_id', $sessionId)->whereNull('user_id')->delete();
        }

        return redirect()->back()->with('success', 'Cart cleared successfully!');
    }

    /**
     * Display the checkout page.
     *
     * @return View|RedirectResponse
     */
    public function checkout()
    {
        $userId = Auth::id();
        $sessionId = Session::getId();

        $cartItems = Cart::getCartItems($userId, $sessionId);

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Cart is empty!');
        }

        $total = $cartItems->sum('total');

        return view('cart.checkout', [
            'titleShop' => '💳 Checkout Pesanan - RAVAZKA | Selesaikan Pembelian Seragam',
            'title' => '💳 Checkout Pesanan - RAVAZKA | Selesaikan Pembelian Seragam',
            'metaDescription' => '✅ Selesaikan pembelian seragam sekolah Anda di RAVAZKA. Isi data pengiriman, pilih metode pembayaran, dan konfirmasi pesanan melalui WhatsApp.',
            'metaKeywords' => 'checkout RAVAZKA, beli seragam sekolah, pembayaran seragam, konfirmasi pesanan WhatsApp',
            'cartItems' => $cartItems,
            'total' => $total
        ]);
    }

    /**
     * Process the customer's order.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function processOrder(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'payment_method' => 'required|string|in:bri,dana',
            'shipping_method' => 'required|string|in:reguler,express',
            'notes' => 'nullable|string|max:500'
        ]);

        $userId = Auth::id();
        $sessionId = Session::getId();
        
        $cartItems = Cart::getCartItems($userId, $sessionId);

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Cart is empty! Please add products first.');
        }

        try {
            DB::beginTransaction();

            $subtotal = $cartItems->sum('total');
            $shippingCost = 0;
            $totalAmount = $subtotal;

            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => Auth::check() ? Auth::id() : null,
                'customer_name' => $validated['name'],
                'customer_email' => Auth::check() ? Auth::user()->email : null,
                'customer_phone' => $validated['phone'],
                'customer_address' => $validated['address'],
                'notes' => $validated['notes'] ?? null,
                'payment_method' => $validated['payment_method'],
                'shipping_method' => $validated['shipping_method'],
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'total_amount' => $totalAmount,
                'status' => Order::STATUS_PENDING
            ]);

            foreach ($cartItems as $cartItem) {
                if ($cartItem->product->stock < $cartItem->quantity) {
                    throw new \Exception("Insufficient stock for product {$cartItem->product->name}. Available stock: {$cartItem->product->stock}");
                }

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

            $message = $this->generateWhatsAppMessage($cartItems, $validated, $order->order_number);

            if ($userId) {
                Cart::where('user_id', $userId)->delete();
            } else {
                Cart::where('session_id', $sessionId)->whereNull('user_id')->delete();
            }

            $whatsappNumber = '6289677754918';
            $whatsappUrl = "https://wa.me/{$whatsappNumber}?text=" . urlencode($message);

            \Log::info('Order created successfully', [
                'order_number' => $order->order_number,
                'customer_name' => $validated['name'],
                'total_amount' => $totalAmount
            ]);

            return redirect()->away($whatsappUrl);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            return redirect()->back()->withErrors($e->errors())->withInput();
            
        } catch (\Exception $e) {
            DB::rollback();
            
            \Log::error('Order processing failed', [
                'error' => $e->getMessage(),
                'customer_name' => $validated['name'] ?? 'Unknown',
                'user_id' => $userId,
                'session_id' => $sessionId
            ]);
            
            return redirect()->back()
                ->with('error', 'An error occurred while processing your order. Please try again or contact customer service.')
                ->withInput();
        }
    }

    /**
     * Generate a WhatsApp message for the order.
     *
     * @param mixed $cartItems
     * @param array $customerData
     * @param string|null $orderNumber
     * @return string
     */
    private function generateWhatsAppMessage($cartItems, array $customerData, ?string $orderNumber = null): string
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
        $message .= "Subtotal: Rp " . number_format($subtotal, 0, ',', '.') . "\n";
        
        $shippingLabel = isset($customerData['shipping_method']) && $customerData['shipping_method'] === 'express' ? 'Express (1-2 hari)' : 'Reguler (3-5 hari)';
        $message .= "Pengiriman: {$shippingLabel} - GRATIS\n";
        
        $message .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        $message .= "*TOTAL: Rp " . number_format($total, 0, ',', '.') . "*\n\n";

        $message .= "👤 *Data Pelanggan:*\n";
        $message .= "Nama: {$customerData['name']}\n";
        $message .= "No. HP: {$customerData['phone']}\n";
        $message .= "Alamat: {$customerData['address']}\n";
        
        $shippingMethodLabel = isset($customerData['shipping_method']) && $customerData['shipping_method'] === 'express' ? 'Express (1-2 hari)' : 'Reguler (3-5 hari)';
        $message .= "Metode Pengiriman: {$shippingMethodLabel}\n";

        if (!empty($customerData['notes'])) {
            $message .= "Catatan: {$customerData['notes']}\n";
        }

        $message .= "\n💳 *Metode Pembayaran:*\n";
        if ($customerData['payment_method'] === 'bri') {
            $message .= "Bank BRI\n";
            $message .= "No. Rekening: 1234-5678-9012-3456\n";
            $message .= "Atas Nama: Yuga Azka Al Razzak\n";
        } else if ($customerData['payment_method'] === 'dana') {
            $message .= "DANA E-Wallet\n";
            $message .= "No. DANA: 0896-7775-4918\n";
            $message .= "Atas Nama: Yuga Azka Al Razzak\n";
        }

        $message .= "\n⚠️ *PENTING:*\n";
        $message .= "Transfer hanya atas nama rekening diatas\n";

        $message .= "\n📅 Tanggal: " . date('d/m/Y H:i') . "\n";
        $message .= "\nTerima kasih telah berbelanja di RAVAZKA! 🙏";

        return $message;
    }



    /**
     * Get the number of items in the cart.
     *
     * @return JsonResponse
     */
    public function getCartCount(): JsonResponse
    {
        $userId = Auth::id();
        $sessionId = Session::getId();

        $cartItems = Cart::getCartItems($userId, $sessionId);
        $count = $cartItems->sum('quantity');

        return response()->json(['count' => $count]);
    }
}
