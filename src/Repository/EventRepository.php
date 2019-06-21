<?php

namespace App\Repository;

use App\Entity\Campus;
use App\Entity\Event;
use App\Entity\State;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function updateState($request, $state)
    {
        $querybuilder = $this->createQueryBuilder('event');

        $querybuilder->update(Event::class, 'e')
            ->set('e.state', '?1')
            ->where('e.id = ?2')
            ->setParameter(1, $state->getId())
            ->setParameter(2, $request->get('id'));

        $querybuilder->getQuery()->execute();
    }

    public function displayWithoutFilter(?User $user)
    {
        $query = $this->createQueryBuilder('event');
        $query->leftJoin('event.state', 's');

        // On retire les event enregistrés non publiés des autres users
        $query->where('s.name != :name OR event.creator = :user')
            ->setParameter('name', 'en création')
            ->setParameter('user', $user);

        // On n'affiche pas les sorties passées
        $query->andWhere('event.start > :date_limit')
            ->setParameter('date_limit', new DateTime(), \Doctrine\DBAL\Types\Type::DATETIME);

        // Trier les résultats par date limite d'inscription
        $query->orderBy('event.limitDate', 'ASC');

        return $query->getQuery()->getResult();
    }

    public function filter(?User $user, ?Campus $campus, ?String $search, ?DateTime $minDate, ?DateTime $maxDate, $organiser, $isParticipant, $isNotParticipant, $isArchived)
    {
        $query = $this->createQueryBuilder('event');
        $query->leftJoin('event.state', 's');

        // Filtre campus
        if ($campus != null) {
            $query->innerJoin('event.campus', 'campus', 'WITH', 'campus = :campus')
                ->setParameter('campus', $campus);
        }

        $query->where('s.name != :name OR event.creator = :user')
            ->setParameter('name', 'en création')
            ->setParameter('user', $user);

        // Filtre date
        if ($minDate != null && $maxDate != null) {
            $query->andWhere($query->expr()->between('event.start', ':date_from', ':date_to'))
                ->setParameter('date_from', $minDate, \Doctrine\DBAL\Types\Type::DATETIME)
                ->setParameter('date_to', $maxDate, \Doctrine\DBAL\Types\Type::DATETIME);
        }

        // Filtres checkboxes

        if (!$organiser) {
            $query->andWhere('event.creator != :user')
                ->setParameter('user', $user);
        }
        if (!$isParticipant) {
            $query->andWhere(':user NOT MEMBER OF event.participants')
                ->setParameter('user', $user);
        }

        if (!$isNotParticipant) {
            $query->andWhere(':user MEMBER OF event.participants')
                ->setParameter('user', $user);
        }

        if (!$isNotParticipant && $organiser) {
            $query->andWhere(':user MEMBER OF event.participants')
                ->setParameter('user', $user);
            $query->orWhere('event.creator = :user')
                ->setParameter('user', $user);
        }

        if ($isArchived) {
            $dateLimit = new DateTime('-30 day'); // On n'affiche jamais les sorties qui ont plus d'un mois
        } else {
            $dateLimit = new DateTime();
        }

        $query->andWhere('event.start > :date_limit')
            ->setParameter('date_limit', $dateLimit, \Doctrine\DBAL\Types\Type::DATETIME);


        // Filtre search
        if ($search != null) {
            $query->andWhere('event.name LIKE :search')
                ->setParameter('search', '%' . $search . '%');
        }

        // Trier les résultats par date limite d'inscription
        $query->orderBy('event.limitDate', 'ASC');

        return $query->getQuery()->getResult();
    }
}
