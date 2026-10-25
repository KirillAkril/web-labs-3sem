<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleFactory extends Factory
{
    public function definition(): array
    {
        $titles = [
            'Новые технологии в современном мире',
            'Как развиваются компьютерные системы',
            'Интересные факты о животных',
            'Будущее искусственного интеллекта',
            'Самые быстрые автомобили',
            'Как создаются современные сайты',
            'История развития интернета',
            'Почему важно изучать программирование',
            'Удивительные открытия науки',
            'Технологии, меняющие нашу жизнь',
        ];

        $title = fake()->randomElement($titles);

        return [
            'title' => $title,
            'description' => fake('ru_RU')->realText(180),
            'content' => fake('ru_RU')->realText(1000),
            'image' => null,
        ];
    }
}
