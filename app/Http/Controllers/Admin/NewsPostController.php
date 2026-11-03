<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsPost;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class NewsPostController extends Controller
{
    public function index(): View
    {
        $posts = NewsPost::orderByDesc('published_at')->orderByDesc('id')->get();

        return view('admin.news.index', compact('posts'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title'        => ['required', 'string', 'max:220'],
            'category'     => ['required', 'string', 'max:60'],
            'summary'      => ['required', 'string', 'max:600'],
            'content'      => ['nullable', 'string', 'max:6000'],
            'external_url' => ['nullable', 'string', 'max:500'],
            'image'        => ['nullable', 'image', 'max:4096'],
            'is_featured'  => ['nullable', 'boolean'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $slug = Str::slug($data['title']);
        if (NewsPost::where('slug', $slug)->exists()) {
            $slug .= '-' . substr(uniqid(), -4);
        }

        $imagePath = '/images/piezas/banner-general.png';
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('news', 'public');
        }

        NewsPost::create([
            'title'        => $data['title'],
            'slug'         => $slug,
            'category'     => $data['category'],
            'summary'      => $data['summary'],
            'content'      => $data['content'] ?? null,
            'external_url' => $data['external_url'] ?? null,
            'image_path'   => $imagePath,
            'is_featured'  => (bool) ($data['is_featured'] ?? false),
            'is_published' => (bool) ($data['is_published'] ?? true),
            'published_at' => now(),
        ]);

        return redirect()->route('admin.news.index')->with('status', 'Noticia publicada en el portal.');
    }

    public function update(Request $request, NewsPost $news): RedirectResponse
    {
        $data = $request->validate([
            'title'        => ['required', 'string', 'max:220'],
            'category'     => ['required', 'string', 'max:60'],
            'summary'      => ['required', 'string', 'max:600'],
            'content'      => ['nullable', 'string', 'max:6000'],
            'external_url' => ['nullable', 'string', 'max:500'],
            'image'        => ['nullable', 'image', 'max:4096'],
            'is_featured'  => ['nullable', 'boolean'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('image')) {
            if ($news->image_path && ! str_starts_with($news->image_path, '/images/')) {
                Storage::disk('public')->delete($news->image_path);
            }
            $news->image_path = $request->file('image')->store('news', 'public');
        }

        $news->update([
            'title'        => $data['title'],
            'category'     => $data['category'],
            'summary'      => $data['summary'],
            'content'      => $data['content'] ?? null,
            'external_url' => $data['external_url'] ?? null,
            'image_path'   => $news->image_path,
            'is_featured'  => (bool) ($data['is_featured'] ?? false),
            'is_published' => (bool) ($data['is_published'] ?? false),
        ]);

        return redirect()->route('admin.news.index')->with('status', 'Noticia actualizada.');
    }

    public function destroy(NewsPost $news): RedirectResponse
    {
        if ($news->image_path && ! str_starts_with($news->image_path, '/images/')) {
            Storage::disk('public')->delete($news->image_path);
        }

        $news->delete();

        return redirect()->route('admin.news.index')->with('status', 'Noticia eliminada.');
    }
}
