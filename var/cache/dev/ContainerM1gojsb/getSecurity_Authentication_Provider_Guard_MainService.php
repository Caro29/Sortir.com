<?php

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.
// Returns the private 'security.authentication.provider.guard.main' shared service.

include_once $this->targetDirs[3].'\\vendor\\symfony\\security\\Core\\Authentication\\Provider\\AuthenticationProviderInterface.php';
include_once $this->targetDirs[3].'\\vendor\\symfony\\security\\Guard\\Provider\\GuardAuthenticationProvider.php';
include_once $this->targetDirs[3].'\\vendor\\symfony\\security\\Core\\User\\UserCheckerInterface.php';
include_once $this->targetDirs[3].'\\vendor\\symfony\\security\\Core\\User\\UserChecker.php';

return $this->services['security.authentication.provider.guard.main'] = new \Symfony\Component\Security\Guard\Provider\GuardAuthenticationProvider(new RewindableGenerator(function () {
    yield 0 => ${($_ = isset($this->services['App\Security\Authenticator']) ? $this->services['App\Security\Authenticator'] : $this->load('getAuthenticatorService.php')) && false ?: '_'};
}, 1), ${($_ = isset($this->services['security.user.provider.concrete.app_user_provider']) ? $this->services['security.user.provider.concrete.app_user_provider'] : $this->load('getSecurity_User_Provider_Concrete_AppUserProviderService.php')) && false ?: '_'}, 'main', ${($_ = isset($this->services['security.user_checker']) ? $this->services['security.user_checker'] : ($this->services['security.user_checker'] = new \Symfony\Component\Security\Core\User\UserChecker())) && false ?: '_'});
