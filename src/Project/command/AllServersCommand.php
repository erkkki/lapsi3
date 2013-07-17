<?php

namespace Project\command;

use Knp\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Doctrine\DBAL\Connection;

use Project\Travian\allServers;

class AllServersCommand extends Command{
  
    protected function configure(){
        $this
            ->setName('tra:allservers')
            ->setDescription('Test console');
    }

    protected function execute(InputInterface $input, OutputInterface $output){
        $output->writeln("Loading servers....");
        $output->writeln("Server count: ".count($this->getAllServers()->updateServers()));
    }
    
    protected function getAllServers(){
        return $this->getSilexApplication()['allServers'];
    }
}
?>
