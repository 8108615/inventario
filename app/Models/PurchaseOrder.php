<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $fillable = [
        'voucher_type',
        'serie',
        'correlative',
        'date',
        'supplier_id',
        'total',
        'observation',
    ];

    //Relación uno a muchos inversa
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    //Relación muchos a muchos polimórfica
    public function products()
    {
        return $this->morphToMany(Product::class, 'productable')
                    ->withPivot('quantity', 'price', 'subtotal')
                    ->withTimestamps();
    }
}
