<?php
require_once __DIR__.'/../vendor/autoload.php';

use Silex\Application;
use Silex\Provider\DoctrineServiceProvider;
use Knp\Provider\ConsoleServiceProvider;
use Symfony\Component\HttpFoundation\Request;
use Project\Travian\VillageSearch;
use Project\Travian\activeServers;
use Project\Travian\allServers;
use Project\Travian\guildService;
use Project\Travian\playerService;
use Project\Travian\serverDataService;
use Project\Travian\tableServise;

$app = new Silex\Application(); 
$app['debug'] = false;

$app->register(new DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'   => 'pdo_mysql',
        'host' => 'localhost',
        'port' => 3306,
        'dbname' => 'erkki',
        'user' => 'l3',
        'password' => 'userpassu',
        'charset' => 'utf8',
    ),
));

$app->register(new ConsoleServiceProvider(), array(
    'console.name'              => 'Project',
    'console.version'           => '1.0.0',
    'console.project_directory' => __DIR__.'/..'
));

$app['tableServise'] = $app->share(function (Application $app) {
    return new tableServise($app['db']);
});


//####### allservers #######
$app['allServers'] = $app->share(function (Application $app) {
    return new allServers($app['db'], $app['tableServise']);
});
$app->get('/api/travian/server/list/all',function(Application $app){
    return json_encode($app['allServers']->getServersList());
});
//### server data update ###
$app['dataupdate'] = $app->share(function (Application $app) {
    return new serverDataService($app['db'], $app['tableServise'], $app['activeservers']);
});
//###### guild search ######
$app['guildService'] = $app->share(function (Application $app) {
    return new guildService($app['db']);
});
//##### player search ######
$app['playerService'] = $app->share(function (Application $app) {
    return new playerService($app['db']);
});
$app->get('/api/travian/search/player/{name}',function(Application $app, $name){
  echo $name;
  return true;
});

$app->post('/api/test',function(Request $request){
  $data = json_decode($request->getContent());
  return json_encode($data);
});
/*
$app->post('/api/travian/search/player/',function(Application $app, Request $request){
  $data = json_decode($request->getContent());
  if($data->server == 'Select server') return 'No players';
  return json_encode($app['playerService']->playerByName($data->server,$data->name));
});
 */
//#### Search villages #####
$app['vil_search'] = $app->share(function (Application $app) {
    return new VillageSearch($app['db'], $app['tableServise']);
});

$app->post('/api/travian/search',function(Application $app, Request $request){
  $data = json_decode($request->getContent());
  if($data->server == 'Select server') return 'No villages';
  $app['vil_search']->setData(json_decode($request->getContent()));
  return json_encode($app['vil_search']->searchVillages());
});
//##### active servers #####
$app['activeservers'] = $app->share(function (Application $app) {
    return new activeServers($app['db'], $app['tableServise']);
});
       
$app->get('/api/travian/server/list',function(Application $app){
    return json_encode($app['activeservers']->getServers());
});
//##########################

return $app;
