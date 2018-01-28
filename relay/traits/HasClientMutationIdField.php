<?php

namespace Nurdiansyah\Graphql\Relay\Traits;

use GraphQL\Type\Definition\Type;

trait HasClientMutationIdField {
    public function getFields() {
        $fields = parent::getFields();
        
        $fields['clientMutationId'] = [
            'type' => Type::nonNull(Type::string())
        ];
        
        return $fields;
    }
}
