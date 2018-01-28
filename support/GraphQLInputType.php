<?php

namespace Debox\Graphql\Support;

use GraphQL\Type\Definition\InputObjectType;

class GraphQLInputType extends GraphQLType
{
    public function toType()
    {
        return new InputObjectType($this->toArray());
    }
}
