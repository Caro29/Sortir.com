<?php

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.
// Returns the private 'service_locator.foocbb0' shared service.

return $this->services['service_locator.foocbb0'] = new \Symfony\Component\DependencyInjection\ServiceLocator(['passwordEncoder' => function () {
    return ${($_ = isset($this->services['security.password_encoder']) ? $this->services['security.password_encoder'] : $this->load('getSecurity_PasswordEncoderService.php')) && false ?: '_'};
}]);
