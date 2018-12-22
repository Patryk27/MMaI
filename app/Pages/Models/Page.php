<?php

namespace App\Pages\Models;

use App\Attachments\Models\Attachment;
use App\Core\Models\HasPresenter;
use App\Core\Models\Morphable;
use App\Core\Models\Presentable;
use App\Languages\Models\Language;
use App\Pages\Presenters\PagePresenter;
use App\Routes\Models\Route;
use App\Tags\Models\Tag;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id
 * @property int $language_id
 * @property string $title
 * @property string|null $lead
 * @property string $content
 * @property string|null $notes
 * @property string $type
 * @property string $status
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 * @property Carbon|null $published_at
 *
 * -----
 *
 * @property-read EloquentCollection|Attachment[] $attachments
 * @property-read Language $language
 * @property-read Route|null $route
 * @property-read EloquentCollection|Tag[] $tags
 *
 * -----
 *
 * @method PagePresenter getPresenter()
 */
class Page extends Model implements Morphable, Presentable
{
    use HasPresenter;

    public const
        TYPE_POST = 'post',
        TYPE_PAGE = 'page';

    public const
        STATUS_DRAFT = 'draft',
        STATUS_PUBLISHED = 'published',
        STATUS_DELETED = 'deleted';

    /** @var string[] */
    protected $fillable = [
        'language_id',
        'title',
        'lead',
        'content',
        'notes',
        'type',
        'status',
        'published_at',
    ];

    /** @var string[] */
    protected $dates = [
        'created_at',
        'updated_at',
        'published_at',
    ];

    /** @var string[] */
    protected $with = [
        'language',
        'route',
        'tags',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attachments()
    {
        return $this->hasMany(Attachment::class)->orderBy('name');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function route()
    {
        return $this->morphOne(Route::class, 'model');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class)->orderBy('name');
    }

    /**
     * @return bool
     */
    public function isPost(): bool
    {
        return $this->type === self::TYPE_POST;
    }

    /**
     * @return bool
     */
    public function isPage(): bool
    {
        return $this->type === self::TYPE_PAGE;
    }

    /**
     * @return bool
     */
    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    /**
     * @return bool
     */
    public function isPublished(): bool
    {
        return $this->status === self::STATUS_PUBLISHED;
    }

    /**
     * @return bool
     */
    public function isDeleted(): bool
    {
        return $this->status === self::STATUS_DELETED;
    }

    /**
     * @return string
     */
    public function getEditUrl(): string
    {
        return route('backend.pages.edit', $this->id);
    }

    /**
     * @inheritDoc
     */
    public function getMorphableId(): int
    {
        return $this->id;
    }

    /**
     * @inheritDoc
     */
    public static function getMorphableType(): string
    {
        return 'page';
    }

    /**
     * @inheritdoc
     */
    public static function getPresenterClass(): string
    {
        return PagePresenter::class;
    }
}
