<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'content',
        'author',
        'source_name',
        'url',
        'url_to_image',
        'category',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    // Static query methods
    public static function searchArticles($query = null, $filters = [])
    {
        $q = static::query();
        if ($query) {
            $q->where(function($sub) use ($query) {
                $sub->where('title', 'like', "%$query%")
                    ->orWhere('description', 'like', "%$query%")
                    ->orWhere('content', 'like', "%$query%")
                    ->orWhere('author', 'like', "%$query%")
                    ->orWhere('source_name', 'like', "%$query%")
                    ->orWhere('category', 'like', "%$query%")
                ;
            });
        }
        if (!empty($filters['date_from'])) {
            $q->whereDate('published_at', '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $q->whereDate('published_at', '<=', $filters['date_to']);
        }
        if (!empty($filters['category'])) {
            $q->where('category', $filters['category']);
        }
        if (!empty($filters['source'])) {
            $q->where('source_name', $filters['source']);
        }
        if (!empty($filters['author'])) {
            $q->where('author', $filters['author']);
        }
        if (!empty($filters['sources'])) {
            $q->whereIn('source_name', $filters['sources']);
        }
        if (!empty($filters['categories'])) {
            $q->whereIn('category', $filters['categories']);
        }
        if (!empty($filters['authors'])) {
            $q->whereIn('author', $filters['authors']);
        }
        return $q;
    }
}
