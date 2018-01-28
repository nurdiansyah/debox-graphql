<?php
namespace debox\graphql\support\facades;

use Illuminate\Support\Facades\Facade;

class Relay extends Facade {
    protected static function getFacadeAccessor() {
        return 'graphql.relay';
    }

}