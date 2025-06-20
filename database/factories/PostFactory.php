<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition()
    {
        return [
            'category_id' => Category::inRandomOrder()->first()->id,
            'user_id' => User::inRandomOrder()->first()->id,
            'title' => $this->faker->unique()->sentence(),
            'slug' => fn(array $attributes) => Str::slug($attributes['title']),
            'content' => $this->faker->paragraphs(5, true),
            'is_published' => $this->faker->boolean(80),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }

    // State untuk post yang published
    public function published()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_published' => true,
            ];
        });
    }

    // State untuk post yang tidak published
    public function unpublished()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_published' => false,
            ];
        });
    }

    // State untuk post dengan kategori tertentu
    public function forCategory($categoryId)
    {
        return $this->state(function (array $attributes) use ($categoryId) {
            return [
                'category_id' => $categoryId,
            ];
        });
    }

    // Method khusus untuk membuat 3 post per kategori
    public function createPostsForEachCategory()
    {
        $categories = Category::all();

        foreach ($categories as $category) {
            // Buat 3 post published untuk kategori ini
            Post::factory()
                ->count(3)
                ->forCategory($category->id)
                ->published()
                ->create();

            // Atau jika ingin variasi published/unpublished
            /*
            Post::factory()
                ->count(2)
                ->forCategory($category->id)
                ->published()
                ->create();
                
            Post::factory()
                ->forCategory($category->id)
                ->unpublished()
                ->create();
            */
        }
    }
}
