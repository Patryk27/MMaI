<?php

namespace App\Routes\Models;

use App\Core\Models\Interfaces\Morphable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * This model describes a single route.
 *
 * It can point at:
 *   - a page,
 *   - a route (in which case we're doing a redirection).
 *
 * -----
 *
 * @property-read int $id
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

    /**
     * @var string[]
     */
    protected $fillable = [
        'url',
        'model_type',
        'model_id',
    ];

    /**
     * Builds route for given morphable.
     *
     * @param string $url
     * @param Morphable $morphable
     * @return Route
     */
    public static function buildFor(string $url, Morphable $morphable): Route
    {
        return new self([
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
     * Changes route's target to given one ("re-routes" the route).
     *
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
    public function getTargetUrl(): string
    {
        return '/' . $this->url;
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