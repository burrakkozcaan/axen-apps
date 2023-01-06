<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $fillable = [
        'title',
        'slug',
        'content',
        'image',
        'quantity',
        'price',
        'status',
        'category_id',

    ];


    protected $table = 'products';

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

}
