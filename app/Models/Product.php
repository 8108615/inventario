<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'sku',
        'barcode',
        'price',
        'category_id',
    ];

    //accesores
    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->images()->count() ? Storage::url($this->images->first()->path) : 'https://dicesabajio.com.mx/wp-content/uploads/2021/06/no-image-300x300.jpeg',
        );
    }

    // Relación uno a muchos inversa
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    //Relacion uno a muchos
    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    //Relacion muchos a muchos polimórfica

    public function purchaseOrder()
    {
        return $this->morphedByMany(PurchaseOrder::class, 'productable');
    }

    public function quotes()
    {
        return $this->morphedByMany(Quote::class, 'productable');
    }

    //Relacion polimórfica
    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
}
