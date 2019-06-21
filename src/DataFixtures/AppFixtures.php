<?php

namespace App\DataFixtures;

use App\Service\MapService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;
use App\Entity\City;
use App\Entity\Location;
use App\Entity\State;
use App\Entity\Event;
use App\Entity\User;
use App\Entity\Campus;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    private $passwordEncoder;
    private $mapService;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, MapService $mapService)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->mapService = $mapService;
    }

    public function load(ObjectManager $manager)
    {

        // le faker permet de créer de fausses données
        $faker = Faker\Factory::create('fr_FR');

        $statelabels = ['en création', 'annulé', 'publié'];
        $states = [];
        foreach ($statelabels as $label) {
            $state = new State();
            $state->setName($label);
            $manager->persist($state);
            $states[] = $state;
        }

//        $cities = [];
//        for ($i = 0; $i < 10; $i++) {
//            $city = new City();
//            $city->setName($faker->city);
//            $city->setPostCode($faker->postcode);
//            $manager->persist($city);
//            $cities[] = $city;
//        }
//
//        $locations = [];
//        for ($i = 0; $i < 20; $i++) {
//            $location = new Location();
//            $location->setName($faker->company);
//            $location->setCity($cities[mt_rand(0, 9)]);
//            $location->setAddress($faker->streetAddress);
//            $location->setLatitude($faker->latitude);
//            $location->setLongitude($faker->longitude);
//            $manager->persist($location);
//            $locations[] = $location;
//        }

        //campus
        $campuslabels = ['Rennes', 'Nantes', 'Quimper', 'Niort'];
        $campuses = [];
        foreach ($campuslabels as $label) {
            $campus = new Campus();
            $campus->setName($label);
            $manager->persist($campus);
            $campuses[] = $campus;
        }


        //user
        $users = [];
        for ($i = 0; $i < 20; $i++) {
            $user = new User();
            $user->setUsername($faker->userName);
            $user->setLastName($faker->lastName);
            $user->setFirstName($faker->firstName);
            $user->setPhone($faker->phoneNumber);
            $user->setEmail($faker->email);
            $user->setPassword(
                $this->passwordEncoder->encodePassword(
                    $user,
                    'password'
                )
            );
            $user->setRoles(['ROLE_USER']);
            $user->setActive(true);
            $user->setCampus($campuses[mt_rand(0, 3)]);
            $users[] = $user;
            $manager->persist($user);
        }


        $usernames = ['user', 'admin', 'super','user2','user3'];
        $roles = ['ROLE_USER', 'ROLE_ADMIN', 'ROLE_SUPER_ADMIN','user','user'];
        $passwords =['user', 'stephane', 'stephane','user2','user3'];

        for ($i = 0; $i < count($usernames); $i++) {
            $user = new User();
            $user->setUsername($usernames[$i]);
            $user->setLastName($faker->lastName);
            $user->setFirstName($faker->firstName);
            $user->setPhone($faker->phoneNumber);
            $user->setEmail($faker->email);
            $user->setPassword(
                $this->passwordEncoder->encodePassword(
                    $user,
                    $passwords[$i]
                )
            );
            $user->setRoles([$roles[$i]]);
            $user->setActive(true);
            $user->setCampus($campuses[mt_rand(0, 3)]);
            $users[] = $user;
            $manager->persist($user);
        }

        $user = new User();
        $user->setUsername('Batiste');
        $user->setLastName($faker->lastName);
        $user->setFirstName($faker->firstName);
        $user->setPhone($faker->phoneNumber);
        $user->setEmail($faker->email);
        $user->setPassword(
            $this->passwordEncoder->encodePassword(
                $user,
                'inactif'
            )
        );
        $user->setRoles([$roles[0]]);
        $user->setActive(false);
        $user->setCampus($campuses[mt_rand(0, 3)]);
        $users[] = $user;
        $manager->persist($user);






        //$addresses=[];
        $cities = ['Rennes', 'Nantes', 'Quimper', 'Paris', 'Toulouse',
            'Lorient', 'Lyon', 'Tour', 'Angers', 'Lille',
            'Marseille', 'Rouen', 'Poitiers', 'Limoges', 'Dijon',
            'Valence', 'Bayonne','Brest','Dunkerque','Montpellier'];
        $eventNamePart1 = ['Sortie à ', 'Allons à ', 'Visite de ', 'Fête à ','Let\'s go to '];
        $eventDescriptionPart1= ['Partons tous à pour le week-end à ', 'Allons nous amuser à ', 'Rendez-vous à l\'école puis on part tous en bus pour aller à ', 'Fête à '];
        for ($i = 0; $i < count($cities); $i++) {
            $event = new Event();
            $event->setName($eventNamePart1[mt_rand(0, count($eventNamePart1) - 1)] . $cities[$i]);
            $event->setDescription($eventDescriptionPart1[mt_rand(0, count($eventDescriptionPart1) - 1)] . $cities[$i]);
            $event->setCampus($campuses[mt_rand(0, 3)]);
            $event->setCreator($users[mt_rand(0, 22)]);
            $minutes = strval(mt_rand(1, 59)) . ' minutes';
            $event->setDuration(\DateInterval::createFromDateString($minutes));
            $event->setNbMax(mt_rand(1, 20));
            $date1 = $faker->dateTimeBetween('-40days', '+40days');

            $dateString = date_format($date1,'Y-m-d');
            //$date2 = $faker->dateTimeBetween('-40days', '+40days');
            $date2=$faker->dateTimeInInterval($dateString,'-7days');
            if ($date1 > $date2) {
                $event->setStart($date1);
                $event->setLimitDate($date2);
            } else {
                $event->setStart($date2);
                $event->setLimitDate($date1);
            }
            //$event->setLocation($locations[mt_rand(0, 19)]);
            $event->setLocation($this->mapService->geocodeAddress('mairie', $cities[$i]));


            $event->setState($states[mt_rand(0, 2)]);
            $manager->persist($event);
        }

        $manager->flush();
    }
}
