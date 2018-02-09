<?php

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class GlobalGraphCommand extends Command
{
    protected function configure()
    {
      $this
      ->setName('global')
      ->setDescription('First command')
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

class FileGraphCommand extends Command
{
    protected function configure()
    {
      $this
      ->setName('file')
      ->setDescription('Search for all files that include a given dependency')
      ->setHelp('This is help')
  ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // ...
        $generator = new PhpDependencyGraphGenerator();
        echo $generator->fileSearchGraph();
    }
}

?>
