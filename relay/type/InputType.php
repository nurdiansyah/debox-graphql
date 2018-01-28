<?php
namespace debox\graphql\relay\type;

use debox\graphql\support\GraphQLType;
use debox\graphql\relay\traits\HasClientMutationIdField;

class InputType extends GraphQLType {
    use HasClientMutationIdField;
    protected $inputObject = true;
}
