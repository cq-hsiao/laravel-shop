<?php

namespace App\Observers;

use App\Jobs\SyncOneProductToES;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class ProductObserver
{
    function deleted(Product $product)
    {

        app('es')->delete([
            'index' => 'products',
            'id' => $product->id,
        ]);
    }
}
