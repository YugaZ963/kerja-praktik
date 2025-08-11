<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'user_id',
        'customer_name',
        'customer_phone',
        'customer_address',
        'notes',
        'payment_method',
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
        'tracking_number'
    ];

    protected $casts = [
        'payment_verified_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'subtotal' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'total_amount' => 'decimal:2'
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

    public function getStatusLabelAttribute()
    {
        return self::getStatusLabels()[$this->status] ?? $this->status;
    }

    public function getStatusLabel()
    {
        return self::getStatusLabels()[$this->status] ?? $this->status;
    }

    public function getPaymentMethodLabelAttribute()
    {
        return $this->payment_method === 'bri' ? 'Bank BRI' : 'DANA E-Wallet';
    }

    public function getPaymentMethodLabel()
    {
        return $this->payment_method === 'bri' ? 'Bank BRI' : 'DANA E-Wallet';
    }

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // Generate order number
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

    // Scope methods
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}
