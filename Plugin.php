<?php namespace Debox\Graphql;

use Backend\Facades\Backend;
use Debox\Graphql\Console\EnumMakeCommand;
use Debox\Graphql\Console\FieldMakeCommand;
use Debox\Graphql\Console\InterfaceMakeCommand;
use Debox\Graphql\Console\MutationMakeCommand;
use Debox\Graphql\Console\QueryMakeCommand;
use Debox\Graphql\Console\ScalarMakeCommand;
use Debox\Graphql\Console\TypeMakeCommand;
use Debox\Graphql\Events\SchemaAdded;
use Debox\Graphql\Relay\RelayServiceProvider;
use GraphQL\Validator\DocumentValidator;
use System\Classes\PluginBase;

/**
 * Graphql Plugin Information File
 */
class Plugin extends PluginBase {
    private $relayServiceProvider;

    /**
     * Plugin constructor.
     */
    public function __construct($application) {
        parent::__construct($application);
        $this->relayServiceProvider = new RelayServiceProvider($application);
    }


    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails() {
        return [
            'name' => 'Graphql',
            'description' => 'graphql support',
            'author' => 'Debox',
            'icon' => 'icon-leaf'
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register() {
        $this->registerGraphQL();
        $this->relayServiceProvider->register();
        $this->registerConsole();
    }

    /**
     * Boot method, called right before the request route.
     *
     * @return void
     */
    public function boot() {
        $this->bootRouter();
        $this->relayServiceProvider->boot();
    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents() {
        return []; // Remove this line to activate
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions() {
        return []; // Remove this line to activate
    }

    /**
     * Registers back-end navigation items for this plugin.
     *
     * @return array
     */
    public function registerNavigation() {
        return [
            'graphql-menu' => [
                'label' => 'Graphql',
                'url' => Backend::url('Debox/Graphql/graphiql'),
                'icon' => 'icon-leaf',
                'permissions' => ['Debox.Graphql.*'],
                'order' => 500,
            ],
        ];
    }

    protected function getRouter() {
        return $this->app['router'];
    }

    /**
     * Bootstrap events
     *
     * @param GraphQLService $graphql
     * @return void
     */
    private function registerEventListeners(GraphQLService $graphql) {
        // Update the schema route pattern when schema is added
        $this->app['events']->listen(SchemaAdded::class, function () use ($graphql) {
            $router = $this->getRouter();
            if (method_exists($router, 'pattern')) {
                $schemaNames = array_keys($graphql->getSchemas());
                $router->pattern('graphql_schema', '(' . implode('|', $schemaNames) . ')');
            }
        });
    }

    /**
     * Register GraphQL facade
     *
     * @return void
     */
    protected function registerGraphQL() {
        $this->app->singleton('graphql', function ($app) {
            $graphql = new GraphQLService($app);
            $this->addTypes($graphql);
            $this->addSchemas($graphql);
            $this->registerEventListeners($graphql);
            $this->applySecurityRules();
            return $graphql;
        });
    }

    /**
     * Register console commands
     *
     * @return void
     */
    private function registerConsole() {
        $this->registerConsoleCommand('graphql:type {name}', TypeMakeCommand::class);
        $this->registerConsoleCommand('graphql:query {name}', QueryMakeCommand::class);
        $this->registerConsoleCommand('graphql:mutation {name}', MutationMakeCommand::class);
        $this->registerConsoleCommand('graphql:enum {name}', EnumMakeCommand::class);
        $this->registerConsoleCommand('graphql:field {name}', FieldMakeCommand::class);
        $this->registerConsoleCommand('graphql:interface {name}', InterfaceMakeCommand::class);
        $this->registerConsoleCommand('graphql:scalar {name}', ScalarMakeCommand::class);
    }

    /**
     * Bootstrap router
     *
     * @return void
     */
    private function bootRouter() {
        if ($this->app['config']->get('Debox.Graphql::routes') && !$this->app->routesAreCached()) {
            include __DIR__ . '/routes.php';
        }
    }

    /**
     * Add types from config
     *
     * @param GraphQLService $graphql
     * @return void
     */
    protected function addTypes(GraphQLService $graphql) {
        $types = $this->app['config']->get('debox.graphql::types', []);

        foreach ($types as $name => $type) {
            $graphql->addType($type, is_numeric($name) ? null : $name);
        }
    }

    /**
     * Add schemas from config
     *
     * @param GraphQLService $graphql
     * @return void
     */
    protected function addSchemas(GraphQLService $graphql) {
        $schemas = $this->app['config']->get('debox.graphql::schemas', []);

        foreach ($schemas as $name => $schema) {
            $graphql->addSchema($name, $schema);
        }
    }

    /**
     * Configure security from config
     *
     * @return void
     */
    protected function applySecurityRules() {
        $maxQueryComplexity = config('debox.graphql::security.query_max_complexity');
        if ($maxQueryComplexity !== null) {
            /** @var QueryComplexity $queryComplexity */
            $queryComplexity = DocumentValidator::getRule('QueryComplexity');
            $queryComplexity->setMaxQueryComplexity($maxQueryComplexity);
        }

        $maxQueryDepth = config('debox.graphql::security.query_max_depth');
        if ($maxQueryDepth !== null) {
            /** @var QueryDepth $queryDepth */
            $queryDepth = DocumentValidator::getRule('QueryDepth');
            $queryDepth->setMaxQueryDepth($maxQueryDepth);
        }

        $disableIntrospection = config('debox.graphql::security.disable_introspection');
        if ($disableIntrospection === true) {
            /** @var DisableIntrospection $disableIntrospection */
            $disableIntrospection = DocumentValidator::getRule('DisableIntrospection');
            $disableIntrospection->setEnabled(DisableIntrospection::ENABLED);
        }
    }
}
