<?php

namespace App\Pages\Models;

use App\Attachments\Models\Attachment;
use App\Attachments\Models\Interfaces\Attachable;
use App\Core\Models\HasPresenter;
use App\Core\Models\Presentable;
use App\Pages\Presenters\PagePresenter;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;

/**
 * This model describes a single page.
 *
 * It's primarily used to bind common pages together, as it's the
 * @see PageVariant, which contains all the page's actual data.
 *
 * -----
 *
 * @property-read int $id
 * @property string $type
 * @property string $notes
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 *
 * -----
 *
 * @property-read EloquentCollection|PageVariant[] $pageVariants
 * @property-read EloquentCollection|Attachment[] $attachments
 *
 * -----
 *
 * @method PagePresenter getPresenter()
 */
class Page extends Model implements Attachable, Presentable
{

    use HasPresenter;

    public const
        TYPE_BLOG = 'blog',
        TYPE_CMS = 'cms';

    /**
     * @var string[]
     */
    protected $fillable = [
        'type',
        'notes',
    ];

    /**
     * @var string[]
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * @todo rename to just variants()
     */
    public function pageVariants()
    {
        return $this->hasMany(PageVariant::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    /**
     * Returns `true` if this page is a blog post.
     *
     * @return bool
     */
    public function isBlogPost(): bool
    {
        return $this->type === self::TYPE_BLOG;
    }

    /**
     * Returns `true` if this page is a CMS page.
     *
     * @return bool
     */
    public function isCmsPage(): bool
    {
        return $this->type === self::TYPE_CMS;
    }

    /**
     * Returns page variant for given language.
     *
     * @param int $languageId
     * @return PageVariant|null
     */
    public function getVariantForLanguage(int $languageId): ?PageVariant
    {
        return $this->pageVariants
            ->where('language_id', $languageId)
            ->first();
    }

    /**
     * Returns URL for the "Edit" action.
     *
     * @return string
     *
     * @todo move into presenter?
     */
    public function getBackendEditUrl(): string
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
     * @inheritDoc
     */
    public static function getPresenterClass(): string
    {
        return PagePresenter::class;
    }

}
