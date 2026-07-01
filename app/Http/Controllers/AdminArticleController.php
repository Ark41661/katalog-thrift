<?php
namespace App\Http\Controllers;

use App\Models\Article;
use App\Support\ArticleCoverHelper;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AdminArticleController extends Controller
{
    private function storeName(): string
    {
        return config('catalog.store_name');
    }

    private function categories(): array
    {
        return [
            'mix-match'      => 'Mix & Match',
            'tips-perawatan' => 'Tips Perawatan',
            'tren'           => 'Tren Fashion',
            'panduan'        => 'Panduan Ukuran',
        ];
    }

    public function index(): View
    {
        return view('admin.articles.index', [
            'articles'  => Article::latest()->paginate(20),
            'storeName' => $this->storeName(),
        ]);
    }

    public function create(): View
    {
        return view('admin.articles.create', [
            'categories' => $this->categories(),
            'storeName'  => $this->storeName(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title'        => ['required', 'string', 'max:200'],
            'category'     => ['required', 'string'],
            'cover_image'  => ['nullable', 'url'],
            'cover_file'   => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp,gif', 'max:10240'],
            'excerpt'      => ['nullable', 'string', 'max:300'],
            'content'      => ['required', 'string'],
            'author'       => ['nullable', 'string', 'max:100'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $data['cover_image'] = $this->resolveCoverImage($request, null);
        unset($data['cover_file']);

        $data['slug']         = Article::uniqueSlug($data['title']);
        $data['is_published'] = $request->boolean('is_published');
        $data['published_at'] = $data['is_published'] ? now() : null;
        $data['author']       = $data['author'] ?? 'Admin';

        if (empty($data['excerpt']) && !empty($data['content'])) {
            $data['excerpt'] = Str::limit($data['content'], 160);
        }

        Article::create($data);

        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil dibuat.');
    }

    public function edit(Article $article): View
    {
        return view('admin.articles.edit', [
            'article'    => $article,
            'categories' => $this->categories(),
            'storeName'  => $this->storeName(),
        ]);
    }

    public function update(Request $request, Article $article): RedirectResponse
    {
        $data = $request->validate([
            'title'        => ['required', 'string', 'max:200'],
            'category'     => ['required', 'string'],
            'cover_image'  => ['nullable', 'url'],
            'cover_file'   => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp,gif', 'max:10240'],
            'excerpt'      => ['nullable', 'string', 'max:300'],
            'content'      => ['required', 'string'],
            'author'       => ['nullable', 'string', 'max:100'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $resolvedCover = $this->resolveCoverImage($request, $article);
        if ($resolvedCover !== false) {
            $data['cover_image'] = $resolvedCover;
        } else {
            unset($data['cover_image']);
        }
        unset($data['cover_file']);

        if ($data['title'] !== $article->title) {
            $data['slug'] = Article::uniqueSlug($data['title'], $article->id);
        }

        $data['is_published'] = $request->boolean('is_published');
        if ($data['is_published'] && !$article->published_at) {
            $data['published_at'] = now();
        }

        $article->update($data);

        return redirect()->route('admin.articles.index')->with('success', 'Artikel diperbarui.');
    }

    public function destroy(Article $article): RedirectResponse
    {
        if ($article->cover_image && !str_starts_with($article->cover_image, 'http')) {
            Storage::disk('public')->delete($article->cover_image);
        }
        $article->delete();

        return back()->with('success', 'Artikel dihapus.');
    }

    /**
     * @return string|null|false  string = new cover, null = no cover, false = keep existing (update only)
     */
    private function resolveCoverImage(Request $request, ?Article $article): string|null|false
    {
        if ($request->hasFile('cover_file')) {
            if ($article?->cover_image && !str_starts_with($article->cover_image, 'http')) {
                Storage::disk('public')->delete($article->cover_image);
            }

            return ArticleCoverHelper::storeUpload($request->file('cover_file'));
        }

        $url = trim($request->input('cover_image', ''));

        if ($url !== '') {
            if (!ArticleCoverHelper::isDirectImageUrl($url)) {
                throw ValidationException::withMessages([
                    'cover_image' => 'URL tidak valid untuk gambar. Jangan paste link Gemini/Google. Download gambarnya lalu upload file, atau pakai link langsung (.jpg / .png / .webp).',
                ]);
            }

            return $url;
        }

        if ($article) {
            return false;
        }

        return null;
    }
}
