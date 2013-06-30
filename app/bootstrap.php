<?php
require_once __DIR__.'/../vendor/autoload.php';

use Silex\Application;
use Silex\Provider\DoctrineServiceProvider;
use Symfony\Component\HttpFoundation\Request;
use Project\Travian\VillageSearch;
use Project\Travian\tableServise;
use Project\Travian\activeServers;
use Project\Travian\allServers;
use Project\Travian\serverDataService;

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
$app['tableServise'] = $app->share(function (Application $app) {
    return new tableServise($app['db']);
});

//### allservers ###

$app['allServers'] = $app->share(function (Application $app) {
    return new allServers($app['db'], $app['tableServise']);
});

$app->get('/api/travian/server/updateservers/',function(Application $app){
    return json_encode($app['allServers']->updateServers());
});
$app->get('/api/travian/server/list/all/',function(Application $app){
    return json_encode($app['allServers']->getServersList());
});
//######################
//### active servers ###

$app['activeservers'] = $app->share(function (Application $app) {
    return new activeServers($app['db'], $app['tableServise']);
});
         
$app->get('/api/travian/server/list/',function(Application $app){
    return json_encode($app['activeservers']->getServers());
});
$app->get('/api/travian/server/by/id/{id}/',function(Application $app, $id){
    return json_encode($app['activeservers']->getServerId($id));
});
$app->get('/api/travian/server/by/name/{name}/',function(Application $app, $name){
    return json_encode($app['activeservers']->getServerName($name));
});
$app->post('/api/travian/server/add/',function(Application $app, Request $request){
    $data = json_decode($request->getContent());
    return json_encode($app['activeservers']->addServer($data));
});
$app->post('/api/travian/server/editserver/',function(Application $app, Request $request){
    $data = json_decode($request->getContent());
    return json_encode($app['activeservers']->updateServer($data));
});
$app->get('/api/travian/server/delete/{id}',function(Application $app, $id){
    $server = $app['activeservers']->getServerId($id);
    $app['activeservers']->deleteServer($id);    
    $app['tableServise']->DropTable(str_replace('.','',$server['address']));
    return true;
});
//##########################
//### server data update ###
$app['dataupdate'] = $app->share(function (Application $app) {
    return new serverDataService($app['db'], $app['tableServise'], $app['activeservers']);
});

$app->get('/api/travian/server/update/{id}',function(Application $app, $id){
    return $app['dataupdate']->updateServerData($id);
});
//##########################
//#### Search villages #####

$app->post('/api/travian/search',function(Application $app, Request $request){
    $data = json_decode($request->getContent());
    if($data->server == null) return true;
    $app['vil_search']->setData(json_decode($request->getContent()));
    return json_encode($app['vil_search']->getVillages());
});
$app->post('/api/travian/search/player/',function(Application $app, Request $request){
    $data = json_decode($request->getContent());
    $temp = $app['vil_search']->getPlayer($data->key,$data->server);
    $players = array();
    foreach ($temp as $line) {
          array_push($players, $line['player']);
      }
    return json_encode($players);
});
$app->post('/api/travian/search/playerbyname/',function(Application $app, Request $request){
    $data = json_decode($request->getContent());
    $temp = $app['vil_search']->getPlayer($data->key,$data->server);
    return json_encode($temp); 
});


//##########################
$app->post('/api/travian/test/',function(Application $app, Request $request){
    $data = json_decode($request->getContent());
    echo "<pre>";
    var_dump($data);
    echo "</pre><br>";
    return true;
});
$app->get('/api/travian/test2/{id}',function(Application $app, $id){
    return json_encode($app['vil_search']->getPlayer($id,"tx3.travian.fi"));
});

return $app;