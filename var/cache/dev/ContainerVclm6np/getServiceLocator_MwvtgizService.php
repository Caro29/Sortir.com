<?php

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.
// Returns the private 'service_locator.mwvtgiz' shared service.

return $this->services['service_locator.mwvtgiz'] = new \Symfony\Component\DependencyInjection\ServiceLocator(['campusRepository' => function () {
    $f = function (\App\Repository\CampusRepository $v = null) { return $v; }; return $f(${($_ = isset($this->services['App\Repository\CampusRepository']) ? $this->services['App\Repository\CampusRepository'] : $this->load('getCampusRepositoryService.php')) && false ?: '_'});
}]);
