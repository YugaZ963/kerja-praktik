<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Order
 *
 * Represents a customer order in the application.
 *
 * @property int $id
 * @property string $order_number
 * @property int|null $user_id
 * @property string $customer_name
 * @property string $customer_email
 * @property string $customer_phone
 * @property string $customer_address
 * @property string|null $notes
 * @property string $payment_method
 * @property string $shipping_method
 * @property float $subtotal
 * @property float $shipping_cost
 * @property float $total_amount
 * @property string $status
 * @property string|null $payment_proof
 * @property \Illuminate\Support\Carbon|null $payment_verified_at
 * @property \Illuminate\Support\Carbon|null $shipped_at
 * @property \Illuminate\Support\Carbon|null $delivered_at
 * @property string|null $delivery_proof
 * @property string|null $admin_notes
 * @property string|null $tracking_number
 * @property bool $stock_reduced
 * @property \Illuminate\Support\Carbon|null $stock_reduced_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\OrderItem[] $items
 * @property-read string $status_label
 * @property-read string $payment_method_label
 * @property-read string $shipping_method_label
 */
class Order extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_number',
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_address',
        'notes',
        'payment_method',
        'shipping_method',
        'subtotal',
        'shipping_cost',
        'total_amount',
        'status',
        'payment_proof',
        'payment_verified_at',
        'shipped_at',
        'delivered_at',
        'delivery_proof',
        'admin_notes',
        'tracking_number',
        'stock_reduced',
        'stock_reduced_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'payment_verified_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'stock_reduced_at' => 'datetime',
        'subtotal' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'stock_reduced' => 'boolean'
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_PAYMENT_PENDING = 'payment_pending';
    const STATUS_PAYMENT_VERIFIED = 'payment_verified';
    const STATUS_PROCESSING = 'processing';
    const STATUS_PACKAGED = 'packaged';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Get the labels for the order statuses.
     *
     * @return array<string, string>
     */
    public static function getStatusLabels()
    {
        return [
            self::STATUS_PENDING => 'Menunggu Konfirmasi',
            self::STATUS_PAYMENT_PENDING => 'Menunggu Pembayaran',
            self::STATUS_PAYMENT_VERIFIED => 'Pembayaran Terverifikasi',
            self::STATUS_PROCESSING => 'Sedang Disiapkan',
            self::STATUS_PACKAGED => 'Sudah Dikemas',
            self::STATUS_SHIPPED => 'Sedang Dikirim',
            self::STATUS_DELIVERED => 'Sudah Sampai',
            self::STATUS_COMPLETED => 'Selesai',
            self::STATUS_CANCELLED => 'Dibatalkan'
        ];
    }

    /**
     * Get the status label attribute.
     *
     * @return string
     */
    public function getStatusLabelAttribute()
    {
        return self::getStatusLabels()[$this->status] ?? $this->status;
    }

    /**
     * Get the status label.
     *
     * @return string
     */
    public function getStatusLabel()
    {
        return self::getStatusLabels()[$this->status] ?? $this->status;
    }

    /**
     * Get the payment method label attribute.
     *
     * @return string
     */
    public function getPaymentMethodLabelAttribute()
    {
        return $this->payment_method === 'bri' ? 'Bank BRI' : 'DANA E-Wallet';
    }

    /**
     * Get the payment method label.
     *
     * @return string
     */
    public function getPaymentMethodLabel()
    {
        return $this->payment_method === 'bri' ? 'Bank BRI' : 'DANA E-Wallet';
    }

    /**
     * Get the shipping method label attribute.
     *
     * @return string
     */
    public function getShippingMethodLabelAttribute()
    {
        return $this->shipping_method === 'reguler' ? 'Reguler (3-5 hari)' : 'Express (1-2 hari)';
    }

    /**
     * Get the shipping method label.
     *
     * @return string
     */
    public function getShippingMethodLabel()
    {
        return $this->shipping_method === 'reguler' ? 'Reguler (3-5 hari)' : 'Express (1-2 hari)';
    }

    /**
     * Get the user associated with the order.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the items associated with the order.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Generate a unique order number.
     *
     * @return string
     */
    public static function generateOrderNumber()
    {
        $prefix = 'RVZ';
        $date = now()->format('ymd');
        $lastOrder = self::whereDate('created_at', today())
                        ->orderBy('id', 'desc')
                        ->first();
        
        $sequence = $lastOrder ? (int)substr($lastOrder->order_number, -3) + 1 : 1;
        
        return $prefix . $date . str_pad($sequence, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Scope a query to only include orders with a given status.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include recent orders.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}
