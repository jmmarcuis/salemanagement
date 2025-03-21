<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'name',
        'product_type',
        'quantity',
        'price',
        'available'
    ];
    protected $casts = [
        'price' => 'float', // Ensures price is treated as a float
    ];
}