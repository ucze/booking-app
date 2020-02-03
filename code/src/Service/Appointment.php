<?php declare(strict_types=1);

namespace App\Service;

use App\Command\Appointment\Book as BookAppointmentCommand;
use App\Entity\Appointment as AppointmentEntity;
use App\Exception\ApiException;
use App\Form\BookAppointmentType;
use App\Repository\AppointmentRepository;
use App\Repository\ChairRepository;
use App\Repository\TimeSlotRepository;
use App\Util\ValidationErrors;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\FormFactoryInterface;

/**
 * Class Appointment
 * @package App\Service
 */
class Appointment
{
    /**
     * @var FormFactoryInterface
     */
    protected FormFactoryInterface $formFactory;
    /**
     * @var ChairRepository
     */
    protected ChairRepository $chairRepository;
    /**
     * @var TimeSlotRepository
     */
    protected TimeSlotRepository $timeSlotRepository;
    /**
     * @var ValidationErrors
     */
    protected ValidationErrors $validationErrorsHelper;
    /**
     * @var AppointmentRepository
     */
    protected AppointmentRepository $appointmentRepository;

    /**
     * Appointment constructor.
     * @param FormFactoryInterface $formFactory
     * @param AppointmentRepository $appointmentRepository
     * @param ChairRepository $chairRepository
     * @param TimeSlotRepository $timeSlotRepository
     * @param ValidationErrors $validationErrorsHelper
     */
    public function __construct(FormFactoryInterface $formFactory, AppointmentRepository $appointmentRepository, ChairRepository $chairRepository, TimeSlotRepository $timeSlotRepository, ValidationErrors $validationErrorsHelper)
    {
        $this->formFactory = $formFactory;
        $this->chairRepository = $chairRepository;
        $this->timeSlotRepository = $timeSlotRepository;
        $this->validationErrorsHelper = $validationErrorsHelper;
        $this->appointmentRepository = $appointmentRepository;
    }

    /**
     * @param array $data
     * @throws \Exception
     */
    public function bookAppointment(array $data)
    {
        $bookAppointmentCommand = new BookAppointmentCommand();
        $form = $this->formFactory->create(BookAppointmentType::class, $bookAppointmentCommand);
        $form->submit($data);
        if (!$form->isValid()) {
            $this->validationErrorsHelper->handle($form);
        }

        // Todo: Refactor, add transformer/handler
        $chair = $this->chairRepository->find($bookAppointmentCommand->chair_id);
        if (!$chair) {
            throw new ApiException('Chair doesn\'t exists');
        }
        $bookAppointmentCommand->chair = $chair;
        $slots = new ArrayCollection();
        foreach (explode(',', $bookAppointmentCommand->slot_ids) as $slotId) {
            $slot = $this->timeSlotRepository->find($slotId);
            if (!$slot) {
                throw new ApiException('Time slot doesn\'t exists');
            }
            $slots->add($slot);
        }
        $bookAppointmentCommand->slots = $slots;
        $this->appointmentRepository->save(AppointmentEntity::fromBookAppointmentCommand($bookAppointmentCommand));
    }

    /**
     * @param DateTimeInterface $date
     * @param int $chairId
     * @return array
     */
    public function listAppointments(DateTimeInterface $date, int $chairId)
    {
        $chair = $this->chairRepository->find($chairId);
        if (!$chair) {
            throw new ApiException('Chair doesn\'t exists');
        }
        $takenSlots = $this->timeSlotRepository->listTakenSlotsByDateAndChair($date, $chairId);
        $slots = [];
        foreach ($takenSlots as $takenSlot) {
            $time = sprintf('%s - %s', $takenSlot['ts_startsAt']->format('H:i'), $takenSlot['ts_endsAt']->format('H:i'));
            $slots[] = ['appointment_id' => $takenSlot['a_id'], 'client' => $takenSlot['a_client'], 'time' => $time];
        }
        return $slots;
    }
}