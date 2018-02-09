<?php

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class GlobalGraphCommand extends Command
{
    protected function configure()
    {
      $this
      // the name of the command (the part after "bin/console")
      ->setName('global')

      // the short description shown while running "php bin/console list"
      ->setDescription('First command')

      // the full command description shown when running the command with
      // the "--help" option
      ->setHelp('This is foo help')
  ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // ...
        $generator = new PhpDependencyGraphGenerator();
        echo $generator->generateGlobalGraph();
    }
}

?>
