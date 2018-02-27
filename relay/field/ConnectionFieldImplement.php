<?php

namespace Debox\Graphql\Relay\Field;

use Illuminate\Database\Query\Builder;

class ConnectionFieldImplement extends ConnectionField {

    /**
     * @param $root
     * @param $args
     * @return Builder
     */
    public function resolveQueryBuilder($root, $args) {
        if (!$this->queryBuilderResolver) {
            return null;
        }

        $args = func_get_args();
        return call_user_func_array($this->queryBuilderResolver, $args);
    }
}