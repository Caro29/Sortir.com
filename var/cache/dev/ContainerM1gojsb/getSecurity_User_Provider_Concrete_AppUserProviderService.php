<?php

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.
// Returns the private 'security.user.provider.concrete.app_user_provider' shared service.

include_once $this->targetDirs[3].'\\vendor\\symfony\\security\\Core\\User\\UserProviderInterface.php';
include_once $this->targetDirs[3].'\\vendor\\symfony\\doctrine-bridge\\Security\\User\\EntityUserProvider.php';

return $this->services['security.user.provider.concrete.app_user_provider'] = new \Symfony\Bridge\Doctrine\Security\User\EntityUserProvider(${($_ = isset($this->services['doctrine']) ? $this->services['doctrine'] : $this->getDoctrineService()) && false ?: '_'}, 'App\\Entity\\User', NULL, NULL);
