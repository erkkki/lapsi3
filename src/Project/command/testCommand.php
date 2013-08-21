<?php

namespace Project\command;

use Knp\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Doctrine\DBAL\Connection;

use Project\Travian\tableServise;

class testcommand extends Command{
  
    protected function configure(){
        $this
            ->setName('test:console')
            ->setDescription('Test sql connection etc.');
    }

    protected function execute(InputInterface $input, OutputInterface $output){
      try{
        $tableService = $this->getSilexApplication()['tableServise'];
        $tableService->tableExists('random');
      } catch (Exception $e){
        $output->writeln("No sql connection. $e");
      }
      $output->writeln("Sql connection ok.");
      
      
      
      
    }
}
?>
