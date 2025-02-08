<?php

namespace App\Controller;

use App\Service\TeacherService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;

final class TeacherController extends AbstractController
{
    private TeacherService  $teacherService;
    private SerializerInterface $serializer;

    public function __construct(TeacherService $teacherService, SerializerInterface $serializer){
        $this->teacherService = $teacherService;
        $this->serializer = $serializer;
    }
    #[Route('/api/teacher/{id}', name: 'app_teacher_schedule', methods: ['GET'])]
    #[OA\Get(
        path: "/api/teacher/{id}",
        summary: "Отримання розкладу викладача",
        tags: ["Teachers"]
    )]
    #[OA\Parameter(
        name: "id",
        description: "ID викладача",
        in: "path",
        required: true,
        schema: new OA\Schema(type: "integer")
    )]
    #[OA\Response(
        response: 200,
        description: "Розклад викладача успішно отримано",
        content: new OA\JsonContent(
            type: "array",
            items: new OA\Items(
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
        )
    )]
    #[OA\Response(
        response: 404,
        description: "Викладача не знайдено"
    )]
    public function getTeacherSchedule(int $id): JsonResponse
    {
        $json = $this->serializer->serialize(
            $this->teacherService->getTeacherSchedule($id),
            'json',
            SerializationContext::create()->setSerializeNull(true)->setGroups(["details"])
        );
       return new JsonResponse($json, Response::HTTP_OK, [], true);
    }
    #[Route('/api/teacher/{id}/{day}', name: 'app_teacher_schedule_day)', methods: ['GET'])]
    #[OA\Get(
        path: "/api/teacher/{id}/{day}",
        summary: "Отримання розкладу викладача на конкретний день",
        tags: ["Teachers"]
    )]
    #[OA\Parameter(
        name: "id",
        description: "ID викладача",
        in: "path",
        required: true,
        schema: new OA\Schema(type: "integer")
    )]
    #[OA\Parameter(
        name: "day",
        description: "День тижня (Monday, Tuesday, etc.)",
        in: "path",
        required: true,
        schema: new OA\Schema(type: "string")
    )]
    #[OA\Response(
        response: 200,
        description: "Розклад викладача на день успішно отримано",
        content: new OA\JsonContent(
            type: "array",
            items: new OA\Items(
                properties: [
                    new OA\Property(property: "id", type: "integer", example: 1),
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
        )
    )]
    #[OA\Response(
        response: 404,
        description: "Викладача не знайдено або неправильно вказаний день"
    )]
    public function getTeacherScheduleForDay(int $id, string $day): JsonResponse
    {
        $json = $this->serializer->serialize(
            $this->teacherService->getTeacherScheduleForDay($id, $day),
            'json',
            SerializationContext::create()->setSerializeNull(true)->setGroups(["details"])
        );
        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }
}
