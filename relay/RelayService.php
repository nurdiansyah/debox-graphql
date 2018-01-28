<?php
namespace Nurdiansyah\Graphql\Relay;

use Nurdiansyah\Graphql\GraphQLService;
use Nurdiansyah\Graphql\Relay\Field\ConnectionField;
use Nurdiansyah\Graphql\Relay\Type\ConnectionType;
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