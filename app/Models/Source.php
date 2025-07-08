<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
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
    
}
