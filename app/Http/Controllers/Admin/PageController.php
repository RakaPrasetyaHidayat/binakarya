<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PageController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Page::class);
        $pages = Page::latest()->paginate(20);
        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        $this->authorize('create', Page::class);
        return view('admin.pages.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Page::class);
        $validated = $this->validatePage($request);

        $validated['slug'] = Str::slug($request->title);
        $validated['is_published'] = $request->has('is_published');
        $validated = $this->sanitizePayload($validated);

        Page::create($validated);

        return redirect()->route('admin.pages.index')->with('success', 'Page created successfully.');
    }

    public function edit(Page $page)
    {
        $this->authorize('update', $page);
        return view('admin.pages.edit', compact('page'));
    }

    public function show(Page $page)
    {
        $this->authorize('view', $page);
        return redirect()->route('admin.pages.edit', $page);
    }

    public function update(Request $request, Page $page)
    {
        $this->authorize('update', $page);
        $validated = $this->validatePage($request);

        $validated['slug'] = Str::slug($request->title);
        $validated['is_published'] = $request->has('is_published');
        $validated = $this->sanitizePayload($validated);

        $page->update($validated);

        return redirect()->route('admin.pages.index')->with('success', 'Page updated successfully.');
    }

    public function destroy(Page $page)
    {
        $this->authorize('delete', $page);
        $page->delete();
        return redirect()->route('admin.pages.index')->with('success', 'Page deleted successfully.');
    }

    private function validatePage(Request $request): array
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content_mode' => ['nullable', Rule::in(['classic', 'builder'])],
            'content' => 'nullable|string',
            'content_blocks' => 'nullable|string',
            'custom_css' => 'nullable|string',
            'custom_js' => 'nullable|string',
            'is_published' => 'boolean',
            'meta_description' => 'nullable|string|max:160',
        ]);

        $validated['content_mode'] = $validated['content_mode'] ?? 'classic';

        if (($validated['content_mode'] ?? 'classic') === 'classic' && blank($validated['content'] ?? null)) {
            throw ValidationException::withMessages([
                'content' => 'Konten wajib diisi untuk mode classic.',
            ]);
        }

        if (($validated['content_mode'] ?? 'classic') === 'builder' && blank($validated['content_blocks'] ?? null)) {
            throw ValidationException::withMessages([
                'content_blocks' => 'Minimal satu block wajib diisi untuk mode builder.',
            ]);
        }

        if (!blank($validated['custom_js'] ?? null) && !Gate::allows('useCustomJs', Page::class)) {
            throw ValidationException::withMessages([
                'custom_js' => 'Anda tidak memiliki izin untuk menyimpan custom JS.',
            ]);
        }

        return $validated;
    }

    private function sanitizePayload(array $validated): array
    {
        if (!empty($validated['content'])) {
            $validated['content'] = clean($validated['content'], 'html5');
        }

        $validated['content_blocks'] = $this->sanitizeBlocks($validated['content_blocks'] ?? null);

        // Preserve classic compatibility and avoid stale values crossing mode.
        if (($validated['content_mode'] ?? 'classic') === 'classic') {
            $validated['content_blocks'] = null;
            $validated['custom_css'] = null;
            $validated['custom_js'] = null;
        } else {
            $validated['content'] = $validated['content'] ?? '';
            $validated['custom_css'] = $this->sanitizeCss((string) ($validated['custom_css'] ?? ''));
            $validated['custom_js'] = Gate::allows('useCustomJs', Page::class)
                ? $this->sanitizeJs((string) ($validated['custom_js'] ?? ''))
                : null;
        }

        return $validated;
    }

    private function sanitizeBlocks(?string $rawBlocks): ?array
    {
        if (blank($rawBlocks)) {
            return null;
        }

        $decoded = json_decode($rawBlocks, true);
        if (!is_array($decoded)) {
            throw ValidationException::withMessages([
                'content_blocks' => 'Format block builder tidak valid.',
            ]);
        }

        $allowedTypes = ['hero', 'text', 'image', 'cta', 'html'];
        $sanitized = [];

        foreach ($decoded as $index => $block) {
            if (!is_array($block)) {
                throw ValidationException::withMessages([
                    'content_blocks' => "Block ke-" . ($index + 1) . " tidak valid.",
                ]);
            }

            $type = $block['type'] ?? null;
            $data = $block['data'] ?? [];

            if (!in_array($type, $allowedTypes, true)) {
                throw ValidationException::withMessages([
                    'content_blocks' => "Tipe block '{$type}' tidak didukung.",
                ]);
            }

            if (!is_array($data)) {
                $data = [];
            }

            $sanitized[] = [
                'type' => $type,
                'data' => $this->sanitizeBlockData($type, $data),
            ];
        }

        return $sanitized;
    }

    private function sanitizeBlockData(string $type, array $data): array
    {
        return match ($type) {
            'hero' => [
                'title' => strip_tags((string) ($data['title'] ?? '')),
                'subtitle' => strip_tags((string) ($data['subtitle'] ?? '')),
                'background_image' => filter_var($data['background_image'] ?? '', FILTER_VALIDATE_URL) ? $data['background_image'] : '',
            ],
            'text' => [
                'content' => clean((string) ($data['content'] ?? ''), 'page_builder_html'),
            ],
            'image' => [
                'url' => filter_var($data['url'] ?? '', FILTER_VALIDATE_URL) ? $data['url'] : '',
                'alt' => strip_tags((string) ($data['alt'] ?? '')),
                'caption' => strip_tags((string) ($data['caption'] ?? '')),
            ],
            'cta' => [
                'title' => strip_tags((string) ($data['title'] ?? '')),
                'description' => strip_tags((string) ($data['description'] ?? '')),
                'button_label' => strip_tags((string) ($data['button_label'] ?? '')),
                'button_url' => filter_var($data['button_url'] ?? '', FILTER_VALIDATE_URL) ? $data['button_url'] : '',
            ],
            'html' => [
                'html' => clean((string) ($data['html'] ?? ''), 'trusted_custom_code'),
            ],
            default => [],
        };
    }

    private function sanitizeCss(string $css): string
    {
        $css = preg_replace('#</?style[^>]*>#i', '', $css) ?? '';
        return trim($css);
    }

    private function sanitizeJs(string $js): string
    {
        $js = preg_replace('#</?script[^>]*>#i', '', $js) ?? '';
        return trim($js);
    }
}
