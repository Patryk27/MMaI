<?php

use App\Core\Exceptions\UnimplementedException;
use Illuminate\Support\Collection;

/**
 * Throws a @see UnimplementedException pointing at the caller's frame.
 *
 * @return void
 */
function unimplemented(): void
{
    $frames = debug_backtrace();
    $callerFrame = $frames[1];

    throw new UnimplementedException(
        sprintf('%s::%s', $callerFrame['class'], $callerFrame['function'])
    );
}

/**
 * Creates a collection which includes given value only if it's not null.
 *
 * It works similarly to @see collect(), with one corner-case handled differently:
 *   collect(['a', 'b'])      ->  new Collection(['a', 'b'])      (i.e. collection with two items)
 *   collect_one(['a', 'b'])  ->  new Collection([ ['a', 'b'] ])  (i.e. collection with one item)
 *
 * Better shown on models:
 *   $someModel = new Model([
 *     'foo' => true,
 *     'bar' => true,
 *   ]);
 *
 *   collect($someModel)      ->  new Collection(['foo' => true, 'bar' => true])  (see how model's properties got extracted)
 *   collect_one($someModel)  ->  new Collection($someModel)
 *
 * @param mixed|null $value
 * @return Collection
 */
function collect_one($value): Collection
{
    $collection = new Collection();

    if (isset($value)) {
        $collection->push($value);
    }

    return $collection;
}