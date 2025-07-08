<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;
use App\Services\NewsApiService;
use App\Models\Source;
use App\Http\Resources\ArticleResource;

class ArticleController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');
        $filters = [
            'date_from' => $request->input('date_from'),
            'date_to' => $request->input('date_to'),
            'sources' => $request->input('sources', []),
            'categories' => $request->input('categories', []),
            'authors' => $request->input('authors', []),
        ];
        $per_page = $request->input('per_page', 20);
        $articles = Article::searchArticles($query, $filters)
            ->orderByDesc('published_at')
            ->paginate($per_page);
        return ArticleResource::collection($articles);
    }

    public function categories()
    {
        return response()->json([
            'categories' => NewsApiService::CATEGORIES
        ]);
    }

    public function sources()
    {
        $query = request()->input('query', null);
        $sources = Source::search($query);
        return response()->json(['sources' => $sources]);
    }
}
