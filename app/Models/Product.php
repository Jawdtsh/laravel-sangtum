<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Product extends Model
{
    use HasFactory;
    protected $appends = ['created_form'];
    protected $fillable = [
        'name',
        'description',
        'price',
        'quantity',
        'status',
        'weight',
        'dimensions',
    ];

    public function getCreatedFormAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }



}
