<?php

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.
// Returns the private 'service_locator.fsvj3yw' shared service.

return $this->services['service_locator.fsvj3yw'] = new \Symfony\Component\DependencyInjection\ServiceLocator(['stateRepository' => function () {
    $f = function (\App\Repository\StateRepository $v = null) { return $v; }; return $f(${($_ = isset($this->services['App\Repository\StateRepository']) ? $this->services['App\Repository\StateRepository'] : $this->load('getStateRepositoryService.php')) && false ?: '_'});
}]);