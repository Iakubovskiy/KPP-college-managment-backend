<?php

namespace App\Controller;

use App\Entity\Group;
use App\Entity\Schedule;
use App\Entity\Subject;
use App\Service\ScheduleService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

final class ScheduleController extends AbstractController
{
    private ScheduleService  $scheduleService;
    private EntityManagerInterface $entityManager;
    public function __construct(ScheduleService $scheduleService, EntityManagerInterface $entityManager){
        $this->scheduleService = $scheduleService;
        $this->entityManager = $entityManager;
    }
    #[Route('/api/schedule', name: 'app_schedule', methods: ['POST'])]
    #[OA\Post(
        path: "/api/schedule",
        summary: "Створення нового запису в розкладі",
        tags: ["Schedule"]
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            type: "object",
            required: ["day", "time", "subject_id", "group_id"],
            properties: [
                new OA\Property(property: "day", type: "string", example: "Monday"),
                new OA\Property(property: "time", type: "string", example: "10:00"),
                new OA\Property(property: "subject_id", type: "integer", example: 1),
                new OA\Property(property: "group_id", type: "integer", example: 1)
            ]
        )
    )]
    #[OA\Response(
        response: 201,
        description: "Запис у розкладі успішно створено",
        content: new OA\JsonContent(
            type: "object",
            properties: [
                new OA\Property(property: "id", type: "integer", example: 1),
                new OA\Property(property: "day", type: "string", example: "Monday"),
                new OA\Property(property: "time", type: "string", example: "10:00"),
                new OA\Property(
                    property: "subject",
                    type: "object",
                    properties: [
                        new OA\Property(property: "id", type: "integer", example: 1),
                        new OA\Property(property: "name", type: "string", example: "Математика")
                    ]
                ),
                new OA\Property(
                    property: "group",
                    type: "object",
                    properties: [
                        new OA\Property(property: "id", type: "integer", example: 1),
                        new OA\Property(property: "name", type: "string", example: "КН-41")
                    ]
                )
            ]
        )
    )]
    #[OA\Response(
        response: 400,
        description: "Помилка валідації даних"
    )]
    public function createScheduleRecord(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $record = new Schedule();
        $record->setDay($data['day']);
        $record->setTime($data['time']);
        $record->setSubject($this->entityManager->getRepository(Subject::class)->find($data['subject_id']));
        $record->setGroup($this->entityManager->getRepository(Group::class)->find($data['group_id']));

        return new JsonResponse($this->scheduleService->createScheduleRecord($record), Response::HTTP_CREATED);
    }

    #[Route('/api/schedule/{id}', name: 'app_delete_schedule', methods: ['DELETE'])]
    #[OA\Delete(
        path: "/api/schedule/{id}",
        summary: "Видалення запису з розкладу",
        tags: ["Schedule"]
    )]
    #[OA\Parameter(
        name: "id",
        description: "ID запису розкладу",
        in: "path",
        required: true,
        schema: new OA\Schema(type: "integer")
    )]
    #[OA\Response(
        response: 200,
        description: "Запис успішно видалено"
    )]
    #[OA\Response(
        response: 404,
        description: "Запис не знайдено"
    )]
    public function deleteScheduleRecord(int $id): JsonResponse
    {
        return new JsonResponse($this->scheduleService->deleteSchedule($id), Response::HTTP_OK);
    }

    #[Route('/api/schedule/{id}', name: 'app_update_schedule', methods: ['PUT'])]
    #[OA\Put(
        path: "/api/schedule/{id}",
        summary: "Оновлення запису в розкладі",
        tags: ["Schedule"]
    )]
    #[OA\Parameter(
        name: "id",
        description: "ID запису розкладу",
        in: "path",
        required: true,
        schema: new OA\Schema(type: "integer")
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            type: "object",
            required: ["day", "time", "subject_id", "group_id"],
            properties: [
                new OA\Property(property: "day", type: "string", example: "Monday"),
                new OA\Property(property: "time", type: "string", example: "10:00"),
                new OA\Property(property: "subject_id", type: "integer", example: 1),
                new OA\Property(property: "group_id", type: "integer", example: 1)
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: "Запис у розкладі успішно оновлено",
        content: new OA\JsonContent(
            type: "object",
            properties: [
                new OA\Property(property: "id", type: "integer", example: 1),
                new OA\Property(property: "day", type: "string", example: "Monday"),
                new OA\Property(property: "time", type: "string", example: "10:00"),
                new OA\Property(
                    property: "subject",
                    type: "object",
                    properties: [
                        new OA\Property(property: "id", type: "integer", example: 1),
                        new OA\Property(property: "name", type: "string", example: "Математика")
                    ]
                ),
                new OA\Property(
                    property: "group",
                    type: "object",
                    properties: [
                        new OA\Property(property: "id", type: "integer", example: 1),
                        new OA\Property(property: "name", type: "string", example: "КН-41")
                    ]
                )
            ]
        )
    )]
    #[OA\Response(
        response: 400,
        description: "Помилка валідації даних"
    )]
    #[OA\Response(
        response: 404,
        description: "Запис не знайдено"
    )]
    public function updateScheduleRecord(Request $request, int $id): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $record = new Schedule();
        $record->setDay($data['day']);
        $record->setTime($data['time']);
        $record->setSubject($this->entityManager->getRepository(Subject::class)->find($data['subject_id']));
        $record->setGroup($this->entityManager->getRepository(Group::class)->find($data['group_id']));
        return new JsonResponse($this->scheduleService->updateSchedule($id, $record), Response::HTTP_OK);
    }
}
