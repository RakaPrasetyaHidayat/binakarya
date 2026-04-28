<?php

namespace Database\Factories;

use App\Models\Page;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Page>
 */
class PageFactory extends Factory
{
    protected $model = Page::class;

    public function definition(): array
    {
        $title = $this->faker->sentence(3);

        return [
            'title' => $title,
            'slug' => Str::slug($title) . '-' . $this->faker->unique()->numberBetween(1, 99999),
            'content' => '<p>' . $this->faker->paragraph(4) . '</p>',
            'content_mode' => 'classic',
            'content_blocks' => null,
            'custom_css' => null,
            'custom_js' => null,
            'is_published' => $this->faker->boolean(80),
            'meta_description' => $this->faker->optional()->text(120),
        ];
    }
}
