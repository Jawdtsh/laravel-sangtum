<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'product_id',
        'quantity',
        'price',
    ];




    public function products(): BelongsToMany {
        return $this->belongsToMany(Product::class)->withPivot('quantity')->withTimestamps();
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function card(): HasOne
    {
        return $this->hasOne(Card::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
