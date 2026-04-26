<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PostRequest;
use App\Models\Post;
use App\Models\Category;
use App\Services\FileUploadService;

class PostController extends Controller
{
    public function __construct(private FileUploadService $uploader) {}

    public function index()
    {
        $user = auth()->user();
        
        // Admin sees all posts, contributors see only their own
        if ($user->isAdmin()) {
            $posts = Post::with(['user', 'category'])->latest()->paginate(15);
        } else {
            $posts = Post::where('user_id', $user->id)
                ->with(['user', 'category'])
                ->latest()
                ->paginate(15);
        }

        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        // Check if user can create posts
        $this->authorize('create', Post::class);

        $categories = Category::where('type', 'blog')->get();
        return view('admin.posts.form', compact('categories'));
    }

    public function store(PostRequest $request)
    {
        // Check if user can create posts
        $this->authorize('create', Post::class);

        if (!auth()->check()) {
            abort(401, 'Unauthorized');
        }

        try {
            $data = $request->except(['featured_image', 'detail_image']);
            $data['user_id'] = auth()->id();

            // Sanitize HTML content
            if (isset($data['body'])) {
                $data['body'] = clean($data['body']);
            }
            if (isset($data['excerpt'])) {
                $data['excerpt'] = clean($data['excerpt']);
            }

            // Only admin can publish posts immediately
            if (auth()->user()->isAdmin()) {
                $data['is_published'] = filter_var($request->boolean('is_published'), FILTER_VALIDATE_BOOLEAN);
                $data['published_at'] = $data['is_published'] ? now() : null;
            } else {
                $data['is_published'] = false;
                $data['published_at'] = null;
            }

            if ($request->hasFile('featured_image')) {
                $data['featured_image'] = $this->uploader->upload($request->file('featured_image'), 'posts');
            }
            if ($request->hasFile('detail_image')) {
                $data['detail_image'] = $this->uploader->upload($request->file('detail_image'), 'posts');
            }

            $post = Post::create($data);
            
            return redirect()
                ->route('admin.posts.index')
                ->with('success', 'Artikel berhasil ditambahkan. Menunggu persetujuan admin untuk dipublikasikan.');
        } catch (\Exception $e) {
            \Log::error('Error creating post: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'request_data' => $request->except(['featured_image']),
            ]);
            
            return back()
                ->withInput()
                ->with('error', 'Gagal menambahkan artikel. Silakan coba lagi.');
        }
    }

    public function edit(Post $post)
    {
        // Check if user can update this post
        $this->authorize('update', $post);

        $categories = Category::where('type', 'blog')->get();
        return view('admin.posts.form', compact('post', 'categories'));
    }

    public function update(PostRequest $request, Post $post)
    {
        // Check if user can update this post
        $this->authorize('update', $post);

        if (!auth()->check()) {
            abort(401, 'Unauthorized');
        }

        try {
            $data = $request->except(['featured_image', 'detail_image']);
            
            // Sanitize HTML content
            if (isset($data['body'])) {
                $data['body'] = clean($data['body']);
            }
            if (isset($data['excerpt'])) {
                $data['excerpt'] = clean($data['excerpt']);
            }

            // Only admin can publish/unpublish posts
            if (!auth()->user()->isAdmin()) {
                unset($data['is_published']);
            } else {
                $data['is_published'] = filter_var($request->boolean('is_published'), FILTER_VALIDATE_BOOLEAN);
                if ($data['is_published'] && !$post->published_at) {
                    $data['published_at'] = now();
                }
            }

            if ($request->hasFile('featured_image')) {
                $this->uploader->delete($post->featured_image);
                $data['featured_image'] = $this->uploader->upload($request->file('featured_image'), 'posts');
            }
            if ($request->hasFile('detail_image')) {
                $this->uploader->delete($post->detail_image);
                $data['detail_image'] = $this->uploader->upload($request->file('detail_image'), 'posts');
            }

            $post->update($data);
            
            return redirect()
                ->route('admin.posts.index')
                ->with('success', 'Artikel berhasil diperbarui.');
        } catch (\Exception $e) {
            \Log::error('Error updating post: ' . $e->getMessage(), [
                'post_id' => $post->id,
                'user_id' => auth()->id(),
                'request_data' => $request->except(['featured_image']),
            ]);
            
            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui artikel. Silakan coba lagi.');
        }
    }

    public function destroy(Post $post)
    {
        // Check if user can delete this post (only admin)
        $this->authorize('delete', $post);

        if (!auth()->check()) {
            abort(401, 'Unauthorized');
        }

        try {
            $this->uploader->delete($post->featured_image);
            $this->uploader->delete($post->detail_image);
            $postTitle = $post->title;
            $post->delete();
            
            return back()->with('success', "Artikel '{$postTitle}' berhasil dihapus.");
        } catch (\Exception $e) {
            \Log::error('Error deleting post: ' . $e->getMessage(), [
                'post_id' => $post->id,
                'user_id' => auth()->id(),
            ]);
            
            return back()->with('error', 'Gagal menghapus artikel. Silakan coba lagi.');
        }
    }
}
