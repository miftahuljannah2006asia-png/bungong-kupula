<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'code', 'name', 'phone', 'city', 'address', 'notes',
        'total', 'status', 'ip',
    ];

    protected function casts(): array
    {
        return [
            'total' => 'integer',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /** Ringkasan satu baris untuk daftar pesanan / WhatsApp. */
    public function summary(): string
    {
        return $this->items
            ->map(fn (OrderItem $i) => "{$i->qty}x {$i->title}")
            ->implode('; ');
    }
}
