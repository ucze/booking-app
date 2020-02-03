<?php declare(strict_types=1);

namespace App\Command\Appointment;

use App\Entity\Chair;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Book
 * @package App\Command\Appointment
 */
final class Book
{
    /**
     * @var string|null
     * @Assert\NotBlank()
     */
    public ?string $client = null;

    /**
     * @var DateTimeInterface|null
     * @Assert\NotBlank
     */
    public ?DateTimeInterface $date = null;

    /**
     * @var int|null
     * @Assert\NotBlank
     */
    public ?int $chair_id = null;

    /**
     * @var Chair|null
     */
    public ?Chair $chair = null;

    /**
     * @var string|null
     * @Assert\NotBlank
     */
    public ?string $slot_ids = null;

    /**
     * @var ArrayCollection
     */
    public ?ArrayCollection $slots = null;
}