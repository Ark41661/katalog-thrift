<?php
namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function index(Request $request): View
    {
        $query = Article::where('is_published', true)->latest('published_at');
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        $articles = $query->paginate(9);
        $categories = [
            'mix-match'      => 'Mix & Match',
            'tips-perawatan' => 'Tips Perawatan',
            'tren'           => 'Tren Fashion',
            'panduan'        => 'Panduan Ukuran',
        ];
        return view('public.articles.index', [
            'articles'       => $articles,
            'categories'     => $categories,
            'activeCategory' => $request->category,
            'storeName'      => config('catalog.store_name'),
        ]);
    }

    public function show(string $slug): View
    {
        $article = Article::where('slug', $slug)->where('is_published', true)->firstOrFail();
        $related = Article::where('is_published', true)
            ->where('category', $article->category)
            ->where('id', '!=', $article->id)
            ->latest('published_at')->take(3)->get();

        return view('public.articles.show', [
            'article'   => $article,
            'related'   => $related,
            'storeName' => config('catalog.store_name'),
        ]);
    }
}
