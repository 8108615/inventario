<?php

use App\Models\Customer;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\Quote;
use App\Models\Reason;
use App\Models\Supplier;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/warehouses', function(Request $request){
    return Warehouse::select('id', 'name', 'location as description')
        ->when($request->search, function($query, $search){
            $query->where('name','like', "%{$search}%")
                ->orWhere('location', 'like', "%{$search}%");
        })

        ->when($request->exclude, function($query, $exclude){
            $query->where('id', '!=', $exclude);
        })

        ->when(
            $request->exists('selected'),
            fn ($query) => $query->whereIn('id', $request->input('selected', [])),
            fn ($query) => $query->limit(10)
        )->get();

})->name('api.warehouses.index');


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

Route::post('/customers', function(Request $request){
    return Customer::select('id', 'name')
        ->when($request->search, function($query, $search){
            $query->where('name','like', "%{$search}%")
                ->orWhere('document_number', 'like', "%{$search}%");
        })
        ->when(
            $request->exists('selected'),
            fn ($query) => $query->whereIn('id', $request->input('selected', [])),
            fn ($query) => $query->limit(10)
        )->get();

})->name('api.customers.index');

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

Route::post('purchase-orders', function(Request $request){
    $purchaseOrders = PurchaseOrder::when($request->search, function($query, $search){

            //OC-0001
            $parts = explode('-', $search);

            if(count($parts) == 1){
                //Buscar por nombre de el Proveedor
                $query->whereHas('supplier', function($q) use ($search) {
                    $q->where('name','like', "%{$search}%")
                        ->orwhere('document_number', 'like', "%{$search}%");
                });

                return;
            }

            if(count($parts) == 2){


                $serie = $parts[0];
                $correlative = ltrim($parts[1], '0'); // Eliminar ceros a la izquierda

                $query->where('serie', 'like', "%{$serie}%")
                    ->where('correlative', 'LIKE', "%{$correlative}%");

                return;
            }


        })
        ->when(
            $request->exists('selected'),
            fn ($query) => $query->whereIn('id', $request->input('selected', [])),
            fn ($query) => $query->limit(10)
        )
        ->with(['supplier'])
        ->orderBy('created_at','desc')
        ->get();

    return $purchaseOrders->map(function($purchaseOrder){
        return [
            'id' => $purchaseOrder->id,
            'name' => $purchaseOrder->serie . '-' . $purchaseOrder->correlative,
            'description' => $purchaseOrder->supplier->name . ' - ' . $purchaseOrder->supplier->document_number,
        ];
    });

})->name('api.purchase-orders.index');

Route::post('quotes', function(Request $request){
    $quotes = Quote::when($request->search, function($query, $search){

            //OC-0001
            $parts = explode('-', $search);

            if(count($parts) == 1){
                //Buscar por nombre de el Proveedor
                $query->whereHas('customer', function($q) use ($search) {
                    $q->where('name','like', "%{$search}%")
                        ->orwhere('document_number', 'like', "%{$search}%");
                });

                return;
            }

            if(count($parts) == 2){


                $serie = $parts[0];
                $correlative = ltrim($parts[1], '0'); // Eliminar ceros a la izquierda

                $query->where('serie', 'like', "%{$serie}%")
                    ->where('correlative', 'LIKE', "%{$correlative}%");

                return;
            }


        })
        ->when(
            $request->exists('selected'),
            fn ($query) => $query->whereIn('id', $request->input('selected', [])),
            fn ($query) => $query->limit(10)
        )
        ->with(['customer'])
        ->orderBy('created_at','desc')
        ->get();

    return $quotes->map(function($quote){
        return [
            'id' => $quote->id,
            'name' => $quote->serie . '-' . $quote->correlative,
            'description' => $quote->customer->name . ' - ' . $quote->customer->document_number,
        ];
    });

})->name('api.sales.index');

Route::post('/reasons', function(Request $request){
    return Reason::select('id', 'name')
        ->when($request->search, function($query, $search){
            $query->where('name','like', "%{$search}%");
        })
        ->when(
            $request->exists('selected'),
            fn ($query) => $query->whereIn('id', $request->input('selected', [])),
            fn ($query) => $query->limit(10)
        )
        ->where('type', $request->input('type', ''))
        ->get();

})->name('api.reasons.index');
