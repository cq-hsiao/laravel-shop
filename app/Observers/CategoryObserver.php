<?php

namespace App\Observers;

use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Support\Facades\Redis;

class CategoryObserver
{
     function saved(Category $category)
    {
        Redis::expire(CategoryService::categoryTree_key,0);
    }

    function deleted(Category $category)
    {
        Redis::expire(CategoryService::categoryTree_key,0);
    }
}
