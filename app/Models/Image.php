<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Image extends Model
{
    use HasFactory;

    protected $fillable = ['path'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function article(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Article::class);
    }
}
