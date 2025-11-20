<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Novel;
use Illuminate\Http\Request;

class NovelController extends Controller
{
    public function index(Request $request)
    {
        // 1. 取得所有分類（給側邊欄）
        $categories = Category::all();

        // 2. 查小說（預載關聯，避免 N+1）
        $novels = Novel::with(['category', 'chapters'])
            ->when($request->category, function ($query, $categoryId) {
                return $query->where('category_id', $categoryId);
            })
            ->withCount('chapters')           // 顯示「共 X 章」
            ->latest('updated_at')           // 最新更新在前
            ->paginate(12);                  // 一頁 12 本

        // 3. 目前選中的分類（給 Blade 高亮）
        $currentCategory = $request->category 
            ? Category::find($request->category) 
            : null;

        return view('novels.index', compact('novels', 'categories', 'currentCategory'));
    }
}