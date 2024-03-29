<?php

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.
// Returns the private 'App\Security\Authenticator' shared autowired service.

include_once $this->targetDirs[3].'\\vendor\\symfony\\security\\Http\\EntryPoint\\AuthenticationEntryPointInterface.php';
include_once $this->targetDirs[3].'\\vendor\\symfony\\security\\Guard\\GuardAuthenticatorInterface.php';
include_once $this->targetDirs[3].'\\vendor\\symfony\\security\\Guard\\AuthenticatorInterface.php';
include_once $this->targetDirs[3].'\\vendor\\symfony\\security\\Guard\\AbstractGuardAuthenticator.php';
include_once $this->targetDirs[3].'\\vendor\\symfony\\security\\Http\\Util\\TargetPathTrait.php';
include_once $this->targetDirs[3].'\\vendor\\symfony\\security\\Guard\\Authenticator\\AbstractFormLoginAuthenticator.php';
include_once $this->targetDirs[3].'\\src\\Security\\Authenticator.php';

return $this->services['App\Security\Authenticator'] = new \App\Security\Authenticator(${($_ = isset($this->services['doctrine.orm.default_entity_manager']) ? $this->services['doctrine.orm.default_entity_manager'] : $this->load('getDoctrine_Orm_DefaultEntityManagerService.php')) && false ?: '_'}, ${($_ = isset($this->services['router']) ? $this->services['router'] : $this->getRouterService()) && false ?: '_'}, ${($_ = isset($this->services['security.csrf.token_manager']) ? $this->services['security.csrf.token_manager'] : $this->load('getSecurity_Csrf_TokenManagerService.php')) && false ?: '_'}, ${($_ = isset($this->services['security.password_encoder']) ? $this->services['security.password_encoder'] : $this->load('getSecurity_PasswordEncoderService.php')) && false ?: '_'});
