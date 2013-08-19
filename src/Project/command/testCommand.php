<?php

namespace Project\command;

use Knp\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Doctrine\DBAL\Connection;

class testcommand extends Command{
  
    protected function configure(){
        $this
            ->setName('test:console')
            ->setDescription('Test console');
    }

    protected function execute(InputInterface $input, OutputInterface $output){
        $output->writeln("Short command test!");
    }
}
?>
