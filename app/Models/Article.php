<?php

namespace App\Models;

use App\Actions\File\FileAction;
use App\DTOs\ArticleDTO;
use App\Enums\ArticleStatusEnum;
use App\Http\Requests\ArticleStoreRequest;
use App\Http\Requests\ArticleUpdateRequest;
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
use Illuminate\Support\Facades\Auth;

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

    protected $fillable = [
        'title',
        'user_id',
        'content',
        'author_name',
        'status',
        'image_id',
        'published_at',
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
            ->select('slug', 'title', 'author_name', 'status', 'image_id')
            ->where('user_id', $userId)
            ->with('image', function (BelongsTo $query) {
                $query->select('path', 'id');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(8);
    }

    public static function getPublishedArticles(): LengthAwarePaginator
    {

        return Article::query()
            ->select('slug', 'title', 'author_name', 'status', 'image_id')
            ->with('image', function (BelongsTo $query) {
                $query->select('path', 'id');
            })
            ->orderBy('published_at', 'desc')
            ->published()
            ->paginate(8);
    }

    /**
     * Scope a query to only include published articles.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', ArticleStatusEnum::PUBLISHED);
    }

    public static function insertArticle(ArticleStoreRequest $request): string
    {

        $files = FileAction::insertFilesToStorage($request);
        $image = FileAction::insertImageToStorage($request);
        $articleDTO = self::createArticleDTO($request, $image);
        $article = Article::query()->create((array) $articleDTO);
        /** @var Article $article */
        self::insertFiles($files, $article->id);

        return $article->slug;

    }

    public static function updateArticle(ArticleUpdateRequest $request): string
    {
        $files = FileAction::insertFilesToStorage($request);
        $image = FileAction::insertImageToStorage($request);

        $articleDTO = self::createArticleDTO($request, $image);
        Article::query()->where('id', $request->id)->update((array) $articleDTO);
        $article = Article::query()
            ->select('id', 'slug')
            ->find($request->id);

        self::insertFiles($files, $article->id);

        return $article->slug;
    }

    private static function insertFiles($files, $articleId): void
    {
        if (! empty($files)) {
            $fileData = collect($files)->map(fn ($file) => [
                'article_id' => $articleId,
                'path' => $file,
            ])->toArray();
            File::query()->insert($fileData);
        }
    }

    private static function createArticleDTO(ArticleUpdateRequest|ArticleStoreRequest $request, ?Image $image): ArticleDTO
    {
        $request->merge([
            'image_id' => $image?->id,
            'user_id' => Auth::id(),
            'published_at' => $request->status == ArticleStatusEnum::PUBLISHED->value ? now() : null,
        ]);

        return new ArticleDTO($request);
    }
}
