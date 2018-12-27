<?php

namespace App\Routes\Models;

use App\Core\Models\Morphable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id
 * @property string $subdomain
 * @property string $url
 * @property string $model_type
 * @property int $model_id
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 *
 * -----
 *
 * @property-read Model|Morphable $model
 */
class Route extends Model implements Morphable
{
    /** @var string[] */
    protected $fillable = [
        'subdomain',
        'url',
        'model_type',
        'model_id',
    ];

    /**
     * Builds route for given morphable.
     *
     * @param string $subdomain
     * @param string $url
     * @param Morphable $morphable
     * @return Route
     */
    public static function build(string $subdomain, string $url, Morphable $morphable): Route
    {
        return new self([
            'subdomain' => $subdomain,
            'url' => $url,
            'model_type' => $morphable->getMorphableType(),
            'model_id' => $morphable->getMorphableId(),
        ]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function model()
    {
        return $this->morphTo();
    }

    /**
     * @param Morphable $morphable
     * @return void
     */
    public function setPointsAt(Morphable $morphable): void
    {
        $this->fill([
            'model_type' => $morphable->getMorphableType(),
            'model_id' => $morphable->getMorphableId(),
        ]);

        $this->setRelation('model', $morphable);
    }

    /**
     * @return string
     */
    public function getAbsoluteUrl(): string
    {
        return sprintf('%s://%s.%s/%s', env('APP_PROTOCOL'), $this->subdomain, env('APP_DOMAIN'), $this->url);
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
        return 'route';
    }
}
