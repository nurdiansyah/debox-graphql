<?php
namespace Debox\Graphql\Relay\Type;


interface NodeContract {
    public function resolveById($id);
}