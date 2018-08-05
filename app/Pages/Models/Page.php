<?php

namespace App\Pages\Models;

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
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 *
 * -----
 *
 * @property-read EloquentCollection|PageVariant[] $pageVariants
 */
class Page extends Model
{

    const
        TYPE_BLOG = 'blog',
        TYPE_CMS = 'cms';

    /**
     * @var string[]
     */
    protected $fillable = [
        'type',
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
     */
    public function pageVariants()
    {
        return $this->hasMany(PageVariant::class);
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
    public function getPageVariantForLanguage(int $languageId): ?PageVariant
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

}