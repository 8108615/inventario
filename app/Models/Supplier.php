<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'identity_id',
        'document_number',
        'name',
        'address',
        'email',
        'phone',
    ];

    //Relación uno a muchos inversa
    public function identity()
    {
        return $this->belongsTo(Identity::class);
    }

    //Relación uno a muchos
    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}
