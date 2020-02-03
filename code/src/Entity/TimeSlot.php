<?php declare(strict_types=1);

namespace App\Entity;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TimeSlotRepository")
 */
class TimeSlot
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="time")
     */
    private DateTimeInterface $startsAt;

    /**
     * @ORM\Column(type="time")
     */
    private DateTimeInterface $endsAt;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Appointment", mappedBy="timeSlots")
     */
    private Collection $appointments;

    /**
     * TimeSlot constructor.
     * @param DateTimeInterface $startsAt
     * @param DateTimeInterface $endsAt
     */
    public function __construct(DateTimeInterface $startsAt, DateTimeInterface $endsAt)
    {
        $this->appointments = new ArrayCollection();
        $this->startsAt = $startsAt;
        $this->endsAt = $endsAt;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return DateTimeInterface
     */
    public function getStartsAt(): DateTimeInterface
    {
        return $this->startsAt;
    }

    /**
     * @return DateTimeInterface
     */
    public function getEndsAt(): DateTimeInterface
    {
        return $this->endsAt;
    }

    /**
     * @return Collection|Appointment[]
     */
    public function getAppointments(): Collection
    {
        return $this->appointments;
    }
}
