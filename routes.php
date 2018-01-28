<?php


/**
 * Register Graphql routes before all user routes.
 */
Event::listen('backend.beforeRoute', function () {
    $schemaParameterPattern = '/\{\s*graphql\_schema\s*\?\s*\}/';

    $graphQLRouter = function ($router) use($schemaParameterPattern) {
        //Get routes from config
        $routes = config('Nurdiansyah.Graphql::routes');
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
        $controllers = config('Nurdiansyah.Graphql::controllers', '\Nurdiansyah\Graphql\GraphQLController@query');
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
                    'as' => 'Nurdiansyah.Graphql::query',
                    'uses' => $queryController
                ));
                $router->get(preg_replace($schemaParameterPattern, '{graphql_schema}', $queryRoute), array(
                    'as' => 'Nurdiansyah.Graphql::query.with_schema',
                    'uses' => $queryController
                ));
                $router->post(preg_replace($schemaParameterPattern, '', $queryRoute), array(
                    'as' => 'Nurdiansyah.Graphql::query.post',
                    'uses' => $queryController
                ));
                $router->post(preg_replace($schemaParameterPattern, '{graphql_schema}', $queryRoute), array(
                    'as' => 'Nurdiansyah.Graphql::query.post.with_schema',
                    'uses' => $queryController
                ));
            } else {
                $router->get($queryRoute, array(
                    'as' => 'Nurdiansyah.Graphql::query',
                    'uses' => $queryController
                ));
                $router->post($queryRoute, array(
                    'as' => 'Nurdiansyah.Graphql::query.post',
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
                    'as' => 'Nurdiansyah.Graphql::mutation',
                    'uses' => $mutationController
                ));
                $router->post(preg_replace($schemaParameterPattern, '{graphql_schema}', $mutationRoute), array(
                    'as' => 'Nurdiansyah.Graphql::mutation.with_schema',
                    'uses' => $mutationController
                ));
                $router->get(preg_replace($schemaParameterPattern, '', $mutationRoute), array(
                    'as' => 'Nurdiansyah.Graphql::mutation.get',
                    'uses' => $mutationController
                ));
                $router->get(preg_replace($schemaParameterPattern, '{graphql_schema}', $mutationRoute), array(
                    'as' => 'Nurdiansyah.Graphql::mutation.get.with_schema',
                    'uses' => $mutationController
                ));
            } else {
                $router->post($mutationRoute, array(
                    'as' => 'Nurdiansyah.Graphql::mutation',
                    'uses' => $mutationController
                ));
                $router->get($mutationRoute, array(
                    'as' => 'Nurdiansyah.Graphql::mutation.get',
                    'uses' => $mutationController
                ));
            }
        }
    };

    /*
     * Extensibility
     */
    Event::fire('Nurdiansyah.Graphql::beforeRoute');
    /*
     * Other pages
     */
    Route::group([
        'prefix' => config('Nurdiansyah.Graphql::prefix'),
        'middleware' => config('Nurdiansyah.Graphql::middleware', [])
    ], $graphQLRouter);
    /*
     * Graphiql
     */
    //GraphiQL
    $graphiQL = Config::get('Nurdiansyah.Graphql::graphiql', true);
    if ($graphiQL) {
        Route::group([], function ($router) use ($schemaParameterPattern) {
            $graphiQLRoute = config('Nurdiansyah.Graphql::graphiql.routes', 'graphiql');
            $graphiQLController = config('Nurdiansyah.Graphql::graphiql.controller', '\Nurdiansyah\Graphql\GraphQLController@graphiql');

            if (!$router instanceof \Illuminate\Routing\Router &&
                preg_match($schemaParameterPattern, $graphiQLRoute)
            ) {
                $router->get(preg_replace($schemaParameterPattern, '', $graphiQLRoute), [
                    'as' => 'Nurdiansyah.Graphql::graphiql',
                    'uses' => $graphiQLController
                ]);
                $router->get(preg_replace($schemaParameterPattern, '{graphql_schema}', $graphiQLRoute), [
                    'as' => 'Nurdiansyah.Graphql::graphiql.with_schema',
                    'uses' => $graphiQLController
                ]);
            } else {
                $router->get($graphiQLRoute, [
                    'as' => 'Nurdiansyah.Graphql::graphiql',
                    'middleware' => config('Nurdiansyah.Graphql::graphiql.middleware', []),
                    'uses' => $graphiQLController
                ]);
            }
        });
    }

    /*
     * Extensibility
     */
    Event::fire('Nurdiansyah.Graphql::route');
});
