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
        $categories = Category::all();

        // 1. [優化] 移除 'chapters' 改用 'latestChapter'
        // 這樣每本書只會 query 一條最新的章節資料，而不是幾千條
        $novels = Novel::with(['category', 'latestChapter'])
            ->when($request->category, function ($query, $categoryId) {
                return $query->where('category_id', $categoryId);
            })
            ->withCount('chapters')
            ->latest('updated_at')
            ->paginate(12);

        $currentCategory = $request->category
            ? Category::find($request->category)
            : null;

        return view('novels.index', compact('novels', 'categories', 'currentCategory'));
    }

    // 修改 show 方法
    public function show(Request $request, $id) // 1. 記得注入 Request
    {
        $novel = Novel::with(['category', 'latestChapter'])
            ->withCount('chapters')
            ->findOrFail($id);

        // 2. 接收排序參數，預設為 'asc' (正序)
        $sort = $request->input('sort', 'asc');

        // 安全性檢查：只允許 'asc' 或 'desc'，避免被亂打
        if (!in_array($sort, ['asc', 'desc'])) {
            $sort = 'asc';
        }

        $chapters = Chapter::select('id', 'novel_id', 'title', 'chapter_num', 'updated_at')
            ->where('novel_id', $id)
            ->orderBy('chapter_num', $sort) // 3. 使用變數進行排序
            ->paginate(100);

        $latestChapter = $novel->latestChapter;

        // 4. 將 $sort 變數傳回 View，以便讓按鈕知道現在是什麼狀態
        return view('novels.show', compact('novel', 'chapters', 'latestChapter', 'sort'));
    }

    public function chapter($novelId, $chapterNum)
    {
        // 這裡不需要載入 category，除非你的閱讀頁有顯示分類
        $novel = Novel::findOrFail($novelId);

        $chapter = Chapter::where('novel_id', $novelId)
            ->where('chapter_num', $chapterNum)
            ->firstOrFail();

        // 3. [效能] 上一章/下一章查詢優化
        // 只需要 ID 和 chapter_num，不需要把整章內容 (content) 都撈出來
        $prevChapter = Chapter::select('chapter_num')
            ->where('novel_id', $novelId)
            ->where('chapter_num', '<', $chapterNum)
            ->orderByDesc('chapter_num')
            ->first();

        $nextChapter = Chapter::select('chapter_num')
            ->where('novel_id', $novelId)
            ->where('chapter_num', '>', $chapterNum)
            ->orderBy('chapter_num')
            ->first();

        // 4. [修正] 移除 $novel->touch(); 
        // 閱讀不應該更新小說的「更新時間」，那應該是「作者發文」時才更新的。
        // 如果你需要統計熱度，應該另外建立一個 views 欄位或一張 novel_views 表。
        $novel->increment('views'); // 假設你有 views 欄位，這樣比較合理

        return view('novels.chapter', compact(
            'novel',
            'chapter',
            'prevChapter',
            'nextChapter'
        ));
    }
}
