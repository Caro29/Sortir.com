<?php

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.
// Returns the private 'service_locator.pyphub4' shared service.

return $this->services['service_locator.pyphub4'] = new \Symfony\Component\DependencyInjection\ServiceLocator(['cityRepository' => function () {
    $f = function (\App\Repository\CityRepository $v = null) { return $v; }; return $f(${($_ = isset($this->services['App\Repository\CityRepository']) ? $this->services['App\Repository\CityRepository'] : $this->load('getCityRepositoryService.php')) && false ?: '_'});
}]);
