<?php

namespace Tests\Feature\Admin;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TinymceSettingTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $contributor;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->contributor = User::factory()->create(['role' => 'contributor']);
    }

    public function test_admin_can_view_tinymce_settings_page(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.tinymce-settings.index'));

        $response->assertOk()
            ->assertViewIs('admin.tinymce-settings.index');
    }

    public function test_contributor_cannot_view_tinymce_settings_page(): void
    {
        $response = $this->actingAs($this->contributor)
            ->get(route('admin.tinymce-settings.index'));

        $response->assertForbidden();
    }

    public function test_guest_cannot_view_tinymce_settings_page(): void
    {
        $response = $this->get(route('admin.tinymce-settings.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_update_tinymce_api_key(): void
    {
        $apiKey = 'test_api_key_12345';

        $response = $this->actingAs($this->admin)
            ->put(route('admin.tinymce-settings.update'), [
                'tinymce_api_key' => $apiKey,
            ]);

        $response->assertRedirect(route('admin.tinymce-settings.index'))
            ->assertSessionHas('success', 'Pengaturan TinyMCE berhasil disimpan.');

        $this->assertEquals($apiKey, Setting::get('tinymce_api_key'));
    }

    public function test_admin_can_clear_tinymce_api_key(): void
    {
        Setting::set('tinymce_api_key', 'existing_key');

        $response = $this->actingAs($this->admin)
            ->put(route('admin.tinymce-settings.update'), [
                'tinymce_api_key' => '',
            ]);

        $response->assertRedirect(route('admin.tinymce-settings.index'));
        $this->assertEquals('', Setting::get('tinymce_api_key'));
    }

    public function test_tinymce_api_key_must_not_exceed_255_characters(): void
    {
        $longKey = str_repeat('a', 256);

        $response = $this->actingAs($this->admin)
            ->put(route('admin.tinymce-settings.update'), [
                'tinymce_api_key' => $longKey,
            ]);

        $response->assertSessionHasErrors('tinymce_api_key');
    }

    public function test_tinymce_settings_displays_current_api_key(): void
    {
        $apiKey = 'display_test_key';
        Setting::set('tinymce_api_key', $apiKey);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.tinymce-settings.index'));

        $response->assertOk()
            ->assertViewHas('apiKey', $apiKey);
    }

    public function test_contributor_cannot_update_tinymce_settings(): void
    {
        $response = $this->actingAs($this->contributor)
            ->put(route('admin.tinymce-settings.update'), [
                'tinymce_api_key' => 'new_key',
            ]);

        $response->assertForbidden();
    }

    public function test_tinymce_api_key_persists_across_sessions(): void
    {
        $apiKey = 'persistent_key_test';

        $this->actingAs($this->admin)
            ->put(route('admin.tinymce-settings.update'), [
                'tinymce_api_key' => $apiKey,
            ]);

        $this->actingAs($this->admin)
            ->get(route('admin.tinymce-settings.index'))
            ->assertViewHas('apiKey', $apiKey);
    }
}
