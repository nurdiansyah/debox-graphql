<?php
namespace Debox\Graphql\Relay\Type;

use Debox\Graphql\Support\GraphQLType;
use Debox\Graphql\Relay\Traits\HasClientMutationIdField;

class InputType extends GraphQLType {
    use HasClientMutationIdField;
    protected $inputObject = true;
}
