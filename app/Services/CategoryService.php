<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Facades\Redis;

class CategoryService
{
    const categoryTree_key = 'category_tree_key';

    // 这是一个递归方法
    // $parentId 参数代表要获取子类目的父类目 ID，null 代表获取所有根类目
    // $allCategories 参数代表数据库中所有的类目，如果是 null 代表需要从数据库中查询
    public function getCategoryTree($parentId = null, $allCategories = null)
    {
        if(is_null($allCategories)){
            // 从数据库中一次性取出所有类目
            $allCategories = Category::all();
        }

        return $allCategories
            // 从所有类目中挑选出父类目 ID 为 $parentId 的类目
            ->where('parent_id',$parentId)
            // 遍历这些类目，并用返回值构建一个新的集合
            ->map(function(Category $category) use ($allCategories){
                $data = ['id' => $category->id, 'name' => $category->name];
                // 如果当前类目不是父类目，则直接返回
                if(!$category->is_directory){
                    return $data;
                }

                // 否则递归调用本方法，将返回值放入 children 字段中
                $data['children'] = $this->getCategoryTree($category->id, $allCategories);

                return $data;
            });
    }

    // 当 Eloquent 模型触发 saved 和 deleted 事件,会触发监听器清除类目树缓存数据  \app\Observers\CategoryObserver.php
    public function getCacheCategoryTree()
    {
        if(!$categoryCache = Redis::get(self::categoryTree_key)){
            $categoryTree = $this->getCategoryTree();
            Redis::setex(self::categoryTree_key,1800,\Opis\Closure\serialize($categoryTree));
        } else {
            $categoryTree = unserialize($categoryCache);
        }

        return $categoryTree;
    }

}