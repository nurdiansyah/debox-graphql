<?php
namespace Nurdiansyah\Graphql\Relay\Type;

use Nurdiansyah\Graphql\Support\GraphQLType;
use Nurdiansyah\Graphql\Relay\Traits\HasClientMutationIdField;

class InputType extends GraphQLType {
    use HasClientMutationIdField;
    protected $inputObject = true;
}
