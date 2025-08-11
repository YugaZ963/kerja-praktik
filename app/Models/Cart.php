<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'session_id',
        'user_id',
        'product_id',
        'quantity',
        'price'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getTotalAttribute()
    {
        return $this->quantity * $this->price;
    }

    /**
     * Get cart items for current user/session
     */
    public static function getCartItems($userId = null, $sessionId = null)
    {
        $query = self::with('product');
        
        if ($userId) {
            // Jika user login, ambil cart berdasarkan user_id
            $query->where('user_id', $userId);
        } else {
            // Jika guest, ambil cart berdasarkan session_id
            $query->where('session_id', $sessionId)->whereNull('user_id');
        }
        
        return $query->get();
    }

    /**
     * Merge session cart to user cart when user logs in
     */
    public static function mergeSessionToUser($userId, $sessionId)
    {
        $sessionCarts = self::where('session_id', $sessionId)->whereNull('user_id')->get();
        
        foreach ($sessionCarts as $sessionCart) {
            // Cek apakah produk sudah ada di cart user
            $userCart = self::where('user_id', $userId)
                           ->where('product_id', $sessionCart->product_id)
                           ->first();
            
            if ($userCart) {
                // Jika sudah ada, tambahkan quantity
                $userCart->quantity += $sessionCart->quantity;
                $userCart->save();
            } else {
                // Jika belum ada, pindahkan ke user cart
                $sessionCart->user_id = $userId;
                $sessionCart->save();
            }
        }
        
        // Hapus cart session yang sudah dipindahkan
        self::where('session_id', $sessionId)->whereNotNull('user_id')->delete();
    }
}
