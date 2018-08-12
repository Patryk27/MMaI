<?php

namespace App\Pages\Models;

use App\Core\Models\Interfaces\Morphable;
use App\Core\Models\Interfaces\Presentable;
use App\Core\Models\Traits\HasPresenter;
use App\Languages\Models\Language;
use App\Pages\Presenters\PageVariantPresenter;
use App\Routes\Models\Route;
use App\Tags\Models\Tag;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;

/**
 * This model describes actual contents of a @see Page.
 *
 * -----
 *
 * @property-read int $id
 * @property int $page_id
 * @property int $language_id
 * @property string $status
 * @property string $title
 * @property string|null $lead
 * @property string $content
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 * @property Carbon|null $published_at
 *
 * -----
 *
 * @property-read Page $page
 * @property-read Language $language
 * @property-read Route|null $route
 * @property-read EloquentCollection|Tag[] $tags
 *
 * -----
 *
 * @method PageVariantPresenter getPresenter()
 */
class PageVariant extends Model implements Morphable, Presentable
{

    use HasPresenter;

    const
        STATUS_DRAFT = 'draft',
        STATUS_PUBLISHED = 'published',
        STATUS_DELETED = 'deleted';

    /**
     * @var string[]
     */
    protected $fillable = [
        'page_id',
        'language_id',
        'status',
        'title',
        'lead',
        'content',
        'published_at',
    ];

    /**
     * @var string[]
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'published_at',
    ];

    /**
     * @var string[]
     */
    protected $with = [
        'page',
        'language',
        'route',
        'tags',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function page()
    {
        return $this->belongsTo(Page::class);
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
     * Returns `true` if this page variant is a draft.
     *
     * @return bool
     */
    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    /**
     * Returns `true` if this page variant has been published.
     *
     * @return bool
     */
    public function isPublished(): bool
    {
        return $this->status === self::STATUS_PUBLISHED;
    }

    /**
     * Returns `true` if this page variant has been deleted.
     *
     * @return bool
     */
    public function isDeleted(): bool
    {
        return $this->status === self::STATUS_DELETED;
    }

    /**
     * Returns URL for the "Edit" action.
     *
     * @return string
     *
     * @todo move to the presenter?
     */
    public function getBackendEditUrl(): string
    {
        return $this->page->getBackendEditUrl();
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
        return 'page-variant';
    }

    /**
     * @inheritdoc
     */
    public static function getPresenterClass(): string
    {
        return PageVariantPresenter::class;
    }

}