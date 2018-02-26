<?php


/**
 * Register Graphql routes before all user routes.
 */
Event::listen('backend.beforeRoute', function () {
    $schemaParameterPattern = '/\{\s*graphql\_schema\s*\?\s*\}/';

    $graphQLRouter = function ($router) use($schemaParameterPattern) {
        //Get routes from config
        $routes = config('debox.graphql::routes');
        $queryRoute = null;
        $mutationRoute = null;
        if (is_array($routes)) {
            $queryRoute = array_get($routes, 'query', null);
            $mutationRoute = array_get($routes, 'mutation', null);
        } else {
            $queryRoute = $schemaParameterPattern;
            $mutationRoute = $routes;
        }

        //Get controllers from config
        $controllers = config('debox.graphql::controllers', '\Debox\Graphql\GraphQLController@query');
        $queryController = null;
        $mutationController = null;
        if (is_array($controllers)) {
            $queryController = array_get($controllers, 'query', null);
            $mutationController = array_get($controllers, 'mutation', null);
        } else {
            $queryController = $controllers;
            $mutationController = $controllers;
        }

        //Query
        if ($queryRoute) {
            // Remove optional parameter in Lumen. Instead, creates two routes.
            if (!$router instanceof \Illuminate\Routing\Router &&
                preg_match($schemaParameterPattern, $queryRoute)
            ) {
                $router->get(preg_replace($schemaParameterPattern, '', $queryRoute), array(
                    'as' => 'Debox.Graphql::query',
                    'uses' => $queryController
                ));
                $router->get(preg_replace($schemaParameterPattern, '{graphql_schema}', $queryRoute), array(
                    'as' => 'Debox.Graphql::query.with_schema',
                    'uses' => $queryController
                ));
                $router->post(preg_replace($schemaParameterPattern, '', $queryRoute), array(
                    'as' => 'Debox.Graphql::query.post',
                    'uses' => $queryController
                ));
                $router->post(preg_replace($schemaParameterPattern, '{graphql_schema}', $queryRoute), array(
                    'as' => 'Debox.Graphql::query.post.with_schema',
                    'uses' => $queryController
                ));
            } else {
                $router->get($queryRoute, array(
                    'as' => 'Debox.Graphql::query',
                    'uses' => $queryController
                ));
                $router->post($queryRoute, array(
                    'as' => 'Debox.Graphql::query.post',
                    'uses' => $queryController
                ));
            }
        }

        //Mutation routes (define only if different than query)
        if ($mutationRoute && $mutationRoute !== $queryRoute) {
            // Remove optional parameter in Lumen. Instead, creates two routes.
            if (!$router instanceof \Illuminate\Routing\Router &&
                preg_match($schemaParameterPattern, $mutationRoute)
            ) {
                $router->post(preg_replace($schemaParameterPattern, '', $mutationRoute), array(
                    'as' => 'Debox.Graphql::mutation',
                    'uses' => $mutationController
                ));
                $router->post(preg_replace($schemaParameterPattern, '{graphql_schema}', $mutationRoute), array(
                    'as' => 'Debox.Graphql::mutation.with_schema',
                    'uses' => $mutationController
                ));
                $router->get(preg_replace($schemaParameterPattern, '', $mutationRoute), array(
                    'as' => 'Debox.Graphql::mutation.get',
                    'uses' => $mutationController
                ));
                $router->get(preg_replace($schemaParameterPattern, '{graphql_schema}', $mutationRoute), array(
                    'as' => 'Debox.Graphql::mutation.get.with_schema',
                    'uses' => $mutationController
                ));
            } else {
                $router->post($mutationRoute, array(
                    'as' => 'Debox.Graphql::mutation',
                    'uses' => $mutationController
                ));
                $router->get($mutationRoute, array(
                    'as' => 'Debox.Graphql::mutation.get',
                    'uses' => $mutationController
                ));
            }
        }
    };

    /*
     * Extensibility
     */
    Event::fire('Debox.Graphql::beforeRoute');
    /*
     * Other pages
     */
    Route::group([
        'prefix' => config('debox.graphql::prefix'),
        'middleware' => config('debox.graphql::middleware', [])
    ], $graphQLRouter);
    /*
     * Graphiql
     */
    //GraphiQL
    $graphiQL = Config::get('Debox.Graphql::graphiql', true);
    if ($graphiQL) {
        Route::group([], function ($router) use ($schemaParameterPattern) {
            $graphiQLRoute = config('debox.graphql::graphiql.routes', 'graphiql');
            $graphiQLController = config('debox.graphql::graphiql.controller', '\Debox\Graphql\GraphQLController@graphiql');

            if (!$router instanceof \Illuminate\Routing\Router &&
                preg_match($schemaParameterPattern, $graphiQLRoute)
            ) {
                $router->get(preg_replace($schemaParameterPattern, '', $graphiQLRoute), [
                    'as' => 'Debox.Graphql::graphiql',
                    'uses' => $graphiQLController
                ]);
                $router->get(preg_replace($schemaParameterPattern, '{graphql_schema}', $graphiQLRoute), [
                    'as' => 'Debox.Graphql::graphiql.with_schema',
                    'uses' => $graphiQLController
                ]);
            } else {
                $router->get($graphiQLRoute, [
                    'as' => 'Debox.Graphql::graphiql',
                    'middleware' => config('debox.graphql::graphiql.middleware', []),
                    'uses' => $graphiQLController
                ]);
            }
        });
    }

    /*
     * Extensibility
     */
    Event::fire('Debox.Graphql::route');
});
