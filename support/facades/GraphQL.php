<?php namespace Debox\Graphql\Support\Facades;


use October\Rain\Support\Facade;

class GraphQL extends Facade {
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() {
        return 'graphql';
    }
}
