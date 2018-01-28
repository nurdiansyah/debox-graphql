<?php


namespace Nurdiansyah\Graphql\Relay;


use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Fluent;

class MutationResponse extends Fluent {
    protected $clientMutationId;
    protected $originalNode;

    public function getOriginalNode() {
        return $this->originalNode;
    }

    public function setNode($node) {
        $this->originalNode = $node;
        $this->attributes = $node instanceof Arrayable ? $node->toArray() : (array)$node;
        return $this;
    }

    public function setClientMutationId($clientMutationId) {
        $this->clientMutationId = $clientMutationId;
        return $this;
    }

    public function getClientMutationId() {
        return $this->clientMutationId;
    }
}
