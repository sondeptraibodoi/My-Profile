<?php

namespace App\Library\QueryBuilder\Concerns;

use App\Helpers\AggridHelper;
use App\Library\QueryBuilder\Filters\AllowedFilter;

trait AddInitFromModel
{
    public function initFromModelForList(): self
    {
        if (method_exists($this->subject, 'getModel')) {
            if ($this->getModel()->usesTimestamps()) {
                $this->allowedTimestamps();
                $this->defaultSort('-' . $this->getModel()->getKeyName());
            }
            if (property_exists($this->getModel(), 'INCLUDE'))
                $this->allowedIncludes($this->getModel()::$INCLUDE);
            if (property_exists($this->getModel(), 'SORT'))
                $this->allowedSorts($this->getModel()::$SORT);
            if (property_exists($this->getModel(), 'SEARCH'))
                $this->allowedSearch($this->getModel()::$SEARCH);
            if (property_exists($this->getModel(), 'FILTER'))
                $this->allowedFilters($this->getModel()::$FILTER);
        }
        $filters = $this->request->calcFilters();
        if (isset($filters) && $filters->count() > 0)
            $filters->each(function ($items, $key) {
                foreach ($items as $method => $values) {
                    if (!is_array($values)) {
                        $values = [$values];
                    }
                    foreach ($values as $value) {
                        AggridHelper::convertTextFilterType(
                            [
                                'type' => $method,
                                'filter' => $value
                            ],
                            $this,
                            $key,
                            'where'
                        );
                    }
                }
            });
        $relations = $this->request->calcRelations();
        if (isset($relations) && $relations->count() > 0)
            $relations->each(function ($items, $relation) {
                $this->whereHas($relation, function ($q) use ($items) {
                    foreach ($items as $key => $filters) {
                        foreach ($filters as $method => $values) {
                            if (!is_array($values)) {
                                $values = [$values];
                            }
                            foreach ($values as $value) {
                                AggridHelper::convertTextFilterType(
                                    [
                                        'type' => $method,
                                        'filter' => $value
                                    ],
                                    $q,
                                    $key,
                                    'where'
                                );
                            }
                        }
                    }
                });
            });
        return $this;
    }
    public function initFromModelForShow(): self
    {
        if (method_exists($this->subject, 'getModel')) {
            if (property_exists($this->getModel(), 'INCLUDE'))
                $this->allowedIncludes($this->getModel()::$INCLUDE);
        }
        return $this;
    }
}
