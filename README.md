# 小說網站 MVP 功能清單（精簡版）
> Laravel 10 + Blade + MySQL  
> 目標：7 天內上線可閱讀原型  

## 1. 使用者系統 ★★★

- [x] 註冊 / 登入 / 登出（Laravel Breeze）
- [x] 第三方登入（Socialite：Google）
- [x] 個人資料編輯（頭像上傳 → Storage::disk('public')）
- [x] 角色判斷（Gate + middleware:auth,author）

## 2. 小說管理 ★★★

- [x] 首頁：最新 20 本 + 熱門 20 本（views_count）
- [x] 分類瀏覽（/category/{slug}）
- [x] 搜尋（LIKE title）
- [x] 小說詳情頁（章節列表 + 分頁）

## 3. 章節閱讀 ★★★

- [x] 閱讀頁（/novel/{novel}/{chapter}）
- [x] 上一章 / 下一章（JS + Laravel route）
- [x] 閱讀進度本地儲存（localStorage）
- [x] 夜間模式切換（CSS class toggle）

## 4. 作者後台 ★★☆
- [x] 創建小說（表單 + 封面上傳）
- [x] 新增 / 編輯章節（TinyMCE 編輯器）
- [x] 章節排序拖曳（SortableJS）

## 5. 書架與追更 ★★☆

- [x] 一鍵加入書架
- [x] 個人書架頁（/my/shelf）
- [x] 新章推送（站內小紅點）

## 6. 評論系統 ★★☆

- [x] 章節底部留言（巢狀 2 層）
- [x] 即時載入（Livewire 簡易版）

## 7. 排行榜 ★☆☆
- [x] 週點擊榜（Redis ZINCRBY + Scheduler 每日落 MySQL）
- [x] 總收藏榜

## 8. 後台管理 ★☆☆
- [x] Laravel Filament（5 分鐘裝好）
- [x] 小說審核 + 強制下架
