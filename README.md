# 小說閱讀平台 MVP

[![Laravel Version](https://img.shields.io/badge/Laravel-11.x-red)](https://laravel.com)  

[![License](https://img.shields.io/badge/License-MIT-green)](LICENSE)

## 專案簡介

這是一個使用 Laravel、Blade 和 MySQL 開發的小說閱讀網站的 MVP（Minimum Viable Product）版本。  

專案目標是建立一個簡單卻完整的閱讀平台，讓使用者能瀏覽小說列表、閱讀章節，並擁有基本的互動體驗。  

作為個人學習專案，我從資料庫設計開始，一步步手刻實作核心功能，重點練習 Eloquent 關聯、效能優化（如 N+1 避免）和 Blade 響應式介面。  

目前已上線基本版，未來可擴展會員系統與後台管理。

## 主要功能

1. **小說列表與分類篩選**  

   - 首頁顯示小說卡片（書名、作者、封面、最新章節、更新時間）。  

   - 支援分類篩選（例如玄幻、言情），點擊按鈕即時過濾。

2. **小說詳情頁**  

   - 顯示書本詳情（簡介、狀態、總章節數）。  

   - 章節目錄支援正序/倒序切換與分頁。

3. **閱讀頁面**  

   - 單章內容顯示，支援上一章/下一章翻頁。  

   - 閱讀模式切換（白/暖/夜間）、字體大小調整、鍵盤左右鍵翻頁。  

   - 使用 localStorage 記憶使用者偏好。

4. **效能優化**  

   - 使用 Eloquent 預載關聯，避免 N+1 問題。  

   - 最新章節使用 `hasOne ofMany` 高效查詢。

5. **測試資料生成**  

   - 自製 Factory & Seeder，一鍵產生 20 本小說 + 數千章節假資料。

6. **其他**  

   - 響應式設計（Tailwind CSS CDN）。  

   - 基本路由與控制器邏輯。

## 技術棧

- **後端**：Laravel 11.x（Eloquent ORM、Migration、Seeder、Factory）  

- **前端**：Blade 模板引擎 + Tailwind CSS（CDN 版，無需安裝）  

- **資料庫**：MySQL（3 張表：categories, novels, chapters）  

- **其他**：PHP 8.x, Composer, Artisan CLI  

- **優化重點**：Query Builder 條件式查詢、分頁、關聯預載。

## 安裝與運行

### 先決條件

- PHP >= 8.1  

- Composer  

- MySQL 資料庫  

- Node.js（可選，若需未來擴展 JS）

### 步驟

1. Clone 專案：  

   ```bash

   git clone https://github.com/amu981015/novel.git

   cd novel

   ```

2. 安裝依賴：  

   ```bash

   composer install

   ```

3. 設定環境：  

   - 複製 `.env.example` 為 `.env`  

   - 修改資料庫連線（DB_DATABASE、DB_USERNAME 等）  

   - 生成 App Key：  

     ```bash

     php artisan key:generate

     ```

4. 建表與假資料：  

   ```bash

   php artisan migrate

   php artisan db:seed --class=NovelDatabaseSeeder  # 產生測試資料

   ```

5. 啟動伺服器：  

   ```bash

   php artisan serve

   ```

   - 瀏覽 http://localhost:8000

### 問題排查

- 如果報錯 "No application encryption key"：跑 `php artisan key:generate`。  

- 圖片不出來：跑 `php artisan storage:link`（未來上傳封面用）。

## 使用說明

- 首頁：瀏覽所有小說，點分類篩選。  

- 點書名：進入詳情頁，點章節進入閱讀。  

- 閱讀頁：用按鈕或鍵盤翻頁，切換模式試試！  

- 測試：用 Tinker 插入資料（`php artisan tinker`）。

## 未來計劃

- 加入會員系統（登入、書架、閱讀進度記錄）。  

- 後台管理（新增/編輯小說與章節）。  

- 全站搜尋與熱門推薦。  


---

開發者：Amy (學習中)  

最後更新：2025 年 12 月
