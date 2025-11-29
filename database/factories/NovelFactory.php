<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class NovelFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title'       => fake('zh_TW')->realText(15, 2), // 中文書名
            'author'      => fake('zh_TW')->name(),
            'intro'       => fake('zh_TW')->paragraphs(3, true),
            'cover'       => null, // 先留空，之後再教你放圖
            'category_id' => Category::inRandomOrder()->first() ?? Category::factory(),
            'status'      => fake()->randomElement([0, 0, 0, 1]), // 80% 連載中
        ];
    }
}