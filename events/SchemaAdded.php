<?php namespace debox\graphql\events;

class SchemaAdded
{
    public $schema;
    public $name;
    
    public function __construct($schema, $name)
    {
        $this->schema = $schema;
        $this->name = $name;
    }
}
