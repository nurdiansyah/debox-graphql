<?php
namespace debox\graphql\relay\type;


use debox\graphql\support\GraphQLType;
use GraphQL\Type\Definition\Type;

class ConnectionEdgeType extends GraphQLType {
    public function fields() {
        return [
            'cursor' => [
                'type' => Type::nonNull(Type::id())
            ],
            'node' => [
                'type' => app('graphql')->type('Node')
            ]
        ];
    }

    public function toType() {
        return new EdgeObjectType($this->toArray());
    }
}