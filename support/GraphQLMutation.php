<?php

namespace Nurdiansyah\Graphql\Support;

use Validator;
use Nurdiansyah\Graphql\Error\ValidationError;
use Nurdiansyah\Graphql\Support\Traits\ShouldValidate;

class GraphQLMutation extends GraphQLField
{
    use ShouldValidate;
}
