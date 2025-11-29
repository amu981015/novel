<?php

namespace Database\Factories;

use App\Models\Novel;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChapterFactory extends Factory
{
    public function definition(): array
    {
        return [
            'novel_id'    => Novel::inRandomOrder()->first(),
            'chapter_num' => $this->faker->unique(false), // 同一批會重置，所以下面用 sequence
            'title'       => '第' . $this->faker->numberBetween(1, 999) . '章 ' . $this->faker->realText(20),
            'content'     => $this->generateContent(),
        ];
    }

    // 產生 800～1500 字的假內容
    private function generateContent(): string
    {
        $paragraphs = fake('zh_TW')->paragraphs(rand(25, 45));
        return implode("\n\n", $paragraphs);
    }
}