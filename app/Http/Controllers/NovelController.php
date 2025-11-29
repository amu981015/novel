<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Chapter;
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

    // app/Http/Controllers/NovelController.php  最後面加上

    public function show($id)
    {
        // 預載所有需要的關聯，避免 N+1
        $novel = Novel::with('category')
            ->withCount('chapters')
            ->findOrFail($id);

        // 章節分頁，每頁 100 章（夠用）
        $chapters = Chapter::where('novel_id', $id)
            ->orderBy('chapter_num')
            ->paginate(100);

        // 最新章節（給按鈕用）
        $latestChapter = $novel->chapters()->orderByDesc('chapter_num')->first();

        return view('novels.show', compact('novel', 'chapters', 'latestChapter'));
    }

    // app/Http/Controllers/NovelController.php  再加這段

    public function chapter($novelId, $chapterNum)
    {
        // 1. 先找這本書（預載分類）
        $novel = Novel::with('category')->findOrFail($novelId);

        // 2. 找這一章
        $chapter = Chapter::where('novel_id', $novelId)
            ->where('chapter_num', $chapterNum)
            ->firstOrFail();

        // 3. 上一章、下一章（用 chapter_num 比大小）
        $prevChapter = Chapter::where('novel_id', $novelId)
            ->where('chapter_num', '<', $chapterNum)
            ->orderBy('chapter_num', 'desc')
            ->first();

        $nextChapter = Chapter::where('novel_id', $novelId)
            ->where('chapter_num', '>', $chapterNum)
            ->orderBy('chapter_num', 'asc')
            ->first();

        // 4. 更新小說的 updated_at（讓首頁最新更新排序正確）
        $novel->touch(); // 超好用的一行！

        return view('novels.chapter', compact(
            'novel',
            'chapter',
            'prevChapter',
            'nextChapter'
        ));
    }
}
