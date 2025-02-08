<?php

namespace App\Controller;

use App\Service\StudentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;

final class StudentController extends AbstractController
{
    private StudentService $studentService;
    private SerializerInterface $serializer;

    public function __construct(StudentService $studentService, SerializerInterface $serializer){
        $this->studentService = $studentService;
        $this->serializer = $serializer;
    }

    #[Route('/api/student/{id}/{subject_id}', name: 'app_student_grades', methods:["GET"])]
    #[OA\Get(
        path: "/api/student/{id}/{subject_id}",
        summary: "Отримання оцінок студента з конкретного предмету",
        tags: ["Students"]
    )]
    #[OA\Parameter(
        name: "id",
        description: "ID студента",
        in: "path",
        required: true,
        schema: new OA\Schema(type: "integer")
    )]
    #[OA\Parameter(
        name: "subject_id",
        description: "ID предмету",
        in: "path",
        required: true,
        schema: new OA\Schema(type: "integer")
    )]
    #[OA\Response(
        response: 200,
        description: "Список оцінок студента успішно отримано",
        content: new OA\JsonContent(
            type: "array",
            items: new OA\Items(
                properties: [
                    new OA\Property(property: "id", type: "integer", example: 1),
                    new OA\Property(property: "grade", type: "integer", example: 90),
                    new OA\Property(property: "date_and_time", type: "string", format: "date-time", example: "2024-02-05T10:00:00Z"),
                    new OA\Property(
                        property: "teacher",
                        type: "object",
                        properties: [
                            new OA\Property(property: "id", type: "integer", example: 1),
                            new OA\Property(property: "name", type: "string", example: "Іван Петрович")
                        ]
                    )
                ]
            )
        )
    )]
    #[OA\Response(
        response: 404,
        description: "Студента або предмет не знайдено"
    )]
    public function getStudentGradesInSubject(int $id, int $subject_id): JsonResponse
    {
        $studentWithGrades = $this->studentService->getStudentGradesInSubject($id, $subject_id);
        $json = $this->serializer->serialize(
            $studentWithGrades,
            "json",
            SerializationContext::create()->setSerializeNull(true)->setGroups(["details"])
        );
        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }
}
