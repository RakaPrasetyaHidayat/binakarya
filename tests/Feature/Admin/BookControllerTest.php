<?php

namespace Tests\Feature\Admin;

use App\Models\Book;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BookControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $contributor;
    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');

        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->contributor = User::factory()->create(['role' => 'contributor']);

        $this->category = Category::factory()->create(['type' => 'book']);
    }

    public function test_admin_can_view_books_list(): void
    {
        Book::factory()->count(5)->create();

        $response = $this->actingAs($this->admin)
            ->get(route('admin.books.index'));

        $response->assertOk()
            ->assertViewIs('admin.books.index')
            ->assertViewHas('books');
    }

    public function test_guest_cannot_view_books(): void
    {
        $response = $this->get(route('admin.books.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_view_create_book_form(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.books.create'));

        $response->assertOk()
            ->assertViewIs('admin.books.form');
    }

    public function test_guest_cannot_view_create_book_form(): void
    {
        $response = $this->get(route('admin.books.create'));
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_create_basic_book(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.books.store'), [
                'title' => 'Introduction to Laravel',
                'author' => 'John Doe',
                'category_id' => $this->category->id,
            ]);

        $response->assertRedirect(route('admin.books.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('books', [
            'title' => 'Introduction to Laravel',
            'author' => 'John Doe',
        ]);
    }

    public function test_book_slug_is_generated_from_title(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.books.store'), [
                'title' => 'Advanced PHP Development',
                'author' => 'Jane Smith',
            ]);

        $book = Book::where('title', 'Advanced PHP Development')->first();
        $this->assertEquals('advanced-php-development', $book->slug);
    }

    public function test_book_title_is_required(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.books.store'), [
                'author' => 'John Doe',
            ]);

        $response->assertSessionHasErrors('title');
    }

    public function test_book_title_must_be_at_least_3_characters(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.books.store'), [
                'title' => 'AB',
                'author' => 'John Doe',
            ]);

        $response->assertSessionHasErrors('title');
    }

    public function test_book_author_is_required(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.books.store'), [
                'title' => 'Book Title',
            ]);

        $response->assertSessionHasErrors('author');
    }

    public function test_book_author_must_be_at_least_3_characters(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.books.store'), [
                'title' => 'Book Title',
                'author' => 'JD',
            ]);

        $response->assertSessionHasErrors('author');
    }

    public function test_book_isbn_format_validation(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.books.store'), [
                'title' => 'Book Title',
                'author' => 'John Doe',
                'isbn' => 'invalid-isbn',
            ]);

        $response->assertSessionHasErrors('isbn');
    }

    public function test_book_valid_isbn_format(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.books.store'), [
                'title' => 'Book Title',
                'author' => 'John Doe',
                'isbn' => '978-0-306-40615-2',
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('books', [
            'isbn' => '978-0-306-40615-2',
        ]);
    }

    public function test_book_doi_format_validation(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.books.store'), [
                'title' => 'Book Title',
                'author' => 'John Doe',
                'doi' => 'invalid-doi',
            ]);

        $response->assertSessionHasErrors('doi');
    }

    public function test_book_valid_doi_format(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.books.store'), [
                'title' => 'Book Title',
                'author' => 'John Doe',
                'doi' => '10.1234/example.doi.123',
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('books', [
            'doi' => '10.1234/example.doi.123',
        ]);
    }

    public function test_book_published_year_must_be_valid(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.books.store'), [
                'title' => 'Book Title',
                'author' => 'John Doe',
                'published_year' => 1800,
            ]);

        $response->assertSessionHasErrors('published_year');
    }

    public function test_book_published_year_must_not_be_future(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.books.store'), [
                'title' => 'Book Title',
                'author' => 'John Doe',
                'published_year' => date('Y') + 1,
            ]);

        $response->assertSessionHasErrors('published_year');
    }

    public function test_book_valid_published_year(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.books.store'), [
                'title' => 'Book Title',
                'author' => 'John Doe',
                'published_year' => 2020,
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('books', [
            'published_year' => 2020,
        ]);
    }

    public function test_book_abstract_minimum_length(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.books.store'), [
                'title' => 'Book Title',
                'author' => 'John Doe',
                'abstract' => 'Too short',
            ]);

        $response->assertSessionHasErrors('abstract');
    }

    public function test_book_abstract_sanitized(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.books.store'), [
                'title' => 'Book Title',
                'author' => 'John Doe',
                'abstract' => 'This is a valid abstract <script>alert("xss")</script> with at least 10 characters required by validation rules here.',
            ]);

        $book = Book::where('title', 'Book Title')->first();
        $this->assertStringNotContainsString('<script>', $book->abstract);
    }

    public function test_book_can_have_cover_image(): void
    {
        $file = UploadedFile::fake()->image('cover.png', 600, 900);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.books.store'), [
                'title' => 'Book With Cover',
                'author' => 'John Doe',
                'cover_image' => $file,
            ]);

        $response->assertRedirect();

        $book = Book::where('title', 'Book With Cover')->first();
        $this->assertNotNull($book->cover_image);
        Storage::disk('public')->assertExists($book->cover_image);
    }

    public function test_book_cover_image_must_be_valid_image(): void
    {
        $file = UploadedFile::fake()->create('document.pdf', 1024, 'application/pdf');

        $response = $this->actingAs($this->admin)
            ->post(route('admin.books.store'), [
                'title' => 'Book With Invalid Cover',
                'author' => 'John Doe',
                'cover_image' => $file,
            ]);

        $response->assertSessionHasErrors('cover_image');
    }

    public function test_book_can_have_pdf_file(): void
    {
        $file = UploadedFile::fake()->create('book.pdf', 10240, 'application/pdf');

        $response = $this->actingAs($this->admin)
            ->post(route('admin.books.store'), [
                'title' => 'Book With PDF',
                'author' => 'John Doe',
                'pdf_file' => $file,
            ]);

        $response->assertRedirect();

        $book = Book::where('title', 'Book With PDF')->first();
        $this->assertNotNull($book->pdf_file);
        Storage::disk('public')->assertExists($book->pdf_file);
    }

    public function test_book_pdf_file_must_be_pdf(): void
    {
        $file = UploadedFile::fake()->create('notpdf.txt', 1024, 'text/plain');

        $response = $this->actingAs($this->admin)
            ->post(route('admin.books.store'), [
                'title' => 'Book With Invalid PDF',
                'author' => 'John Doe',
                'pdf_file' => $file,
            ]);

        $response->assertSessionHasErrors('pdf_file');
    }

    public function test_book_can_have_preview_file(): void
    {
        $file = UploadedFile::fake()->create('preview.pdf', 5120, 'application/pdf');

        $response = $this->actingAs($this->admin)
            ->post(route('admin.books.store'), [
                'title' => 'Book With Preview',
                'author' => 'John Doe',
                'preview_file' => $file,
            ]);

        $response->assertRedirect();

        $book = Book::where('title', 'Book With Preview')->first();
        $this->assertNotNull($book->preview_file);
    }

    public function test_book_with_external_urls(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.books.store'), [
                'title' => 'Book Title',
                'author' => 'John Doe',
                'preview_url' => 'https://example.com/preview',
                'shopee_url' => 'https://www.shopee.co.id/product-123',
                'tokopedia_url' => 'https://www.tokopedia.com/product-123',
                'custom_url' => 'https://example.com/custom',
                'custom_url_label' => 'Buy Now',
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('books', [
            'preview_url' => 'https://example.com/preview',
            'shopee_url' => 'https://www.shopee.co.id/product-123',
        ]);
    }

    public function test_book_shopee_url_format_validation(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.books.store'), [
                'title' => 'Book Title',
                'author' => 'John Doe',
                'shopee_url' => 'https://invalid-shopee.com/product',
            ]);

        $response->assertSessionHasErrors('shopee_url');
    }

    public function test_book_tokopedia_url_format_validation(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.books.store'), [
                'title' => 'Book Title',
                'author' => 'John Doe',
                'tokopedia_url' => 'https://invalid-tokopedia.com/product',
            ]);

        $response->assertSessionHasErrors('tokopedia_url');
    }

    public function test_book_wa_number_format_validation(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.books.store'), [
                'title' => 'Book Title',
                'author' => 'John Doe',
                'wa_number' => '123',
            ]);

        $response->assertSessionHasErrors('wa_number');
    }

    public function test_book_valid_wa_number(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.books.store'), [
                'title' => 'Book Title',
                'author' => 'John Doe',
                'wa_number' => '081234567890',
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('books', [
            'wa_number' => '081234567890',
        ]);
    }

    public function test_book_price_validation(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.books.store'), [
                'title' => 'Book Title',
                'author' => 'John Doe',
                'price' => -100,
            ]);

        $response->assertSessionHasErrors('price');
    }

    public function test_book_valid_price(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.books.store'), [
                'title' => 'Book Title',
                'author' => 'John Doe',
                'price' => 150000.99,
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('books', [
            'price' => 150000.99,
        ]);
    }

    public function test_admin_can_edit_book(): void
    {
        $book = Book::factory()->create(['author' => 'Original Author']);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.books.edit', $book));

        $response->assertOk()
            ->assertViewHas('book', $book);
    }

    public function test_admin_can_update_book(): void
    {
        $book = Book::factory()->create(['title' => 'Original Title']);

        $response = $this->actingAs($this->admin)
            ->put(route('admin.books.update', $book), [
                'title' => 'Updated Title',
                'author' => 'Updated Author',
                'published_year' => 2023,
            ]);

        $response->assertRedirect();

        $book->refresh();
        $this->assertEquals('Updated Title', $book->title);
        $this->assertEquals('Updated Author', $book->author);
    }

    public function test_admin_can_delete_book(): void
    {
        $book = Book::factory()->create();

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.books.destroy', $book));

        $response->assertRedirect();

        $this->assertDatabaseMissing('books', ['id' => $book->id]);
    }

    public function test_book_deletion_removes_files(): void
    {
        $coverFile = UploadedFile::fake()->image('cover.png', 600, 900);
        $pdfFile = UploadedFile::fake()->create('book.pdf', 10240, 'application/pdf');

        $this->actingAs($this->admin)
            ->post(route('admin.books.store'), [
                'title' => 'Book With Files',
                'author' => 'John Doe',
                'cover_image' => $coverFile,
                'pdf_file' => $pdfFile,
            ]);

        $book = Book::where('title', 'Book With Files')->first();
        $coverPath = $book->cover_image;
        $pdfPath = $book->pdf_file;

        $this->actingAs($this->admin)
            ->delete(route('admin.books.destroy', $book));

        Storage::disk('public')->assertMissing($coverPath);
        Storage::disk('public')->assertMissing($pdfPath);
    }

    public function test_book_can_be_published(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.books.store'), [
                'title' => 'Published Book',
                'author' => 'John Doe',
                'is_published' => true,
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('books', [
            'title' => 'Published Book',
            'is_published' => true,
        ]);
    }

    public function test_book_defaults_to_unpublished(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.books.store'), [
                'title' => 'Unpublished Book',
                'author' => 'John Doe',
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('books', [
            'title' => 'Unpublished Book',
            'is_published' => false,
        ]);
    }

    public function test_book_with_all_metadata_fields(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.books.store'), [
                'title' => 'Complete Book Example',
                'author' => 'Complete Author',
                'category_id' => $this->category->id,
                'isbn' => '978-0-306-40615-2',
                'doi' => '10.1234/example.doi.456',
                'published_year' => 2022,
                'abstract' => 'This is a comprehensive abstract for the book example with proper length requirements for validation purposes.',
                'keywords' => 'laravel,php,web development,framework',
                'edition' => 'Second Edition',
                'price' => 299999.99,
            ]);

        $response->assertRedirect();

        $book = Book::where('title', 'Complete Book Example')->first();
        $this->assertNotNull($book);
        $this->assertEquals('Complete Author', $book->author);
        $this->assertEquals('978-0-306-40615-2', $book->isbn);
        $this->assertEquals(299999.99, $book->price);
    }

    public function test_books_pagination(): void
    {
        Book::factory()->count(20)->create();

        $response = $this->actingAs($this->admin)
            ->get(route('admin.books.index'));

        $response->assertViewHas('books');
        $this->assertCount(15, $response->viewData('books'));
    }
}
