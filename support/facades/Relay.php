<?php
namespace Debox\Graphql\Support\Facades;

use Illuminate\Support\Facades\Facade;

class Relay extends Facade {
    protected static function getFacadeAccessor() {
        return 'graphql.relay';
    }

}