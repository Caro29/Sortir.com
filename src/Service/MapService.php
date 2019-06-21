<?php


namespace App\Service;

use App\Entity\City;
use App\Entity\Event;
use App\Entity\Location;
use Doctrine\ORM\EntityManager;
use Geocoder\Exception\Exception;
use Geocoder\Provider\Nominatim\Model\NominatimAddress;
use Geocoder\Provider\Provider;
use Geocoder\Query\GeocodeQuery;
use Symfony\Component\HttpFoundation\Request;

class MapService
{
    private $geocodingProvider;
    private $entityManager;

    public function __construct(Provider $geocodingProvider, EntityManager $entityManager)
    {
        $this->geocodingProvider = $geocodingProvider;
        $this->entityManager = $entityManager;
    }

    // geocode une adresse et renvoie l'objet location correspondant
    public function geocodeAddress(string $address, string $city)
    {

        $address = $address . ' ' . $city;
        try {
            $locations =  $this->geocodingProvider->geocodeQuery(GeocodeQuery::create($address));
            return $this->makeLocation($locations->get(0));

        } catch (Exception $e) {
            //dump($e->getMessage());
            return null;
        }
    }

    // crée un objet location (et sa ville si elle n'existe pas) à partir d'une adresse nominatim
    private function makeLocation(NominatimAddress $nominatimAddress)
    {
        $locationRepository = $this->entityManager->getRepository(Location::class);
        $locationName = $nominatimAddress->getDisplayName();
        $location = $locationRepository->findOneBy(['name' => $locationName]);

        if ($location != null) {
            return $location;
        }

        $location = new Location();
        $location->setName($locationName);

        $streetNumber = $nominatimAddress->getStreetNumber() != null ? $nominatimAddress->getStreetNumber() . " " : "";
        $street = $nominatimAddress->getStreetName();
        $address = $streetNumber . $street;
        if($address==null){
            return null;
        }
        $location->setAddress($address);

        // dealing with the city

        $cityRepository = $this->entityManager->getRepository(City::class);
        $cityName = $nominatimAddress->getLocality();
        $postCode = $nominatimAddress->getPostalCode();
        if($cityName == null || $postCode==null){
            return null;
        }
        $city = $cityRepository->findOneBy(['name' => $cityName, 'postCode' => $postCode]);

        if ($city == null) {
            $city = new City();
            $city->setName($cityName);
            $city->setPostCode($postCode);
            $this->entityManager->persist($city);
        }

        $location->setCity($city);
        $location->setLatitude($nominatimAddress->getCoordinates()->getLatitude());
        $location->setLongitude($nominatimAddress->getCoordinates()->getLongitude());

        $this->entityManager->persist($location);
        $this->entityManager->flush();

        return $location;
    }
}