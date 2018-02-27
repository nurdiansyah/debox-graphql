<?php

namespace Debox\Graphql\Relay;

use Debox\Graphql\GraphQLService;
use Debox\Graphql\Relay\Field\ConnectionFieldImplement;
use Debox\Graphql\Relay\Type\ConnectionType;
use Illuminate\Contracts\Foundation\Application;

class RelayService {

    /**
     * @var Application
     */
    protected $app;


    /**
     * @var GraphQLService
     */
    protected $graphql;

    public function __construct($app) {
        $this->app = $app;
        $this->graphql = $app['graphql'];
    }

    public function connectionField($config = []) {
        $field = new ConnectionFieldImplement($config);
        return $field;
    }

    /**
     * @param $edgeType
     * @param array $config
     * @return ConnectionFieldImplement
     * @throws \Debox\Graphql\Exception\TypeNotFound
     */
    public function connectionFieldFromEdgeType($edgeType, $config = []) {
        $typeName = array_get($edgeType->config, 'name');
        $connectionName = array_get($config, 'connectionTypeName', str_plural($typeName) . 'Connection');
        $connectionType = new ConnectionType([
            'name' => $connectionName
        ]);
        $connectionType->setEdgeType($edgeType);
        $this->graphql->addType($connectionType, $connectionName);
        $fieldConfig = array_except($config, ['connectionTypeName']);
        $field = new ConnectionFieldImplement($fieldConfig);
        $field->setType($this->graphql->type($connectionName));
        return $field;
    }

    /**
     * @param $edgeType
     * @param $queryBuilderResolver
     * @param array $config
     * @return ConnectionFieldImplement
     * @throws \Debox\Graphql\Exception\TypeNotFound
     */
    public function connectionFieldFromEdgeTypeAndQueryBuilder($edgeType, $queryBuilderResolver, $config = []) {
        $field = $this->connectionFieldFromEdgeType($edgeType, $config);
        $field->setQueryBuilderResolver($queryBuilderResolver);
        return $field;
    }

    public function toGlobalId($type, $id) {
        return base64_encode($type . ':' . $id);
    }

    public function fromGlobalId($globalId) {
        $id = explode(':', base64_decode($globalId), 2);
        return sizeof($id) === 2 ? [
            'type' => $id[0],
            'id' => $id[1]
        ] : null;
    }

    public function getIdFromGlobalId($globalId) {
        $id = $this->fromGlobalId($globalId);
        return $id ? $id['id'] : null;
    }

    public function getTypeFromGlobalId($globalId) {
        $id = $this->fromGlobalId($globalId);
        return $id ? $id['type'] : null;
    }
}