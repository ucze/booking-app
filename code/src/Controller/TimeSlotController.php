<?php declare(strict_types=1);

namespace App\Controller;

use App\Exception\ApiException;
use App\Service\TimeSlot as TimeSlotService;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TimeSlotController
 * @package App\Controller
 */
class TimeSlotController extends AbstractController
{
    /**
     * @var TimeSlotService
     */
    protected TimeSlotService $timeSlotService;

    public function __construct(TimeSlotService $timeSlotService)
    {
        $this->timeSlotService = $timeSlotService;
    }

    /**
     * List available time slots ( Client )
     *
     * @Route("/api/time_slots", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="List of available timeslots",
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Bad Request",
     * )
     * @SWG\Parameter(
     *   name="date",
     *   type="string",
     *   required=true,
     *   in="query",
     *   description="Date [Y-m-d]"
     * )
     * @SWG\Tag(name="Time Slots")
     * @param Request $request
     * @return JsonResponse
     */
    public function listAvailableSlots(Request $request)
    {
        $dateString = $request->get('date');
        $date = DateTime::createFromFormat('Y-m-d', $dateString);
        if (!$date) {
            throw new ApiException('Wrong date format (Y-m-d)');
        }
        $data = $this->timeSlotService->listAvailableSlots($date);
        return $this->json($data);
    }
}