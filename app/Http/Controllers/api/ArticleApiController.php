<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;

class ArticleApiController extends Controller
{
   public function showArticles(Request $request)
    {
        $articles = Article::select('id', 'title', 'file_path', 'created_at')
            ->latest()
            ->paginate(10);

        $articles->getCollection()->transform(function ($article) {
            return [
                'id'         => $article->id,
                'title'      => $article->title,
                'file_url'   => asset('storage/' . $article->file_path),
                'created_at' => $article->created_at->toDateTimeString(),
            ];
        });

        return response()->json([
            'success' => true,
            'data'    => $articles->items(),
            'pagination' => [
                'current_page' => $articles->currentPage(),
                'per_page'     => $articles->perPage(),
                'total'        => $articles->total(),
                'last_page'    => $articles->lastPage(),
                'next_page_url' => $articles->nextPageUrl(),
                'prev_page_url' => $articles->previousPageUrl(),
            ],
        ], 200);
    }
    
  public function searchArticles(Request $request)
{
    $request->validate([
        'query' => 'nullable|string|max:255',
    ]);

    $query = $request->input('query', '');
    $cleanQuery = trim(preg_replace('/[^\p{L}\p{N}\s]/u', '', $query));

    $articlesQuery = Article::select('id', 'title', 'file_path', 'created_at');

    if (!empty($cleanQuery)) {
        $keywords = preg_split('/\s+/', $cleanQuery, -1, PREG_SPLIT_NO_EMPTY);
        $articlesQuery->where(function ($q) use ($keywords) {
            foreach ($keywords as $keyword) {
                $q->orWhere('title', 'LIKE', "%{$keyword}%");
            }
        });
    }

    $articles = $articlesQuery->latest()->paginate(10);

    $formattedArticles = $articles->getCollection()->map(function ($article) {
        return [
            'id'         => (string) $article->id,
            'title'      => $article->title,
            'file_url'   => asset('storage/' . $article->file_path),
            'created_at' => $article->created_at->toDateTimeString(),
        ];
    });

    return response()->json([
        'status'      => '1',
        'status_code' => '200',
        'data'        => [
            'articles'     => $formattedArticles,
            'current_page' => (string) $articles->currentPage(),
            'last_page'    => (string) $articles->lastPage(),
            'per_page'     => (string) $articles->perPage(),
            'total'        => (string) $articles->total(),
        ],
        'message' => empty($cleanQuery)
            ? 'Articles fetched successfully'
            : 'Articles fetched successfully for query: ' . $cleanQuery,
    ]);
}
}
