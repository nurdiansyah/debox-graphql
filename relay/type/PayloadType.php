<?php
namespace Nurdiansyah\Graphql\Relay\Type;

use Nurdiansyah\Graphql\Support\GraphQLType;
use Nurdiansyah\Graphql\Relay\Traits\HasClientMutationIdField;

class PayloadType extends GraphQLType {
    use HasClientMutationIdField;
}