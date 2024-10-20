<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $path
 * @property int $id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
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
