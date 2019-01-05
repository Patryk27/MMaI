<?php

namespace App\Attachments\Models;

use App\Attachments\Presenters\AttachmentPresenter;
use App\Core\Models\HasPresenter;
use App\Core\Models\Presentable;
use App\Pages\Models\Page;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id
 * @property int|null $page_id
 * @property string $name
 * @property string $mime
 * @property int $size
 * @property string $path
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * -----
 *
 * @property-read Page $page
 *
 * -----
 *
 * @method AttachmentPresenter getPresenter()
 */
class Attachment extends Model implements Presentable {
    use HasPresenter;

    /** @var string[] */
    protected $fillable = [
        'page_id',
        'name',
        'mime',
        'size',
        'path',
    ];

    /** @var string[] */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function page() {
        return $this->belongsTo(Page::class);
    }

    /**
     * @return string
     */
    public function getSizeForHumans(): string {
        $size = $this->size;

        foreach (['B', 'KB', 'MB', 'GB'] as $unitIdx => $unit) {
            if ($size < 1024) {
                if ($size === (int)$size) {
                    return sprintf('%d %s', $size, $unit);
                }

                return sprintf('%.2f %s', $size, $unit);
            }

            if ($size >= 1024) {
                $size /= 1024;
            }
        }

        return sprintf('%.2f TB', $size);
    }

    /**
     * @inheritDoc
     */
    public static function getPresenterClass(): string {
        return AttachmentPresenter::class;
    }
}
