<?php
namespace Debox\Graphql\Relay;

use Debox\Graphql\Exception\NodeInvalid;
use Debox\Graphql\Exception\TypeNotFound;
use Debox\Graphql\Support\GraphQLQuery;
use Debox\Graphql\Relay\Type\NodeContract;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

class NodeQuery extends GraphQLQuery {
    protected $attributes = [
        'name' => 'node',
        'description' => 'A node query'
    ];

    public function type() {
        return app('graphql')->type('Node');
    }

    public function args() {
        return [
            'id' => [
                'name' => 'id',
                'type' => Type::nonNull(Type::id())
            ]
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $info) {
        $globalId = app('graphql.relay')->fromGlobalId($args['id']);
        $typeName = $globalId['type'];
        $id = $globalId['id'];
        $types = app('graphql')->getTypes();
        $typeClass = array_get($types, $typeName);
        if (!$typeClass) {
            throw new TypeNotFound('Type "' . $typeName . '" not found.');
        }
        $type = app($typeClass);
        if (!$type instanceof NodeContract) {
            throw new NodeInvalid('Type "' . $typeName . '" doesn\'t implement the NodeContract interface.');
        }
        $node = $type->resolveById($id);
        $response = new NodeResponse();
        $response->setNode($node);
        $response->setType(app('graphql')->type($typeName));
        return $response;
    }

}