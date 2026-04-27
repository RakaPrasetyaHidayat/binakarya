<?php

namespace Tests\Feature\Admin;

use App\Models\Page;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    public function test_admin_can_view_pages_list(): void
    {
        Page::factory()->count(5)->create();

        $response = $this->actingAs($this->admin)
            ->get(route('admin.pages.index'));

        $response->assertOk()
            ->assertViewIs('admin.pages.index')
            ->assertViewHas('pages');
    }

    public function test_guest_cannot_view_pages(): void
    {
        $response = $this->get(route('admin.pages.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_view_create_page_form(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.pages.create'));

        $response->assertOk()
            ->assertViewIs('admin.pages.create');
    }

    public function test_guest_cannot_view_create_page_form(): void
    {
        $response = $this->get(route('admin.pages.create'));
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_create_page(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.pages.store'), [
                'title' => 'About Our Company',
                'content' => 'This is comprehensive company information with at least some meaningful content describing our organization and what we do.',
                'meta_description' => 'Learn more about our company and its mission.',
            ]);

        $response->assertRedirect(route('admin.pages.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('pages', [
            'title' => 'About Our Company',
        ]);
    }

    public function test_page_slug_is_generated_from_title(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.pages.store'), [
                'title' => 'Terms and Conditions',
                'content' => 'This is comprehensive terms and conditions content with meaningful information.',
            ]);

        $page = Page::where('title', 'Terms and Conditions')->first();
        $this->assertEquals('terms-and-conditions', $page->slug);
    }

    public function test_page_title_is_required(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.pages.store'), [
                'content' => 'This is page content.',
            ]);

        $response->assertSessionHasErrors('title');
    }

    public function test_page_content_is_required(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.pages.store'), [
                'title' => 'Page Title',
            ]);

        $response->assertSessionHasErrors('content');
    }

    public function test_page_title_must_not_exceed_255_characters(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.pages.store'), [
                'title' => str_repeat('a', 256),
                'content' => 'This is page content.',
            ]);

        $response->assertSessionHasErrors('title');
    }

    public function test_page_meta_description_must_not_exceed_160_characters(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.pages.store'), [
                'title' => 'Page Title',
                'content' => 'This is page content.',
                'meta_description' => str_repeat('a', 161),
            ]);

        $response->assertSessionHasErrors('meta_description');
    }

    public function test_page_html_content_is_sanitized(): void
    {
        $maliciousContent = 'This is safe content <script>alert("xss")</script> more safe content here.';

        $response = $this->actingAs($this->admin)
            ->post(route('admin.pages.store'), [
                'title' => 'Page With Malicious Content',
                'content' => $maliciousContent,
            ]);

        $response->assertRedirect();

        $page = Page::where('title', 'Page With Malicious Content')->first();
        $this->assertStringNotContainsString('<script>', $page->content);
    }

    public function test_page_can_be_published(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.pages.store'), [
                'title' => 'Published Page',
                'content' => 'This is published page content.',
                'is_published' => true,
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('pages', [
            'title' => 'Published Page',
            'is_published' => true,
        ]);
    }

    public function test_page_defaults_to_unpublished(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.pages.store'), [
                'title' => 'Unpublished Page',
                'content' => 'This is unpublished page content.',
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('pages', [
            'title' => 'Unpublished Page',
            'is_published' => false,
        ]);
    }

    public function test_admin_can_view_edit_page_form(): void
    {
        $page = Page::factory()->create();

        $response = $this->actingAs($this->admin)
            ->get(route('admin.pages.edit', $page));

        $response->assertOk()
            ->assertViewIs('admin.pages.edit')
            ->assertViewHas('page', $page);
    }

    public function test_guest_cannot_view_edit_page_form(): void
    {
        $page = Page::factory()->create();

        $response = $this->get(route('admin.pages.edit', $page));
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_update_page(): void
    {
        $page = Page::factory()->create([
            'title' => 'Original Title',
            'content' => 'Original content here.',
        ]);

        $response = $this->actingAs($this->admin)
            ->put(route('admin.pages.update', $page), [
                'title' => 'Updated Title',
                'content' => 'Updated page content with more information.',
                'meta_description' => 'Updated meta description.',
            ]);

        $response->assertRedirect(route('admin.pages.index'))
            ->assertSessionHas('success');

        $page->refresh();
        $this->assertEquals('Updated Title', $page->title);
        $this->assertEquals('Updated page content with more information.', $page->content);
        $this->assertEquals('Updated meta description.', $page->meta_description);
    }

    public function test_admin_can_update_page_slug_based_on_new_title(): void
    {
        $page = Page::factory()->create(['title' => 'Original Title']);

        $this->actingAs($this->admin)
            ->put(route('admin.pages.update', $page), [
                'title' => 'New Title For Page',
                'content' => 'Updated page content.',
            ]);

        $page->refresh();
        $this->assertEquals('new-title-for-page', $page->slug);
    }

    public function test_admin_can_publish_unpublished_page(): void
    {
        $page = Page::factory()->create(['is_published' => false]);

        $response = $this->actingAs($this->admin)
            ->put(route('admin.pages.update', $page), [
                'title' => $page->title,
                'content' => $page->content,
                'is_published' => true,
            ]);

        $response->assertRedirect();

        $page->refresh();
        $this->assertTrue($page->is_published);
    }

    public function test_admin_can_unpublish_published_page(): void
    {
        $page = Page::factory()->create(['is_published' => true]);

        $response = $this->actingAs($this->admin)
            ->put(route('admin.pages.update', $page), [
                'title' => $page->title,
                'content' => $page->content,
                'is_published' => false,
            ]);

        $response->assertRedirect();

        $page->refresh();
        $this->assertFalse($page->is_published);
    }

    public function test_admin_can_delete_page(): void
    {
        $page = Page::factory()->create();

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.pages.destroy', $page));

        $response->assertRedirect(route('admin.pages.index'))
            ->assertSessionHas('success', 'Page deleted successfully.');

        $this->assertDatabaseMissing('pages', ['id' => $page->id]);
    }

    public function test_guest_cannot_delete_page(): void
    {
        $page = Page::factory()->create();

        $response = $this->delete(route('admin.pages.destroy', $page));
        $response->assertRedirect(route('login'));
    }

    public function test_page_with_all_fields(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.pages.store'), [
                'title' => 'Complete Page Example',
                'content' => 'This is a comprehensive page content with all fields populated properly.',
                'meta_description' => 'Complete page with SEO metadata',
                'is_published' => true,
            ]);

        $response->assertRedirect();

        $page = Page::where('title', 'Complete Page Example')->first();
        $this->assertEquals('complete-page-example', $page->slug);
        $this->assertEquals('This is a comprehensive page content with all fields populated properly.', $page->content);
        $this->assertEquals('Complete page with SEO metadata', $page->meta_description);
        $this->assertTrue($page->is_published);
    }

    public function test_pages_pagination(): void
    {
        Page::factory()->count(25)->create();

        $response = $this->actingAs($this->admin)
            ->get(route('admin.pages.index'));

        $response->assertViewHas('pages');
        $this->assertCount(20, $response->viewData('pages'));
    }

    public function test_update_preserves_other_attributes(): void
    {
        $page = Page::factory()->create([
            'title' => 'Original Title',
            'content' => 'Original content',
            'meta_description' => 'Original meta',
            'is_published' => false,
        ]);

        $this->actingAs($this->admin)
            ->put(route('admin.pages.update', $page), [
                'title' => 'Updated Title',
                'content' => 'Updated content',
            ]);

        $page->refresh();
        $this->assertEquals('Updated Title', $page->title);
        $this->assertEquals('Original meta', $page->meta_description);
        $this->assertFalse($page->is_published);
    }

    public function test_page_content_with_valid_html(): void
    {
        $validHtml = '<p>This is a paragraph</p><strong>Bold text</strong><em>Italic text</em>';

        $this->actingAs($this->admin)
            ->post(route('admin.pages.store'), [
                'title' => 'Page With HTML',
                'content' => $validHtml,
            ]);

        $page = Page::where('title', 'Page With HTML')->first();
        $this->assertNotEmpty($page->content);
        $this->assertStringNotContainsString('<script>', $page->content);
    }
}
