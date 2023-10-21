<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class File extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = ['path', 'article_id'];

    /**
     * @return BelongsTo
     */
    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }
}
