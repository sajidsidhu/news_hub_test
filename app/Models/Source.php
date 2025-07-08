<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
    use HasFactory;

    protected $fillable = [
        'source_id',
        'name',
        'description',
        'url',
        'category',
        'language',
        'country',
    ];

    /**
     * Get the source IDs from the database.
     *
     * @return array
     */
    public static function getSourceIds(): array
    {
        return self::pluck('source_id')->toArray(); 
    }
    
    /**
     * Search sources by name or category.
     *
     * @param string|null $query
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function search($query = null)
    {
        return  static::select('source_id', 'name')
                    ->when($query, function($sub) use ($query) {
                        $sub->where('name', 'like', "%$query%")
                            ->orWhere('category', 'like', "%$query%");
                    })
                    ->orderBy('name')
                    ->limit(10)
                    ->get();
    }

}
