<?php

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.
// Returns the private 'service_locator.1oiawak' shared service.

return $this->services['service_locator.1oiawak'] = new \Symfony\Component\DependencyInjection\ServiceLocator(['eventRepository' => function () {
    $f = function (\App\Repository\EventRepository $v = null) { return $v; }; return $f(${($_ = isset($this->services['App\Repository\EventRepository']) ? $this->services['App\Repository\EventRepository'] : $this->load('getEventRepositoryService.php')) && false ?: '_'});
}]);
