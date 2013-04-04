<?php
require_once __DIR__.'/../vendor/autoload.php';

use Silex\Provider\DoctrineServiceProvider;
use Silex\Application;
use Project\Travian\VillageSearch;

$app = new Silex\Application();
$app['debug'] = true;

$app->register(new DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'   => 'pdo_mysql',
        'host' => 'localhost',
        'port' => 3306,
        'dbname' => 'erkki',
        'user' => 'root',
        'password' => 'userpassu',
        'charset' => 'utf8',
    ),
));

$app['vil_search'] = $app->share(function (Application $app) {
    return new VillageSearch($app['db']);
});

$app->get('/api/travian/search/',function(Application $app){
    return $app['vil_search']->getsome();
});



$app->get('/api/', function () use ($app){

    $statement = $app['db']->prepare('SELECT * FROM tx3');
    $statement->execute();
    $users = $statement->fetchAll();
    
    return  json_encode($users);
});



return $app;