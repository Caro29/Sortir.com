<?php

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.
// Returns the private 'doctrine_migrations.rollup_command' shared service.

include_once $this->targetDirs[3].'\\vendor\\symfony\\console\\Command\\Command.php';
include_once $this->targetDirs[3].'\\vendor\\doctrine\\migrations\\lib\\Doctrine\\Migrations\\Tools\\Console\\Command\\AbstractCommand.php';
include_once $this->targetDirs[3].'\\vendor\\doctrine\\migrations\\lib\\Doctrine\\Migrations\\Tools\\Console\\Command\\RollupCommand.php';
include_once $this->targetDirs[3].'\\vendor\\doctrine\\doctrine-migrations-bundle\\Command\\MigrationsRollupDoctrineCommand.php';

return $this->services['doctrine_migrations.rollup_command'] = new \Doctrine\Bundle\MigrationsBundle\Command\MigrationsRollupDoctrineCommand();
