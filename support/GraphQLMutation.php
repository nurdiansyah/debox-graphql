<?php

namespace Debox\Graphql\Support;

use Validator;
use Debox\Graphql\Error\ValidationError;
use Debox\Graphql\Support\Traits\ShouldValidate;

class GraphQLMutation extends GraphQLField
{
    use ShouldValidate;
}
