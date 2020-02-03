<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\TimeSlot;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TimeSlot|null find($id, $lockMode = null, $lockVersion = null)
 * @method TimeSlot|null findOneBy(array $criteria, array $orderBy = null)
 * @method TimeSlot[]    findAll()
 * @method TimeSlot[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TimeSlotRepository extends ServiceEntityRepository
{
    /**
     * TimeSlotRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TimeSlot::class);
    }

    /**
     * @param $date
     * @return array
     */
    public function listTakenSlotsByDate($date)
    {
        return $this->listTakenSlots($date);
    }

    /**
     * @param $date
     * @param $chair
     * @return array
     */
    public function listTakenSlotsByDateAndChair($date, $chair)
    {
        return $this->listTakenSlots($date, $chair);
    }

    /**
     * @param DateTimeInterface|null $date
     * @param null $chair
     * @return array
     */
    protected function listTakenSlots(DateTimeInterface $date = null, $chair = null)
    {
        $qb = $this->createQueryBuilder('ts')
            ->addSelect('PARTIAL c.{id}')
            ->addSelect('PARTIAL a.{id,client}')
            ->innerJoin('ts.appointments', 'a')
            ->innerJoin('a.chair', 'c')
            ->orderBy('ts.id', 'ASC');

        if (!empty($date)) {
            $qb = $qb->andWhere('a.date = :date')
                ->setParameter('date', $date->format('Y-m-d'));
        };

        if (!empty($chair)) {
            $qb = $qb->andWhere('c.id = :chair')
                ->setParameter('chair', $chair);
        };

        return $qb->setMaxResults(500)
            ->getQuery()->getScalarResult();

    }
}
