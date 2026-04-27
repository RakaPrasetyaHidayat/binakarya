<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $contributor;
    protected User $other_contributor;
    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->contributor = User::factory()->create(['role' => 'contributor']);
        $this->other_contributor = User::factory()->create(['role' => 'contributor']);
        
        $this->category = Category::factory()->create(['type' => 'blog']);
    }

    public function test_admin_can_view_posts_list(): void
    {
        Post::factory()->count(5)->create(['is_published' => true]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.posts.index'));

        $response->assertOk()
            ->assertViewIs('admin.posts.index')
            ->assertViewHas('posts');
    }

    public function test_contributor_can_view_own_posts_only(): void
    {
        $own_post = Post::factory()->create(['user_id' => $this->contributor->id]);
        $other_post = Post::factory()->create(['user_id' => $this->other_contributor->id]);

        $response = $this->actingAs($this->contributor)
            ->get(route('admin.posts.index'));

        $response->assertOk();
        $this->assertTrue($response->viewData('posts')->pluck('id')->contains($own_post->id));
        $this->assertFalse($response->viewData('posts')->pluck('id')->contains($other_post->id));
    }

    public function test_admin_can_view_all_posts(): void
    {
        $post1 = Post::factory()->create(['user_id' => $this->contributor->id]);
        $post2 = Post::factory()->create(['user_id' => $this->other_contributor->id]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.posts.index'));

        $response->assertOk();
        $this->assertTrue($response->viewData('posts')->pluck('id')->contains($post1->id));
        $this->assertTrue($response->viewData('posts')->pluck('id')->contains($post2->id));
    }

    public function test_guest_cannot_view_posts(): void
    {
        $response = $this->get(route('admin.posts.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_contributor_can_view_create_post_form(): void
    {
        $response = $this->actingAs($this->contributor)
            ->get(route('admin.posts.create'));

        $response->assertOk()
            ->assertViewIs('admin.posts.form');
    }

    public function test_admin_can_view_create_post_form(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.posts.create'));

        $response->assertOk()
            ->assertViewIs('admin.posts.form');
    }

    public function test_guest_cannot_view_create_post_form(): void
    {
        $response = $this->get(route('admin.posts.create'));
        $response->assertRedirect(route('login'));
    }

    public function test_contributor_can_create_post_without_publishing(): void
    {
        $response = $this->actingAs($this->contributor)
            ->post(route('admin.posts.store'), [
                'title' => 'Test Article Title',
                'category_id' => $this->category->id,
                'excerpt' => 'This is a test excerpt for the article.',
                'body' => 'This is a test body content for the article with at least 50 characters needed.',
                'meta_title' => 'Meta Title',
                'meta_description' => 'Meta Description',
            ]);

        $response->assertRedirect(route('admin.posts.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('posts', [
            'title' => 'Test Article Title',
            'is_published' => false,
            'published_at' => null,
        ]);
    }

    public function test_admin_can_create_and_publish_post_immediately(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.posts.store'), [
                'title' => 'Admin Published Article',
                'category_id' => $this->category->id,
                'excerpt' => 'This is a test excerpt for admin article.',
                'body' => 'This is a test body content with at least 50 characters minimum requirement for the article body.',
                'is_published' => true,
            ]);

        $response->assertRedirect(route('admin.posts.index'));

        $this->assertDatabaseHas('posts', [
            'title' => 'Admin Published Article',
            'is_published' => true,
        ]);

        $post = Post::where('title', 'Admin Published Article')->first();
        $this->assertNotNull($post->published_at);
    }

    public function test_post_slug_is_generated_from_title(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.posts.store'), [
                'title' => 'Test Article With Multiple Words',
                'body' => 'This is a test body content with at least 50 characters minimum requirement for the article body.',
            ]);

        $post = Post::where('title', 'Test Article With Multiple Words')->first();
        $this->assertEquals('test-article-with-multiple-words', $post->slug);
    }

    public function test_post_title_is_required(): void
    {
        $response = $this->actingAs($this->contributor)
            ->post(route('admin.posts.store'), [
                'body' => 'This is a test body content with at least 50 characters minimum requirement for the article body.',
            ]);

        $response->assertSessionHasErrors('title');
    }

    public function test_post_title_must_be_at_least_5_characters(): void
    {
        $response = $this->actingAs($this->contributor)
            ->post(route('admin.posts.store'), [
                'title' => 'Test',
                'body' => 'This is a test body content with at least 50 characters minimum requirement for the article body.',
            ]);

        $response->assertSessionHasErrors('title');
    }

    public function test_post_body_is_required(): void
    {
        $response = $this->actingAs($this->contributor)
            ->post(route('admin.posts.store'), [
                'title' => 'Valid Title For Test',
            ]);

        $response->assertSessionHasErrors('body');
    }

    public function test_post_body_must_be_at_least_50_characters(): void
    {
        $response = $this->actingAs($this->contributor)
            ->post(route('admin.posts.store'), [
                'title' => 'Valid Title For Test',
                'body' => 'Short',
            ]);

        $response->assertSessionHasErrors('body');
    }

    public function test_post_featured_image_is_optional(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.posts.store'), [
                'title' => 'Article Without Image',
                'body' => 'This is a test body content with at least 50 characters minimum requirement for the article body.',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('posts', [
            'title' => 'Article Without Image',
        ]);
    }

    public function test_post_featured_image_must_be_valid_image(): void
    {
        $file = UploadedFile::fake()->create('document.pdf', 1024, 'application/pdf');

        $response = $this->actingAs($this->admin)
            ->post(route('admin.posts.store'), [
                'title' => 'Article With Invalid Image',
                'body' => 'This is a test body content with at least 50 characters minimum requirement for the article body.',
                'featured_image' => $file,
            ]);

        $response->assertSessionHasErrors('featured_image');
    }

    public function test_post_featured_image_must_meet_dimensions(): void
    {
        $file = UploadedFile::fake()->image('featured.png', 400, 400);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.posts.store'), [
                'title' => 'Article With Small Image',
                'body' => 'This is a test body content with at least 50 characters minimum requirement for the article body.',
                'featured_image' => $file,
            ]);

        $response->assertSessionHasErrors('featured_image');
    }

    public function test_post_can_be_created_with_featured_image(): void
    {
        $file = UploadedFile::fake()->image('featured.png', 1200, 800);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.posts.store'), [
                'title' => 'Article With Featured Image',
                'body' => 'This is a test body content with at least 50 characters minimum requirement for the article body.',
                'featured_image' => $file,
            ]);

        $response->assertRedirect();

        $post = Post::where('title', 'Article With Featured Image')->first();
        $this->assertNotNull($post->featured_image);
        Storage::disk('public')->assertExists($post->featured_image);
    }

    public function test_post_html_content_is_sanitized(): void
    {
        $maliciousBody = 'This is safe content <script>alert("xss")</script> more safe content here with extra characters to meet minimum requirement.';

        $response = $this->actingAs($this->admin)
            ->post(route('admin.posts.store'), [
                'title' => 'Article With Malicious Content',
                'body' => $maliciousBody,
            ]);

        $response->assertRedirect();

        $post = Post::where('title', 'Article With Malicious Content')->first();
        $this->assertStringNotContainsString('<script>', $post->body);
    }

    public function test_contributor_can_edit_own_post(): void
    {
        $post = Post::factory()->create(['user_id' => $this->contributor->id]);

        $response = $this->actingAs($this->contributor)
            ->get(route('admin.posts.edit', $post));

        $response->assertOk()
            ->assertViewHas('post', $post);
    }

    public function test_contributor_cannot_edit_others_post(): void
    {
        $post = Post::factory()->create(['user_id' => $this->other_contributor->id]);

        $response = $this->actingAs($this->contributor)
            ->get(route('admin.posts.edit', $post));

        $response->assertForbidden();
    }

    public function test_admin_can_edit_any_post(): void
    {
        $post = Post::factory()->create(['user_id' => $this->contributor->id]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.posts.edit', $post));

        $response->assertOk()
            ->assertViewHas('post', $post);
    }

    public function test_contributor_can_update_own_post(): void
    {
        $post = Post::factory()->create(['user_id' => $this->contributor->id, 'is_published' => false]);

        $response = $this->actingAs($this->contributor)
            ->put(route('admin.posts.update', $post), [
                'title' => 'Updated Title',
                'body' => 'This is an updated body content with at least 50 characters minimum requirement for the article body text.',
            ]);

        $response->assertRedirect();

        $post->refresh();
        $this->assertEquals('Updated Title', $post->title);
    }

    public function test_contributor_cannot_change_published_status(): void
    {
        $post = Post::factory()->create([
            'user_id' => $this->contributor->id,
            'is_published' => false,
        ]);

        $this->actingAs($this->contributor)
            ->put(route('admin.posts.update', $post), [
                'title' => 'Updated Title',
                'body' => 'This is an updated body content with at least 50 characters minimum requirement for the article body text.',
                'is_published' => true,
            ]);

        $post->refresh();
        $this->assertFalse($post->is_published);
    }

    public function test_admin_can_publish_unpublished_post(): void
    {
        $post = Post::factory()->create(['is_published' => false, 'published_at' => null]);

        $response = $this->actingAs($this->admin)
            ->put(route('admin.posts.update', $post), [
                'title' => $post->title,
                'body' => 'This is an updated body content with at least 50 characters minimum requirement for the article body text.',
                'is_published' => true,
            ]);

        $response->assertRedirect();

        $post->refresh();
        $this->assertTrue($post->is_published);
        $this->assertNotNull($post->published_at);
    }

    public function test_contributor_can_delete_own_post(): void
    {
        $post = Post::factory()->create(['user_id' => $this->contributor->id]);
        $postTitle = $post->title;

        $response = $this->actingAs($this->contributor)
            ->delete(route('admin.posts.destroy', $post));

        $response->assertRedirect()
            ->assertSessionHas('success', "Artikel '{$postTitle}' berhasil dihapus.");

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    public function test_contributor_cannot_delete_others_post(): void
    {
        $post = Post::factory()->create(['user_id' => $this->other_contributor->id]);

        $response = $this->actingAs($this->contributor)
            ->delete(route('admin.posts.destroy', $post));

        $response->assertForbidden();
        $this->assertDatabaseHas('posts', ['id' => $post->id]);
    }

    public function test_admin_can_delete_any_post(): void
    {
        $post = Post::factory()->create(['user_id' => $this->contributor->id]);

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.posts.destroy', $post));

        $response->assertRedirect();
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    public function test_post_deletion_removes_images(): void
    {
        $file = UploadedFile::fake()->image('featured.png', 1200, 800);

        $this->actingAs($this->admin)
            ->post(route('admin.posts.store'), [
                'title' => 'Article With Image',
                'body' => 'This is a test body content with at least 50 characters minimum requirement for the article body.',
                'featured_image' => $file,
            ]);

        $post = Post::where('title', 'Article With Image')->first();
        $imagePath = $post->featured_image;

        $this->actingAs($this->admin)
            ->delete(route('admin.posts.destroy', $post));

        Storage::disk('public')->assertMissing($imagePath);
    }

    public function test_post_category_must_exist(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.posts.store'), [
                'title' => 'Article With Invalid Category',
                'category_id' => 99999,
                'body' => 'This is a test body content with at least 50 characters minimum requirement for the article body.',
            ]);

        $response->assertSessionHasErrors('category_id');
    }

    public function test_post_excerpt_is_stripped_of_html(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.posts.store'), [
                'title' => 'Article With HTML Excerpt',
                'body' => 'This is a test body content with at least 50 characters minimum requirement for the article body.',
                'excerpt' => 'This is <strong>an</strong> excerpt with HTML tags.',
            ]);

        $post = Post::where('title', 'Article With HTML Excerpt')->first();
        $this->assertStringNotContainsString('<strong>', $post->excerpt);
    }

    public function test_guest_cannot_create_post(): void
    {
        $response = $this->post(route('admin.posts.store'), [
            'title' => 'Test',
            'body' => 'Test content with at least 50 characters minimum requirement for testing purposes here.',
        ]);

        $response->assertRedirect(route('login'));
    }

    public function test_multiple_posts_pagination(): void
    {
        Post::factory()->count(20)->create(['user_id' => $this->admin->id]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.posts.index'));

        $response->assertViewHas('posts');
        $this->assertCount(15, $response->viewData('posts'));
    }

    public function test_post_with_all_metadata_fields(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.posts.store'), [
                'title' => 'Complete Article Example',
                'category_id' => $this->category->id,
                'excerpt' => 'This is a comprehensive excerpt for SEO purposes in the article.',
                'body' => 'This is a comprehensive body content with at least 50 characters minimum requirement for the article complete testing now.',
                'meta_title' => 'SEO Meta Title for Article',
                'meta_description' => 'SEO Meta Description for article indexing',
            ]);

        $response->assertRedirect();

        $post = Post::where('title', 'Complete Article Example')->first();
        $this->assertEquals('This is a comprehensive excerpt for SEO purposes in the article.', $post->excerpt);
        $this->assertEquals('SEO Meta Title for Article', $post->meta_title);
        $this->assertEquals('SEO Meta Description for article indexing', $post->meta_description);
    }
}
