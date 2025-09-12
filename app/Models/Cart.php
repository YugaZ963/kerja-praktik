<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Cart
 *
 * Represents a shopping cart item in the application.
 *
 * @property int $id
 * @property string|null $session_id
 * @property int|null $user_id
 * @property int $product_id
 * @property int $quantity
 * @property float $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product $product
 * @property-read \App\Models\User|null $user
 * @property-read float $total
 */
class Cart extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'session_id',
        'user_id',
        'product_id',
        'quantity',
        'price'
    ];

    /**
     * Get the product associated with the cart item.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the user associated with the cart item.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the total price for the cart item.
     *
     * @return float
     */
    public function getTotalAttribute()
    {
        return $this->quantity * $this->price;
    }

    /**
     * Get cart items for the current user or session.
     *
     * @param int|null $userId The ID of the logged-in user.
     * @param string|null $sessionId The session ID for guest users.
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getCartItems($userId = null, $sessionId = null)
    {
        $query = self::with('product');
        
        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId)->whereNull('user_id');
        }
        
        return $query->get();
    }

    /**
     * Merge the session cart into the user's cart when the user logs in.
     *
     * @param int $userId The ID of the user.
     * @param string $sessionId The session ID.
     * @return void
     */
    public static function mergeSessionToUser($userId, $sessionId)
    {
        $sessionCarts = self::where('session_id', $sessionId)->whereNull('user_id')->get();
        
        foreach ($sessionCarts as $sessionCart) {
            $userCart = self::where('user_id', $userId)
                           ->where('product_id', $sessionCart->product_id)
                           ->first();
            
            if ($userCart) {
                $userCart->quantity += $sessionCart->quantity;
                $userCart->save();
            } else {
                $sessionCart->user_id = $userId;
                $sessionCart->save();
            }
        }
        
        self::where('session_id', $sessionId)->whereNotNull('user_id')->delete();
    }
}
