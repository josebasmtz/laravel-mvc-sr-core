<?php


namespace Josebasmtz\MvcSrCore\Criteria;


use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Josebasmtz\MvcSrCore\Interfaces\ICriteria;

class SearchCriteria implements ICriteria
{
    protected $terms = [];
    public function __construct($terms)
    {
        if (!is_array($terms) || $terms instanceof Collection)
        {
            $terms = (string)$terms;
            $terms = explode(' ', $terms);
        }

        foreach ($terms as $term)
        {
            $term = valid_str($term);
            if ($term !== null)
            {
                $this->terms[] = $term;
            }
        }
    }

    public function apply(&$model)
    {
        $model = $model->search($this->terms);
        return $model;
    }
}