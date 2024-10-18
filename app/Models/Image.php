<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = ['path'];

    public function article(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Article::class);
    }
}
