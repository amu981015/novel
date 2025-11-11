<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Novel extends Model
{
    use HasFactory;

    protected $guarded = [];

    // 屬於哪個分類
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // 擁有多少章節
    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class)->orderBy('chapter_num');
    }
}