<?php

namespace App\Models;

use App\Enums\ArticleStatusEnum;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * @property positive-int $id
 * @property positive-int $user_id
 * @property string $slug
 * @property string $title
 * @property string $content
 * @property string $author_name
 * @property ArticleStatusEnum $status
 * @property positive-int|null $image_id
 * @property Carbon|null $published_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Image|null $image
 * @property Collection<File> $files
 * @property User $user
 *
 * @method published()
 */
class Article extends Model
{
    use HasFactory;
    use Sluggable;
    use SoftDeletes;

    protected $casts = [
        'status' => ArticleStatusEnum::class,
    ];
    public $timestamps = true;


    /**
     * Return the sluggable configuration array for this model.
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
            ],
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function image(): BelongsTo
    {
        return $this->belongsTo(Image::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function files(): HasMany
    {
        return $this->hasMany(File::class);
    }

    /**
     * Scope a query to only include published articles.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', ArticleStatusEnum::PUBLISHED);
    }
}
