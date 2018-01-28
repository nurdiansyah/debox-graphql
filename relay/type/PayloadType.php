<?php
namespace debox\graphql\relay\type;

use debox\graphql\support\GraphQLType;
use debox\graphql\relay\traits\HasClientMutationIdField;

class PayloadType extends GraphQLType {
    use HasClientMutationIdField;
}