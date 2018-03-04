<?php

namespace Debox\Tests\Mocks;

use Debox\Graphql\Support\GraphQLType;

class UserType extends GraphQLType {
    protected $attributes = [
        'name' => 'User'
    ];
}