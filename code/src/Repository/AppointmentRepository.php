<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\Appointment;
use App\Entity\TimeSlot;
use App\Exception\ChairTakenException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * @method Appointment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Appointment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Appointment[]    findAll()
 * @method Appointment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AppointmentRepository extends ServiceEntityRepository
{
    /**
     * AppointmentRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Appointment::class);
    }

    /**
     * @param Appointment $appointment
     * @throws \Exception
     */
    public function save(Appointment $appointment)
    {
        $this->_em->getConnection()->beginTransaction();
        try {
            $this->_em->persist($appointment);
            $this->_em->flush();
            $similarAppointments = $this->findSimilarAppointments(
                $appointment->getChair()->getId(),
                $appointment->getDate()->format('Y-m-d'),
                $appointment->getId()
            );
            if (!empty($similarAppointments)) {
                $slotIds = $appointment->getTimeSlots()->map(function ($timeSlot) {
                    /** @var TimeSlot $timeSlot */
                    return $timeSlot->getId();
                });
                /** @var Appointment $similarAppointment */
                foreach ($similarAppointments as $similarAppointment) {
                    $similarAppointmentTimeSlotIds = $similarAppointment->getTimeSlots()->map(function ($timeSlot) {
                        /** @var TimeSlot $timeSlot */
                        return $timeSlot->getId();
                    });
                    foreach ($slotIds as $slotId) {
                        if ($similarAppointmentTimeSlotIds->contains($slotId)) {
                            throw new ChairTakenException();
                        }
                    }
                }
            }
            $this->_em->getConnection()->commit();
        } catch (Exception $e) {
            $this->_em->getConnection()->rollBack();
            throw $e;
        }
    }

    /**
     * @param $chairId
     * @param $date
     * @param int $id
     * @return mixed
     */
    public function findSimilarAppointments($chairId, $date, $id = 0)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.chair = :chair')
            ->andWhere('a.date = :date')
            ->andWhere('a.id != :id')
            ->setParameter('chair', $chairId)
            ->setParameter('date', $date)
            ->setParameter('id', $id)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }
}
