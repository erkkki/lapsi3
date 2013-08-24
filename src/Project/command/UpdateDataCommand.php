<?php

namespace Project\command;

use Knp\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Doctrine\DBAL\Connection;

use Project\Travian\activeServers;
use Project\Travian\serverDataService;
use Project\Travian\allServers;

class UpdateDataCommand extends Command{
    
    protected function configure(){
        $this
            ->setName('tra:update')
            ->setDescription('Update active servers.');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output){
      $dataService = $this->getUpdateService();
      $activeService = $this->getActiveServersService();
      $allServerService = $this->getAllServersService();
      
      $allServerService->updateServers();
      $allServers = $allServerService->getServersList();
      foreach ($allServers as $server) {
        $activeService->addServer($server['address']);
      }
      
      $servers =  $activeService->getServers();
      $start = time();
      if(count($servers) == 0){
        return $output->writeln("No active servers.");
      }
      $output->writeln("Active servers count: " . count($servers).".");      
      foreach ($servers as $server) {
        if($allServerService->isServerInList($server['address']) == null){
          $output->writeln('Server ' . $server['address'] . ' no more active removing..');
          $temp = $active->getServerName($server['address']);
          $active->deleteServer($temp['id']);
          $tableService->DropTable(str_replace('.','',$server['address']));
          $output->writeln('Server ' . $server['address'] . ' removed.');
        } else {
          $sTime = time();
          $output->write("updating: <info>".$server['address']."</info>");
          try {
            $dataService->updateServerData($server['address']);
            $output->writeln(" ... done! Time taken: " . (time()-$sTime).".s");
          } catch (Exception $e){
            $output->writeln("<error> ... failed to update ".$server['address'].".</error>");
          }
        }
      }
      $output->writeln("Time taken: ".(time()-$start).".s");
    }
    
    protected function getUpdateService(){
        return $this->getSilexApplication()['dataupdate'];
    }
    protected function getAllServersService(){
        return $this->getSilexApplication()['allServers'];
    }
    protected function getActiveServersService(){
        return $this->getSilexApplication()['activeservers'];
    }
}
?>
