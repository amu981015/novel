<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement([
                '玄幻', '仙俠', '都市', '歷史', '科幻', '遊戲', '言情', '懸疑', '恐怖', '同人'
            ]),
        ];
    }
}