<?php

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.
// Returns the private 'Bazinga\GeocoderBundle\Command\GeocodeCommand' shared service.

include_once $this->targetDirs[3].'\\vendor\\symfony\\console\\Command\\Command.php';
include_once $this->targetDirs[3].'\\vendor\\symfony\\framework-bundle\\Command\\ContainerAwareCommand.php';
include_once $this->targetDirs[3].'\\vendor\\willdurand\\geocoder-bundle\\Command\\GeocodeCommand.php';

$this->services['Bazinga\GeocoderBundle\Command\GeocodeCommand'] = $instance = new \Bazinga\GeocoderBundle\Command\GeocodeCommand(${($_ = isset($this->services['Geocoder\ProviderAggregator']) ? $this->services['Geocoder\ProviderAggregator'] : $this->load('getProviderAggregatorService.php')) && false ?: '_'});

$instance->setName('geocoder:geocode');

return $instance;