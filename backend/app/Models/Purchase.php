<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'currency_id',
        'amount',
        'bought_at'
    ];

    protected $casts = [
        'bought_at' => 'datetime',
    ];

    protected $with = [
        'store',
        'currency',
        'document'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function store(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function currency(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function document(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Document::class);
    }
}
