<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Novel extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class)->orderBy('chapter_num');
    }

    /**
     * [優化] 高效取得最新的一章 (解決首頁 N+1 問題)
     * 使用 ofMany 可以在 SQL 層級就只取一筆，而不是取出全部再用 PHP 篩選
     */
    public function latestChapter(): HasOne
    {
        return $this->hasOne(Chapter::class)->ofMany('chapter_num', 'max');
        // 或者如果你的 chapter id 是遞增的，也可以用 id
        // return $this->hasOne(Chapter::class)->latestOfMany(); 
    }
}