<?php

namespace Project\command;

use Knp\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Doctrine\DBAL\Connection;

use Project\Travian\activeServers;

class ShowActiveSCommand extends Command{
    
    protected function configure(){
        $this
            ->setName('tra:list')
            ->setDescription('Active servers list.');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output){
      $active = $this->getSilexApplication()['activeservers'];
      $servers =  $active->getServers();
      
      if(count($servers) == 0){
        return $output->writeln("No active servers.");
      }
      $output->writeln("Active servers count: " . count($servers).".");      
      foreach ($servers as $server) {
        $output->writeln("Server <info>".$server['address']."</info> Id: <info>".$server['id'].".</info> Last update time: <info>".$server['humantime'].".</info>");
      }
    }
}
?>
