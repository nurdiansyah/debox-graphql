<?php namespace debox\graphql\support\facades;


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
