<?php

namespace Project\command;

use Knp\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Doctrine\DBAL\Connection;

use Project\Travian\activeServers;
use Project\Travian\tableServise;

class RemoveServerCommand extends Command{
    
    protected function configure(){
        $this
            ->setName('tra:remove')
            ->setDescription('Remove server.')
            ->addArgument( 'address',
                InputArgument::REQUIRED,
                'Server address like (tx3.travian.fi)?');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output){
      $active = $this->getSilexApplication()['activeservers'];
      $tableService = $this->getSilexApplication()['tableServise'];
      $address = $input->getArgument('address');
      
      $server = $active->getServerName($address);
      if(!$server)
        return $output->writeln("This server address does not exist! Check yout typing.");
         
      $active->deleteServer($server['id']);
      $tableService->DropTable(str_replace('.','',$address));
      
      $output->writeln("<info>Server ".$address." is removed from system.</info>");
      
    }
}
?>
