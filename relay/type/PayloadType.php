<?php
namespace Debox\Graphql\Relay\Type;

use Debox\Graphql\Support\GraphQLType;
use Debox\Graphql\Relay\Traits\HasClientMutationIdField;

class PayloadType extends GraphQLType {
    use HasClientMutationIdField;
}