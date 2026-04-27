<?php

namespace Tests\Feature\Admin;

use App\Models\MailSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class MailSettingTest extends TestCase
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

    public function test_admin_can_view_mail_settings_page(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.mail-settings.index'));

        $response->assertOk()
            ->assertViewIs('admin.mail-settings.index');
    }

    public function test_contributor_cannot_view_mail_settings_page(): void
    {
        $response = $this->actingAs($this->contributor)
            ->get(route('admin.mail-settings.index'));

        $response->assertForbidden();
    }

    public function test_guest_cannot_view_mail_settings_page(): void
    {
        $response = $this->get(route('admin.mail-settings.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_update_smtp_settings(): void
    {
        $response = $this->actingAs($this->admin)
            ->put(route('admin.mail-settings.update'), [
                'driver' => 'smtp',
                'host' => 'smtp.mailtrap.io',
                'port' => 2525,
                'encryption' => 'tls',
                'username' => 'test_username',
                'password' => 'test_password',
                'from_address' => 'admin@example.com',
                'from_name' => 'Bina Karya Cendekia',
                'is_active' => true,
            ]);

        $response->assertRedirect(route('admin.mail-settings.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('mail_settings', [
            'driver' => 'smtp',
            'host' => 'smtp.mailtrap.io',
            'port' => 2525,
            'from_address' => 'admin@example.com',
        ]);
    }

    public function test_admin_can_update_mailketing_settings(): void
    {
        $response = $this->actingAs($this->admin)
            ->put(route('admin.mail-settings.update'), [
                'driver' => 'mailketing',
                'mailketing_api_key' => 'test_mailketing_key_12345',
                'mailketing_sender_email' => 'sender@example.com',
                'from_address' => 'admin@example.com',
                'from_name' => 'Bina Karya Cendekia',
                'is_active' => true,
            ]);

        $response->assertRedirect(route('admin.mail-settings.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('mail_settings', [
            'driver' => 'mailketing',
            'mailketing_api_key' => 'test_mailketing_key_12345',
            'from_address' => 'admin@example.com',
        ]);
    }

    public function test_from_address_must_be_valid_email(): void
    {
        $response = $this->actingAs($this->admin)
            ->put(route('admin.mail-settings.update'), [
                'driver' => 'smtp',
                'from_address' => 'invalid-email',
                'from_name' => 'Test',
            ]);

        $response->assertSessionHasErrors('from_address');
    }

    public function test_from_name_is_required(): void
    {
        $response = $this->actingAs($this->admin)
            ->put(route('admin.mail-settings.update'), [
                'driver' => 'smtp',
                'from_address' => 'admin@example.com',
                'from_name' => '',
            ]);

        $response->assertSessionHasErrors('from_name');
    }

    public function test_driver_must_be_valid(): void
    {
        $response = $this->actingAs($this->admin)
            ->put(route('admin.mail-settings.update'), [
                'driver' => 'invalid_driver',
                'from_address' => 'admin@example.com',
                'from_name' => 'Test',
            ]);

        $response->assertSessionHasErrors('driver');
    }

    public function test_port_must_be_integer(): void
    {
        $response = $this->actingAs($this->admin)
            ->put(route('admin.mail-settings.update'), [
                'driver' => 'smtp',
                'port' => 'not_an_integer',
                'from_address' => 'admin@example.com',
                'from_name' => 'Test',
            ]);

        $response->assertSessionHasErrors('port');
    }

    public function test_encryption_must_be_valid(): void
    {
        $response = $this->actingAs($this->admin)
            ->put(route('admin.mail-settings.update'), [
                'driver' => 'smtp',
                'encryption' => 'invalid_encryption',
                'from_address' => 'admin@example.com',
                'from_name' => 'Test',
            ]);

        $response->assertSessionHasErrors('encryption');
    }

    public function test_recipient_email_must_be_valid_if_provided(): void
    {
        $response = $this->actingAs($this->admin)
            ->put(route('admin.mail-settings.update'), [
                'driver' => 'smtp',
                'recipient_email' => 'invalid-email',
                'from_address' => 'admin@example.com',
                'from_name' => 'Test',
            ]);

        $response->assertSessionHasErrors('recipient_email');
    }

    public function test_test_email_requires_email_address(): void
    {
        MailSetting::create([
            'driver' => 'smtp',
            'from_address' => 'admin@example.com',
            'from_name' => 'Test',
        ]);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.mail-settings.test'), [
                'test_email' => '',
            ]);

        $response->assertSessionHasErrors('test_email');
    }

    public function test_test_email_must_be_valid_email(): void
    {
        MailSetting::create([
            'driver' => 'smtp',
            'from_address' => 'admin@example.com',
            'from_name' => 'Test',
        ]);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.mail-settings.test'), [
                'test_email' => 'invalid-email',
            ]);

        $response->assertSessionHasErrors('test_email');
    }

    public function test_test_email_fails_without_settings(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.mail-settings.test'), [
                'test_email' => 'test@example.com',
            ]);

        $response->assertSessionHas('error', 'Silakan simpan pengaturan terlebih dahulu.');
    }

    public function test_admin_can_test_mailketing_connection(): void
    {
        MailSetting::create([
            'driver' => 'mailketing',
            'mailketing_api_key' => 'test_key_123',
            'mailketing_sender_email' => 'sender@example.com',
            'from_address' => 'admin@example.com',
            'from_name' => 'Test',
        ]);

        Http::fake([
            'https://api.mailketing.co.id/api/v1/send' => Http::response(
                ['status' => 'success', 'message' => 'Email sent'],
                200
            ),
        ]);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.mail-settings.test'), [
                'test_email' => 'recipient@example.com',
            ]);

        $response->assertSessionHas('success', 'Email tes berhasil dikirim ke recipient@example.com');

        Http::assertSent(function ($request) {
            return $request->url() === 'https://api.mailketing.co.id/api/v1/send'
                && $request->data()['api_token'] === 'test_key_123'
                && $request->data()['recipient'] === 'recipient@example.com';
        });
    }

    public function test_mailketing_test_fails_with_invalid_response(): void
    {
        MailSetting::create([
            'driver' => 'mailketing',
            'mailketing_api_key' => 'test_key_123',
            'mailketing_sender_email' => 'sender@example.com',
            'from_address' => 'admin@example.com',
            'from_name' => 'Test',
        ]);

        Http::fake([
            'https://api.mailketing.co.id/api/v1/send' => Http::response(
                ['status' => 'error', 'response' => 'Invalid API key'],
                200
            ),
        ]);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.mail-settings.test'), [
                'test_email' => 'recipient@example.com',
            ]);

        $response->assertSessionHas('error');
        $this->assertStringContainsString('Mailketing Gagal', session('error'));
    }

    public function test_mailketing_api_key_is_hidden_from_response(): void
    {
        MailSetting::create([
            'driver' => 'mailketing',
            'mailketing_api_key' => 'secret_key_12345',
            'from_address' => 'admin@example.com',
            'from_name' => 'Test',
        ]);

        $settings = MailSetting::first();
        $this->assertNotEquals('secret_key_12345', $settings->toArray()['mailketing_api_key'] ?? null);
    }

    public function test_contributor_cannot_update_mail_settings(): void
    {
        $response = $this->actingAs($this->contributor)
            ->put(route('admin.mail-settings.update'), [
                'driver' => 'smtp',
                'from_address' => 'admin@example.com',
                'from_name' => 'Test',
            ]);

        $response->assertForbidden();
    }

    public function test_contributor_cannot_test_mail_settings(): void
    {
        $response = $this->actingAs($this->contributor)
            ->post(route('admin.mail-settings.test'), [
                'test_email' => 'test@example.com',
            ]);

        $response->assertForbidden();
    }

    public function test_is_active_flag_defaults_to_false(): void
    {
        $response = $this->actingAs($this->admin)
            ->put(route('admin.mail-settings.update'), [
                'driver' => 'smtp',
                'from_address' => 'admin@example.com',
                'from_name' => 'Test',
            ]);

        $this->assertDatabaseHas('mail_settings', [
            'is_active' => false,
        ]);
    }

    public function test_multiple_drivers_can_be_configured(): void
    {
        $smtpResponse = $this->actingAs($this->admin)
            ->put(route('admin.mail-settings.update'), [
                'driver' => 'smtp',
                'host' => 'smtp.test.com',
                'from_address' => 'admin@example.com',
                'from_name' => 'Test',
            ]);

        $smtpResponse->assertRedirect();

        $mailketingResponse = $this->actingAs($this->admin)
            ->put(route('admin.mail-settings.update'), [
                'driver' => 'mailketing',
                'mailketing_api_key' => 'new_key',
                'from_address' => 'admin@example.com',
                'from_name' => 'Test',
            ]);

        $mailketingResponse->assertRedirect();

        $this->assertDatabaseHas('mail_settings', [
            'driver' => 'mailketing',
        ]);
    }
}
