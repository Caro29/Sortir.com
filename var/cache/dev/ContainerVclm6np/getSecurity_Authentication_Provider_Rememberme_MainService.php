<?php

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.
// Returns the private 'security.authentication.provider.rememberme.main' shared service.

include_once $this->targetDirs[3].'\\vendor\\symfony\\security\\Core\\Authentication\\Provider\\AuthenticationProviderInterface.php';
include_once $this->targetDirs[3].'\\vendor\\symfony\\security\\Core\\Authentication\\Provider\\RememberMeAuthenticationProvider.php';
include_once $this->targetDirs[3].'\\vendor\\symfony\\security\\Core\\User\\UserCheckerInterface.php';
include_once $this->targetDirs[3].'\\vendor\\symfony\\security\\Core\\User\\UserChecker.php';

return $this->services['security.authentication.provider.rememberme.main'] = new \Symfony\Component\Security\Core\Authentication\Provider\RememberMeAuthenticationProvider(${($_ = isset($this->services['security.user_checker']) ? $this->services['security.user_checker'] : ($this->services['security.user_checker'] = new \Symfony\Component\Security\Core\User\UserChecker())) && false ?: '_'}, $this->getEnv('APP_SECRET'), 'main');
