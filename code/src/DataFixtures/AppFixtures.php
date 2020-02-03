<?php declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Chair;
use App\Entity\TimeSlot;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Class AppFixtures
 * @package App\DataFixtures
 */
class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Chairs
        $firstChair = new Chair('Anna A');
        $secondChair = new Chair('Barbara B');
        $manager->persist($firstChair);
        $manager->persist($secondChair);

        // Time Slots
        $time = new DateTime();
        $time->setTime(8, 0, 0, 0);

        for($i = 1; $i < 25; $i++){
            $startTime = clone $time;
            $endTime = clone $startTime;
            $timeSlot = new TimeSlot($startTime, $endTime->modify('+30 min'));
            $manager->persist($timeSlot);
            $time->modify('+30 min');
        }
        $manager->flush();
    }
}
