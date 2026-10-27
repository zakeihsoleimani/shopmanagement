<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    public function customer (): BelongsTo
    {
        return $this->Belongsto(Customer::class,'customer_id');
    }
    public function items():HasMany
    {
     return $this->HasMany(OrderItem::class, 'order_id');
     }
     public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
    protected $fillable = [
        'customer_id',
        'order_number',
        'status',
        'total_amount',
        'address',
    ];
}
