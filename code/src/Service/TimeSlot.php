<?php

namespace App\Service;

use App\Repository\ChairRepository;
use App\Repository\TimeSlotRepository;

/**
 * Class TimeSlot
 * @package App\Service
 */
class TimeSlot
{
    /**
     * @var TimeSlotRepository
     */
    protected TimeSlotRepository $timeSlotRepository;
    /**
     * @var ChairRepository
     */
    protected ChairRepository $chairRepository;

    /**
     * TimeSlot constructor.
     * @param TimeSlotRepository $timeSlotRepository
     * @param ChairRepository $chairRepository
     */
    public function __construct(TimeSlotRepository $timeSlotRepository, ChairRepository $chairRepository)
    {
        $this->timeSlotRepository = $timeSlotRepository;
        $this->chairRepository = $chairRepository;
    }

    /**
     * @param $date
     * @return array
     */
    public function listAvailableSlots($date)
    {
        $chairSlots = [];
        $takenSlots = $this->timeSlotRepository->listTakenSlotsByDate($date);
        $allSlots = $this->timeSlotRepository->findAll();
        $chairs = $this->chairRepository->findAll();
        // Prepare list of available slots
        foreach ($chairs as $chair) {
            $chairSlots[$chair->getId()] = [];
            $chairSlots[$chair->getId()]['owner'] = $chair->getOwner();
            $chairSlots[$chair->getId()]['chair_id'] = $chair->getId();
            $chairSlots[$chair->getId()]['available_slots'] = [];
            foreach ($allSlots as $slot) {
                $time = sprintf('%s - %s', $slot->getStartsAt()->format('H:i'), $slot->getEndsAt()->format('H:i'));
                $chairSlots[$chair->getId()]['available_slots'][$slot->getId()] = ['id' => $slot->getId(), 'time' => $time];
            }
        }
        // Unset booked slots
        foreach ($takenSlots as $takenSlot) {
            unset($chairSlots[$takenSlot['c_id']]['available_slots'][$takenSlot['ts_id']]);
        }

        foreach ($chairSlots as &$chairSlot) {
            $chairSlot['available_slots'] = array_values($chairSlot['available_slots']);
        }
        return array_values($chairSlots);
    }
}