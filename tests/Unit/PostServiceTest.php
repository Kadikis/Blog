<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use App\Services\PostService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class PostServiceTest extends TestCase
{
    use RefreshDatabase;

    private PostService $postService;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->postService = new PostService();
        $this->user = User::factory()->create();
    }

    public function test_create_post_successfully()
    {
        $request = Request::create('/', 'POST', [
            'title' => 'Test Post Title',
            'body' => 'This is the test post body content.',
            'published_at' => '2024-01-15 10:00:00',
        ]);

        $post = $this->postService->createPost($request, $this->user->id);

        $this->assertInstanceOf(Post::class, $post);
        $this->assertEquals('Test Post Title', $post->title);
        $this->assertEquals('This is the test post body content.', $post->body);
        $this->assertEquals($this->user->id, $post->user_id);
        $this->assertEquals('2024-01-15 10:00:00', $post->published_at->format('Y-m-d H:i:s'));
        $this->assertDatabaseHas('posts', [
            'title' => 'Test Post Title',
            'body' => 'This is the test post body content.',
            'user_id' => $this->user->id,
        ]);
    }

    public function test_create_post_with_categories()
    {
        $categories = Category::factory()->count(3)->create();

        $request = Request::create('/', 'POST', [
            'title' => 'Test Post with Categories',
            'body' => 'This post has categories.',
            'published_at' => '2024-01-15 10:00:00',
            'categories' => $categories->pluck('id')->toArray(),
        ]);

        $post = $this->postService->createPost($request, $this->user->id);

        $this->assertInstanceOf(Post::class, $post);
        $this->assertEquals(3, $post->categories()->count());

        foreach ($categories as $category) {
            $this->assertTrue($post->categories->contains($category));
        }
    }

    public function test_create_post_without_published_date()
    {
        $request = Request::create('/', 'POST', [
            'title' => 'Draft Post',
            'body' => 'This is a draft post.',
        ]);

        $post = $this->postService->createPost($request, $this->user->id);

        $this->assertInstanceOf(Post::class, $post);
        $this->assertNull($post->published_at);
    }

    public function test_create_post_validation_fails_missing_title()
    {
        $request = Request::create('/', 'POST', [
            'body' => 'Post without title.',
        ]);

        $this->expectException(ValidationException::class);
        $this->postService->createPost($request, $this->user->id);
    }

    public function test_create_post_validation_fails_missing_body()
    {
        $request = Request::create('/', 'POST', [
            'title' => 'Post without body',
        ]);

        $this->expectException(ValidationException::class);
        $this->postService->createPost($request, $this->user->id);
    }

    public function test_create_post_validation_fails_invalid_categories()
    {
        $request = Request::create('/', 'POST', [
            'title' => 'Test Post',
            'body' => 'Test body',
            'categories' => [999],
        ]);

        $this->expectException(ValidationException::class);
        $this->postService->createPost($request, $this->user->id);
    }

    public function test_update_post_successfully()
    {
        $post = Post::factory()->create(['user_id' => $this->user->id]);

        $request = Request::create('/', 'PUT', [
            'title' => 'Updated Post Title',
            'body' => 'Updated post body content.',
            'published_at' => '2024-02-15 15:30:00',
        ]);

        $updatedPost = $this->postService->updatePost($request, $post->id);

        $this->assertInstanceOf(Post::class, $updatedPost);
        $this->assertEquals('Updated Post Title', $updatedPost->title);
        $this->assertEquals('Updated post body content.', $updatedPost->body);
        $this->assertEquals('2024-02-15 15:30:00', $updatedPost->published_at->format('Y-m-d H:i:s'));
        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Updated Post Title',
            'body' => 'Updated post body content.',
        ]);
    }

    public function test_update_post_with_categories()
    {
        $post = Post::factory()->create(['user_id' => $this->user->id]);
        $oldCategories = Category::factory()->count(2)->create();
        $newCategories = Category::factory()->count(3)->create();

        // Initially attach old categories
        $post->categories()->attach($oldCategories->pluck('id'));

        $request = Request::create('/', 'PUT', [
            'title' => 'Updated Post with New Categories',
            'body' => 'Updated post body.',
            'categories' => $newCategories->pluck('id')->toArray(),
        ]);

        $updatedPost = $this->postService->updatePost($request, $post->id);

        $this->assertEquals(3, $updatedPost->categories()->count());

        // Check that new categories are attached
        foreach ($newCategories as $category) {
            $this->assertTrue($updatedPost->categories->contains($category));
        }

        // Check that old categories are removed
        foreach ($oldCategories as $category) {
            $this->assertFalse($updatedPost->categories->contains($category));
        }
    }

    public function test_update_post_validation_fails()
    {
        $post = Post::factory()->create(['user_id' => $this->user->id]);

        $request = Request::create('/', 'PUT', [
            'body' => 'Missing title',
        ]);

        $this->expectException(ValidationException::class);
        $this->postService->updatePost($request, $post->id);
    }

    public function test_create_post_with_long_title_validation()
    {
        $request = Request::create('/', 'POST', [
            'title' => str_repeat('a', 256), // Exceeds 255 character limit
            'body' => 'Test body',
        ]);

        $this->expectException(ValidationException::class);
        $this->postService->createPost($request, $this->user->id);
    }

    public function test_create_post_with_invalid_date_format()
    {
        $request = Request::create('/', 'POST', [
            'title' => 'Test Post',
            'body' => 'Test body',
            'published_at' => 'invalid-date-format',
        ]);

        $this->expectException(ValidationException::class);
        $this->postService->createPost($request, $this->user->id);
    }

    public function test_delete_post_successfully()
    {
        $post = Post::factory()->create(['user_id' => $this->user->id]);

        $result = $this->postService->deletePost($post->id);

        $this->assertTrue($result);
        $this->assertSoftDeleted('posts', ['id' => $post->id]);
    }

    public function test_delete_post_returns_false_when_post_not_found()
    {
        $result = $this->postService->deletePost(999); // Non-existent ID

        $this->assertFalse($result);
    }
}
