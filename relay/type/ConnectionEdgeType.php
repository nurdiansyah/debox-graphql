<?php
namespace Nurdiansyah\Graphql\Relay\Type;


use Nurdiansyah\Graphql\Support\GraphQLType;
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