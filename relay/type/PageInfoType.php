<?php
namespace debox\graphql\relay\type;

use debox\graphql\support\GraphQLType;
use GraphQL\Type\Definition\Type;

class PageInfoType extends GraphQLType {
    protected $attributes = [
        'name' => 'PageInfo',
        'description' => 'The relay pageInfo type used by connections'
    ];

    public function fields() {
        return [
            'hasNextPage' => [
                'type' => Type::nonNull(Type::boolean())
            ],
            'hasPreviousPage' => [
                'type' => Type::nonNull(Type::boolean())
            ],
            'startCursor' => [
                'type' => Type::string()
            ],
            'endCursor' => [
                'type' => Type::string()
            ]
        ];
    }
}