<?php

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.
// Returns the private 'service_locator.8hyb8un' shared service.

return $this->services['service_locator.8hyb8un'] = new \Symfony\Component\DependencyInjection\ServiceLocator(['cancelEventRepository' => function () {
    $f = function (\App\Repository\CancelEventRepository $v = null) { return $v; }; return $f(${($_ = isset($this->services['App\Repository\CancelEventRepository']) ? $this->services['App\Repository\CancelEventRepository'] : $this->load('getCancelEventRepositoryService.php')) && false ?: '_'});
}]);
