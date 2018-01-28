<?php
namespace Nurdiansyah\Graphql\Controllers;


use Backend\Classes\Controller;
use Backend\Facades\BackendMenu;
use InvalidArgumentException;

class Graphiql extends Controller {


    /**
     * Graphiql constructor.
     */
    public function __construct() {
        parent::__construct();
        BackendMenu::setContext('Nurdiansyah.Graphql', 'graphql-menu');
    }

    public function index() {
        try {
            $hasRoute = route('graphql.query');
        } catch (InvalidArgumentException $e) {
            $hasRoute = false;
        }

        $schema = array_key_exists('graphql_schema', $this->params)? $this->params['graphql_schema'] : false ;

        if (! empty($schema)) {
            $this->vars['graphqlPath'] = $hasRoute ? route('graphql.query', ['graphql_schema' => $schema]) : url('/graphql/' . $schema);
        } else {
            $this->vars['graphqlPath'] = $hasRoute ? route('graphql.query') : url('/api');
        }
    }

}