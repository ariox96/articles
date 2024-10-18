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
use Illuminate\Pagination\LengthAwarePaginator;
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

    public static function getLatestUserArticles(int $userId): LengthAwarePaginator
    {

        return Article::query()
            ->select('slug', 'title', 'author_name', 'status')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(8);
    }

    public static function getPublishedArticles(): LengthAwarePaginator
    {

        return Article::query()
            ->select('slug', 'title', 'author_name', 'status')
            ->orderBy('published_at', 'desc')
            ->published()
            ->with('user')
            ->paginate(8);
    }

    /**
     * Scope a query to only include published articles.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', ArticleStatusEnum::PUBLISHED);
    }
}
