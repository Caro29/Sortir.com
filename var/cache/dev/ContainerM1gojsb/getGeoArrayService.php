<?php

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.
// Returns the public 'Geocoder\Dumper\GeoArray' shared service.

include_once $this->targetDirs[3].'\\vendor\\willdurand\\geocoder\\Dumper\\AbstractArrayDumper.php';
include_once $this->targetDirs[3].'\\vendor\\willdurand\\geocoder\\Dumper\\Dumper.php';
include_once $this->targetDirs[3].'\\vendor\\willdurand\\geocoder\\Dumper\\GeoArray.php';

return $this->services['Geocoder\Dumper\GeoArray'] = new \Geocoder\Dumper\GeoArray();
