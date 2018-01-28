<?php

namespace debox\graphql\support;

use Validator;
use debox\graphql\error\ValidationError;
use debox\graphql\support\traits\ShouldValidate;

class GraphQLMutation extends GraphQLField
{
    use ShouldValidate;
}
