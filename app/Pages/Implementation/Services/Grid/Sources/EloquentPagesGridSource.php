<?php

namespace App\Pages\Implementation\Services\Grid\Sources;

use App\Grid\Sources\EloquentGridSource;
use App\Pages\Exceptions\PageException;
use App\Pages\Models\Page;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class EloquentPagesGridSource extends EloquentGridSource implements PagesGridSource {

    private const FIELD_MAPPING = [
        'id' => 'pages.id',
        'website' => 'websites.name',
        'type' => 'pages.type',
        'title' => 'pages.title',
        'status' => 'pages.status',
        'createdAt' => 'pages.created_at',
    ];

    /**
     * {@inheritdoc}
     * @throws PageException
     */
    protected function translateField(string $field): string {
        if ($field = self::FIELD_MAPPING[$field] ?? null) {
            return $field;
        }

        throw new PageException(sprintf(
            'Grid field [%s] is not known.', $field
        ));
    }

    /**
     * {@inheritdoc}
     */
    protected static function getNewQuery(): EloquentBuilder {
        $query = Page::query();
        $query->selectRaw('pages.*');

        // Include websites
        $query->join('websites', 'websites.id', 'pages.website_id');

//        // Include routes @todo
//        $this->builder->leftJoin('routes', function (JoinClause $join): void {
//            $join->on('routes.model_id', 'pages.id');
//
//            // We have to do whereRaw() instead of regular where() here, because
//            // when using bindings MySQL fails to prove functional dependency
//            // between the 'pages' and 'routes' tables, which causes this query
//            // to fail in some cases (e.g. when ordering by routes):
//            $join->whereRaw(sprintf(
//                'routes.model_type = \'%s\'', Page::getMorphableType()
//            ));
//        });

        return $query;
    }

}
