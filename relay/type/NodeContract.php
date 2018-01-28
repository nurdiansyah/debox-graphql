<?php
namespace Nurdiansyah\Graphql\Relay\Type;


interface NodeContract {
    public function resolveById($id);
}