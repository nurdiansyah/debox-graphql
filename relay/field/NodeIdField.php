<?php
namespace Nurdiansyah\Graphql\Relay\Field;

use Nurdiansyah\Graphql\Support\Facades\Relay;
use Nurdiansyah\Graphql\Support\GraphQLField;
use GraphQL\Type\Definition\Type;

class NodeIdField extends GraphQLField {
    protected $idResolver;
    protected $idType;
    protected $attributes = [
        'description' => 'A relay node id field'
    ];

    public function type() {
        return Type::nonNull(Type::id());
    }

    public function setIdResolver($idResolver) {
        $this->idResolver = $idResolver;
        return $this;
    }

    public function getIdResolver() {
        return $this->idResolver;
    }

    public function setIdType($idType) {
        $this->idType = $idType;
        return $this;
    }

    public function getIdType() {
        return $this->idType;
    }

    public function resolve() {
        $id = call_user_func_array($this->idResolver, func_get_args());
        return Relay::toGlobalId($this->idType, $id);
    }
}
