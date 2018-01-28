<?php
namespace Debox\Graphql\Relay\Type;


use Debox\Graphql\Support\Facades\GraphQL;
use Debox\Graphql\Support\GraphQLType;
use Debox\Graphql\Relay\Field\NodeIdField;

abstract class NodeType extends GraphQLType implements NodeContract {
    public function fields() {
        $currentFields = parent::fields();
        $idResolver = $this->getIdResolverFromFields($currentFields);
        $nodeIdField = $this->getNodeIdField();
        $nodeIdField->setIdResolver($idResolver);
        $currentFields['id'] = $nodeIdField->toArray();

        return $currentFields;
    }

    protected function getNodeIdField() {
        $nodeIdField = new NodeIdField();
        $nodeIdField->setIdType($this->name);
        return $nodeIdField;
    }

    protected function getIdResolverFromFields($fields) {
        $idResolver = null;
        $originalResolver = array_get($fields, 'id.resolve');
        if ($originalResolver) {
            $idResolver = function () use ($originalResolver) {
                $id = call_user_func_array($originalResolver, func_get_args());
                return $id;
            };
        } else {
            $idResolver = function ($root) {
                return array_get($root, 'id');
            };
        }
        return $idResolver;
    }

    protected function getInterfaces() {
        return [
            GraphQL::type('Node')
        ];
    }
}