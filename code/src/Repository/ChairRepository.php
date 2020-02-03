<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\Chair;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Chair|null find($id, $lockMode = null, $lockVersion = null)
 * @method Chair|null findOneBy(array $criteria, array $orderBy = null)
 * @method Chair[]    findAll()
 * @method Chair[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChairRepository extends ServiceEntityRepository
{
    /**
     * ChairRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Chair::class);
    }
}
