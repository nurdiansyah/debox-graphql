<?php
namespace Nurdiansyah\Graphql\Relay;

use Nurdiansyah\Graphql\Exception\NodeRootInvalid;
use Nurdiansyah\Graphql\Support\GraphQLInterfaceType;
use GraphQL\Type\Definition\Type;

class NodeInterface extends GraphQLInterfaceType {
    protected $attributes = [
        'name' => 'Node',
        'description' => 'The relay node interface'
    ];

    /**
     * NodeInterface constructor.
     */
    public function __construct() {
        parent::__construct();
    }

    public function fields() {
        return [
            'id' => [
                'type' => Type::nonNull(Type::id())
            ]
        ];
    }

    public function resolveType($root) {
        if (!$root instanceof NodeResponse) {
            throw new NodeRootInvalid('$root is not a NodeResponse');
        }
        return $root->getType();
    }
}