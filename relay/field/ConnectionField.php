<?php
namespace Nurdiansyah\Graphql\Relay\Field;

use Nurdiansyah\Graphql\Relay\EdgesCollection;
use Nurdiansyah\Graphql\Support\GraphQLField;
use GraphQL\Type\Definition\Type;
use Illuminate\Database\Query\Builder;
use October\Rain\Database\Relations\Relation;

abstract class ConnectionField extends GraphQLField {
    protected $queryBuilderResolver;

    protected function connectionArgs() {
        return [
            'first' => [
                'name' => 'first',
                'type' => Type::int()
            ],
            'last' => [
                'name' => 'last',
                'type' => Type::int()
            ],
            'after' => [
                'name' => 'after',
                'type' => Type::string()
            ],
            'before' => [
                'name' => 'before',
                'type' => Type::string()
            ]
        ];
    }

    public function args() {
        $args = parent::args();
        $connectionArgs = $this->connectionArgs();
        return array_merge($connectionArgs, $args);
    }

    public function setType($type) {
        $this->attributes['type'] = $type;
    }

    public function getQueryBuilderResolver() {
        return $this->queryBuilderResolver;
    }

    public function setQueryBuilderResolver($queryBuilderResolver) {
        $this->queryBuilderResolver = $queryBuilderResolver;
        return $queryBuilderResolver;
    }

    protected function getCountFromQuery($query) {
        $countQuery = clone $query;
        if ($countQuery instanceof Relation) {
            $countQuery->getBaseQuery()->orders = null;
        } else if ($countQuery instanceof \Illuminate\Database\Eloquent\Builder) {
            $countQuery->getQuery()->orders = null;
        } else if ($countQuery instanceof Builder) {
            $countQuery->orders = null;
        }
        return $countQuery->count();
    }

    /**
     * @param $query Builder
     * @return mixed
     */
    protected function resolveItemsFromQueryBuilder($query) {
        return $query->get();
    }

    protected function getCollectionFromItems($items, $offset, $limit, $total, $hasPreviousPage, $hasNextPage) {
        $collection = new EdgesCollection($items);
        $collection->setTotal($total);
        $collection->setStartCursor($offset);
        $collection->setEndCursor($offset + $limit - 1);
        $collection->setHasNextPage($hasNextPage);
        $collection->setHasPreviousPage($hasPreviousPage);
        return $collection;
    }

    public function resolve($root, $args) {
        // Get the query builder
        $query = $this->resolveQueryBuilder($root, $args);

        // If there is no query builder returned, try to use the parent resolve method.
        $after = array_get($args, 'after');
        $before = array_get($args, 'before');
        $after = $after !== null ? base64_decode($after): null;
        $before = $before !== null ? base64_decode($before): null;
        $first = array_get($args, 'first');
        $last = array_get($args, 'last');
        $count = $this->getCountFromQuery($query);
        $offset = 0;
        $limit = 0;
        if ($first !== null) {
            $limit = $first;
            $offset = 0;
            if ($after !== null) {
                $offset = $after + 1;
            }
            if ($before !== null) {
                $limit = min(max(0, $before - $offset), $limit);
            }
        } else if ($last !== null) {
            $limit = $last;
            $offset = $count - $limit;
            if ($before !== null) {
                $offset = max(0, $before - $limit);
                $limit = min($before - $offset, $limit);
            }
            if ($after !== null) {
                $d = max(0, $after + 1 - $offset);
                $limit -= $d;
                $offset += $d;
            }
        }
        $offset = max(0, $offset);
        $limit = min($count - $offset, $limit);
        $query->skip($offset)->take($limit);
        $hasNextPage = ($offset + $limit) < $count;
        $hasPreviousPage = $offset > 0;
        $resolveItemsArguments = [$query, $root, $args];
        $items = call_user_func_array([$this, 'resolveItemsFromQueryBuilder'], $resolveItemsArguments);
        $collection = $this->getCollectionFromItems($items, $offset, $limit, $count, $hasPreviousPage, $hasNextPage);
        return $collection;
    }

    /**
     * @param $root
     * @param $args
     * @return Builder
     */
    abstract public function resolveQueryBuilder($root, $args);
}