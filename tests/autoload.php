<?php
include_once __DIR__.'/../vendor/autoload.php';
include_once __DIR__.'/../Plugin.php';
include_once __DIR__.'/../GraphQLService.php';
include_once __DIR__.'/../Controller.php';
include_once __DIR__.'/../GraphQLController.php';

$classLoader = new \Composer\Autoload\ClassLoader();
$classLoader->addPsr4('Debox\\Graphql\\Relay\\', __DIR__.'/../relay/', true);
$classLoader->addPsr4('Debox\\Graphql\\Controllers\\', __DIR__.'/../controllers/', true);
$classLoader->addPsr4('Debox\\Graphql\\Error\\', __DIR__.'/../error/', true);
$classLoader->addPsr4('Debox\\Graphql\\Exception\\', __DIR__.'/../exception/', true);
$classLoader->addPsr4('Debox\\Graphql\\Support\\', __DIR__.'/../support/', true);
$classLoader->addPsr4('Debox\\Graphql\\Events\\', __DIR__.'/../events/', true);
$classLoader->addPsr4('Debox\\Tests\\Mocks\\', __DIR__.'/../tests/mocks/', true);

$classLoader->register();
