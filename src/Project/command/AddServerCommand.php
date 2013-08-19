<?php

namespace Project\command;

use Knp\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Doctrine\DBAL\Connection;

use Project\Travian\activeServers;
use Project\Travian\serverDataService;

class AddServerCommand extends Command{
    
    protected function configure(){
        $this
            ->setName('tra:add')
            ->setDescription('Add active server. Use --update when update is not needed.')
            ->addArgument( 'address',
                InputArgument::REQUIRED,
                'Server address like (tx3.travian.fi)?')
            ->addOption( 'update',
               null,
               InputOption::VALUE_NONE,
               'If not set, server data will not update after added.'
            );
    }
    
    protected function execute(InputInterface $input, OutputInterface $output){
      $active = $this->getSilexApplication()['activeservers'];
      $dataService = $this->getSilexApplication()['dataupdate'];
      $address = $input->getArgument('address');
      
      $active->addServer($address);
      $output->writeln("Server ".$address." was added in active servers.");
      
      if (!$input->getOption('update')) {
        $start = time();
        $output->write("updating: <info>".$address."</info>");
        try {
          $dataService->updateServerData($address);
        } catch (Exception $e){
          $output->writeln("<error> ... failed to update ".$address.".</error>");
        }
        $output->writeln(" Time taken: ".(time()-$start).".s");
      }
    }
}
?>
