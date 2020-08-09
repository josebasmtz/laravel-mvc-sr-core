<?php


namespace Josebasmtz\MvcSrCore\Abstracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

/**
 * Class AModel
 * @package Josebasmtz\MvcSrCore\Abstracts
 *
 * @property string text
 */

abstract class AModel extends Model
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->appends[] = 'text';
    }

    protected $searchable = [];

    /**
     * @param Builder $query
     * @param $terms
     * @return Builder
     */
    public function scopeSearch($query, $terms)
    {
        $query = $query->where(function ($query) use ($terms) {
            foreach ($terms as $term)
            {
                foreach ($this->searchable as $column)
                {
                    $query->orWhere($column, 'like', "%$term%");
                }
            }
        });

        return $query;
    }

    public function getTextAttribute()
    {
        return static::class;
    }
}