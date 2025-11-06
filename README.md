
# 小說網站 MVP 功能清單（精簡版）
> Laravel 10 + Blade + MySQL  
> 目標：7 天內上線可閱讀原型  

## 1. 使用者系統 ★★★

- [ ] 註冊 / 登入 / 登出（Laravel Breeze）
- [ ] 個人資料編輯（頭像上傳 → Storage::disk('public')）
- [ ] 角色判斷

## 2. 小說管理 ★★★

- [ ] 首頁：最新 20 本 + 熱門 20 本（views_count）
- [ ] 分類瀏覽（/category/{slug}）
- [ ] 搜尋（LIKE title）
- [ ] 小說詳情頁（章節列表 + 分頁）

## 3. 章節閱讀 ★★★

- [ ] 閱讀頁
- [ ] 上一章 / 下一章
- [ ] 閱讀進度本地儲存（localStorage）

## 4. 作者後台 ★★☆
- [ ] 創建小說（表單 + 封面上傳）
- [ ] 新增 / 編輯章節（TinyMCE 編輯器）
- [ ] 章節排序拖曳（SortableJS）

## 5. 書架與追更 ★★☆

- [ ] 一鍵加入書架
- [ ] 個人書架頁（/my/shelf）

## 6. 評論系統 ★★☆

- [ ] 章節底部留言（巢狀 2 層）
- [ ] 即時載入（Livewire 簡易版）

## 7. 排行榜 ★☆☆
- [ ] 週點擊榜（Redis ZINCRBY + Scheduler 每日落 MySQL）
- [ ] 總收藏榜

## 8. 後台管理 ★☆☆
- [ ] Laravel Filament（5 分鐘裝好）
- [ ] 小說審核 + 強制下架
