<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'description'
    ];

    // Relación uno a muchos
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
