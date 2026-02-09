<?php

use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/suppliers', function(Request $request){
    return Supplier::select('id', 'name')
        ->when($request->search, function($query, $search){
            $query->where('name','like', "%{$search}%")
                ->orWhere('document_number', 'like', "%{$search}%");
        })
        ->when(
            $request->exists('selected'),
            fn ($query) => $query->whereIn('id', $request->input('selected', [])),
            fn ($query) => $query->limit(10)
        )->get();

})->name('api.suppliers.index');

Route::post('/products', function(Request $request){
    return Product::select('id', 'name')
        ->when($request->search, function($query, $search){
            $query->where('name','like', "%{$search}%")
                ->orWhere('sku', 'like', "%{$search}%");
        })
        ->when(
            $request->exists('selected'),
            fn ($query) => $query->whereIn('id', $request->input('selected', [])),
            fn ($query) => $query->limit(10)
        )->get();

})->name('api.products.index');

Route::get('purchase-orders', function(Request $request){
    $purchaseOrders = PurchaseOrder::when($request->search, function($query, $search){

            //OC-0001
            $parts = explode('-', $search);

            if(count($parts) !== 2){
                return;
            }

            $serie = $parts[0];
            $correlative = ltrim($parts[1], '0'); // Eliminar ceros a la izquierda

            $query->where('serie', 'like', "%{$serie}%")
                ->where('correlative', 'LIKE', "%{$correlative}%");


        })
        ->when(
            $request->exists('selected'),
            fn ($query) => $query->whereIn('id', $request->input('selected', [])),
            fn ($query) => $query->limit(10)
        )
        ->with(['supplier'])
        ->get();

    return $purchaseOrders->map(function($purchaseOrder){
        return [
            'id' => $purchaseOrder->id,
            'name' => $purchaseOrder->serie . '-' . $purchaseOrder->correlative,
            'description' => $purchaseOrder->supplier->name,
        ];
    });

})->name('api.purchase-orders.index');
