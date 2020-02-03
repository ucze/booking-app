<?php declare(strict_types=1);

namespace App\Entity;

use App\Command\Appointment\Book as BookAppointmentCommand;
use DateTimeInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AppointmentRepository")
 */
class Appointment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $client;

    /**
     * @ORM\Column(type="date")
     */
    private DateTimeInterface $date;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\TimeSlot", inversedBy="appointments")
     */
    private Collection $timeSlots;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Chair", inversedBy="appointments")
     * @ORM\JoinColumn(nullable=false)
     */
    private Chair $chair;

    /**
     * Appointment constructor.
     * @param string $client
     * @param DateTimeInterface $date
     * @param Chair $chair
     * @param Collection $timeSlots
     */
    public function __construct(string $client, DateTimeInterface $date, Chair $chair, Collection $timeSlots)
    {
        $this->client = $client;
        $this->date = $date;
        $this->chair = $chair;
        $this->timeSlots = $timeSlots;
    }

    /**
     * @param BookAppointmentCommand $bookAppointmentCommand
     * @return Appointment
     */
    public static function fromBookAppointmentCommand(BookAppointmentCommand $bookAppointmentCommand): Appointment
    {
        return new self(
            $bookAppointmentCommand->client,
            $bookAppointmentCommand->date,
            $bookAppointmentCommand->chair,
            $bookAppointmentCommand->slots
        );
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getClient(): ?string
    {
        return $this->client;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getDate(): ?DateTimeInterface
    {
        return $this->date;
    }

    /**
     * @return Collection|TimeSlot[]
     */
    public function getTimeSlots(): Collection
    {
        return $this->timeSlots;
    }

    /**
     * @return Chair
     */
    public function getChair(): ?Chair
    {
        return $this->chair;
    }
}
