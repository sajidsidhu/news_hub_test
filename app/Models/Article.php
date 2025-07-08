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
        'source_id',
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

        $q->when($query, function ($q, $query) {
            $q->where(function ($sub) use ($query) {
            $sub->where('source_id', 'like', "%$query%")
                ->orWhere('category', 'like', "%$query%")
                ->orWhere('published_at', 'like', "%$query%");
            });
        });

        $q->when(!empty($filters['date_from']), function ($q) use ($filters) {
            $q->whereDate('published_at', '>=', $filters['date_from']);
        });

        $q->when(!empty($filters['date_to']), function ($q) use ($filters) {
            $q->whereDate('published_at', '<=', $filters['date_to']);
        });

        $q->where(function ($q) use ($filters) {

            $q->when(!empty($filters['categories']), function ($q) use ($filters) {
                $q->orWhereIn('category', $filters['categories']);
            });

            $q->when(!empty($filters['sources']), function ($q) use ($filters) {
                $q->orWhereIn('source_id', $filters['sources']);
            });

            $q->when(!empty($filters['authors']), function ($q) use ($filters) {
                $q->orWhereIn('author', $filters['authors']);
            });

        });
        

        return $q;
    }
}
