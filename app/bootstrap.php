<?php
require_once __DIR__.'/../vendor/autoload.php';

use Silex\Provider\DoctrineServiceProvider;
use Silex\Application;
use Project\Travian\VillageSearch;
use Project\Travian\ServerList;

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

$app['servers'] = $app->share(function (Application $app) {
    return new ServerList($app['db']);
});

$app['vil_search'] = $app->share(function (Application $app) {
    return new VillageSearch($app['db']);
});





$app->get('/api/travian/serverlist/',function(Application $app){
    return $app['servers']->getServers();
});
$app->get('/api/travian/{server}/setname/{name}',function(Application $app, $server, $name){
    return $app['servers']->updateName($server, $name);
});
$app->get('/api/travian/{server}/setaddress/{address}',function(Application $app, $server, $address){
    return $app['servers']->updateAddress($server, $address);
});


$app->get('/api/travian/{server}/search/',function(Application $app, $server){
    $app['vil_search']->setServer($server);
    return $app['vil_search']->getsome();
});





$app->get('/api/', function () use ($app){

    $statement = $app['db']->prepare('SELECT * FROM tx3');
    $statement->execute();
    $users = $statement->fetchAll();
    
    return  json_encode($users);
});



return $app;