<?php
namespace Nurdiansyah\Graphql\Relay;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Fluent;

class NodeResponse extends Fluent {
    protected $type;
    protected $originalNode;

    public function getOriginalNode() {
        return $this->originalNode;
    }

    public function setNode($node) {
        $this->originalNode = $node;
        $this->attributes = $node instanceof Arrayable ? $node->toArray() : (array)$node;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function getType() {
        return $this->type;
    }
}