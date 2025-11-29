<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Chapter;
use App\Models\Novel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NovelDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 解決方案 1：關掉外鍵檢查 → 直接 truncate → 再開回去
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        Chapter::truncate();
        Novel::truncate();
        Category::truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 解決方案 2：先寫死 10 個分類（不會重複 + 保證有資料）
        $categories = ['玄幻', '仙俠', '都市', '歷史', '科幻', '遊戲', '言情', '懸疑', '恐怖', '同人'];
        foreach ($categories as $name) {
            Category::create(['name' => $name]);
        }

        // 產生 20 本書
        Novel::factory(20)->create()->each(function ($novel) {
            $totalChapters = rand(80, 200);

            // 用 sequence 讓 chapter_num 從 1 開始遞增（最穩）
            Chapter::factory()->count($totalChapters)->sequence(fn ($sequence) => [
                'novel_id'    => $novel->id,
                'chapter_num' => $sequence->index + 1,
            ])->create();

            // 隨機更新時間
            $novel->updated_at = now()->subDays(rand(0, 30));
            $novel->saveQuietly();
        });
    }
}