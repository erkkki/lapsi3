<?php
require_once __DIR__.'/../vendor/autoload.php';

use Silex\Provider\DoctrineServiceProvider;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Project\Travian\VillageSearch;
use Project\Travian\tableServise;
use Project\Travian\serverService;

use Project\Travian\PlayerService;

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


$app['Player'] = $app->share(function (Application $app) {
    return new PlayerService($app['db']);
});
$app['vil_search'] = $app->share(function (Application $app) {
    return new VillageSearch($app['db'], $app['Player']);
});


//############ Server api #############


$app['serverService'] = $app->share(function (Application $app) {
    return new ServerService($app['db'], $app['tableServise']);
});
$app['tableServise'] = $app->share(function (Application $app) {
    return new tableServise($app['db']);
});

$app->get('/api/travian/server/list/',function(Application $app){
    return json_encode($app['serverService']->getServerList());
});
$app->get('/api/travian/server/list/all/',function(Application $app){
    return json_encode($app['serverService']->getAllList());
});
$app->get('/api/travian/server/by/{id}/',function(Application $app, $id){
    return json_encode($app['serverService']->getServerId($id));
});
$app->get('/api/travian/server/by/{id}/',function(Application $app, $id){
    return json_encode($app['serverService']->getServerName($id));
});
$app->post('/api/travian/server/add/',function(Application $app, Request $request){
    return $app['serverService']->addServer(json_decode($request->getContent()));
});
$app->get('/api/travian/server/delete/{id}',function(Application $app, $id){
    return $app['serverService']->deleteServer($id);
});
$app->get('/api/travian/server/updateservers/',function(Application $app){
    return $app['serverService']->updateServerlist();
});
$app->post('/api/travian/server/editserver/',function(Application $app, Request $request){
    return $app['serverService']->updateServer(json_decode($request->getContent()));
});
$app->get('/api/travian/server/update/{id}',function(Application $app, $id){
    return $app['serverService']->updateServerData($id);
});

//#####################################

$app->get('/api/test/',function(Application $app){
    return $app['tableServise']->createActiveServers();
});



$app->post('/api/travian/search',function(Application $app, Request $request){
    $data = json_decode($request->getContent());
    if($data->{server} == null) return true;
    $app['vil_search']->setData(json_decode($request->getContent()));
    return $app['vil_search']->getVillages2();
});
return $app;