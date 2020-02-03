<?php declare(strict_types=1);

namespace App\Controller;

use App\Exception\ApiException;
use App\Service\Appointment as AppointmentService;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AppointmentController
 * @package App\Controller
 */
class AppointmentController extends AbstractController
{
    /**
     * @var AppointmentService
     */
    protected AppointmentService $appointmentService;

    public function __construct(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }

    /**
     * Book new appointment ( Client )
     *
     * @Route("/api/appointments", methods={"POST"})
     * @SWG\Response(
     *     response=201,
     *     description="Appointment created successfully",
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Bad request",
     * )
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     format="application/json",
     *     required=true,
     *     description="Json Payload",
     *     @SWG\Schema(
     *          type="object",
     *          @SWG\Property(property="client", type="string", example="Henry Cavil"),
     *          @SWG\Property(property="chair_id", type="number", example="1"),
     *          @SWG\Property(property="date", type="string", example="2020-02-23"),
     *          @SWG\Property(property="slot_ids", type="string", example="4,5")
     * )
     * )
     * @SWG\Tag(name="Appointments")
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function bookAppointment(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $this->appointmentService->bookAppointment($data);
        return $this->json([], 201);
    }

    /**
     * Appointments list ( Hairdresser )
     *
     * @Route("/api/appointments", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Appointments list",
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
     * @SWG\Parameter(
     *   name="chair_id",
     *   type="number",
     *   required=true,
     *   in="query",
     *   description="Chair ID"
     * )
     * @SWG\Tag(name="Appointments")
     * @param Request $request
     * @return JsonResponse
     */
    public function listAppointments(Request $request)
    {
        $dateString = $request->get('date');
        $chairId = $request->get('chair_id');
        $date = DateTime::createFromFormat('Y-m-d', $dateString);
        if (!$date) {
            throw new ApiException('Wrong date format (Y-m-d)');
        }
        $data = $this->appointmentService->listAppointments($date, (int) $chairId);
        return $this->json($data);
    }
}