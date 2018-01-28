<?php
namespace debox\graphql\relay\traits;

use GraphQL\Type\Definition\Type;

trait ArgsConnection {
    protected function connectionArgs() {
        return [
            'first' => [
                'name' => 'first',
                'type' => Type::int()
            ],
            'last' => [
                'name' => 'last',
                'type' => Type::int()
            ],
            'after' => [
                'name' => 'after',
                'type' => Type::string()
            ],
            'before' => [
                'name' => 'before',
                'type' => Type::string()
            ]
        ];
    }

    public function args() {
        $args = parent::args();
        $connectionArgs = $this->connectionArgs();
        return array_merge($connectionArgs, $args);
    }
}
