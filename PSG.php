<?php
// application.php

require __DIR__.'/vendor/autoload.php';
require 'Commands.php';
require 'PhpDependencyGraphGenerator.php';

use Symfony\Component\Console\Application;

$application = new Application();

// ... register commands
$application->add(new GlobalGraphCommand());

$application->run();




?>
