<?php

namespace App\Controller;

use App\Entity\Group;
use App\Entity\Student;
use App\Service\StudentService;
use Doctrine\ORM\EntityManagerInterface;
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
    private EntityManagerInterface $entityManager;
    private SerializerInterface $serializer;

    public function __construct(StudentService $studentService, SerializerInterface $serializer, EntityManagerInterface $entityManager){
        $this->studentService = $studentService;
        $this->serializer = $serializer;
        $this->entityManager = $entityManager;
    }

    #[Route('/api/students', name: 'app_students_list', methods: ["GET"])]
    #[OA\Get(
        path: "/api/students",
        summary: "Отримання списку всіх студентів",
        tags: ["Students"]
    )]
    #[OA\Response(
        response: 200,
        description: "Список студентів успішно отримано",
        content: new OA\JsonContent(
            type: "array",
            items: new OA\Items(
                properties: [
                    new OA\Property(property: "id", type: "integer", example: 1),
                    new OA\Property(property: "name", type: "string", example: "Петро Іванов"),
                    new OA\Property(
                        property: "group",
                        type: "object",
                        nullable: true,
                        properties: [
                            new OA\Property(property: "id", type: "integer", example: 2),
                            new OA\Property(property: "name", type: "string", example: "Група A-2024")
                        ]
                    )
                ]
            )
        )
    )]
    #[OA\Response(
        response: 204,
        description: "Список студентів порожній"
    )]
    public function getAllStudents(): JsonResponse
    {
        $json = $this->serializer->serialize(
            $this->entityManager->getRepository(Student::class)->findAll(),
            "json",
            SerializationContext::create()->setSerializeNull(true)->setGroups(["list"])
        );
        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    #[Route('/api/student/{id}', name: 'app_student_details', methods: ["GET"])]
    #[OA\Get(
        path: "/api/student/{id}",
        summary: "Отримання інформації про студента",
        tags: ["Students"]
    )]
    #[OA\Parameter(
        name: "id",
        description: "ID студента",
        in: "path",
        required: true,
        schema: new OA\Schema(type: "integer")
    )]
    #[OA\Response(
        response: 200,
        description: "Дані про студента успішно отримано",
        content: new OA\JsonContent(
            type: "object",
            properties: [
                new OA\Property(property: "id", type: "integer", example: 1),
                new OA\Property(property: "name", type: "string", example: "Петро Іванов"),
                new OA\Property(
                    property: "group",
                    type: "object",
                    nullable: true,
                    properties: [
                        new OA\Property(property: "id", type: "integer", example: 2),
                        new OA\Property(property: "name", type: "string", example: "Група A-2024")
                    ]
                )
            ]
        )
    )]
    #[OA\Response(
        response: 404,
        description: "Студента не знайдено"
    )]
    public function getStudent(int $id): JsonResponse
    {
        $student = $this->entityManager->getRepository(Student::class)->find($id);
        $json = $this->serializer->serialize(
            $student,
            "json",
            SerializationContext::create()->setSerializeNull(true)->setGroups(["details"])
        );
        return new JsonResponse($json, Response::HTTP_OK, [], true);
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
            SerializationContext::create()->setSerializeNull(true)->setGroups(["list"])
        );
        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    #[Route('/api/student/{student_id}/{group_id}', name: 'app_student_group', methods: ["PATCH", "PUT"])]
    #[OA\Put(
        path: "/api/student/{student_id}/{group_id}",
        summary: "Оновлення групи студента",
        tags: ["Students"]
    )]
    #[OA\Patch(
        path: "/api/student/{student_id}/{group_id}",
        summary: "Оновлення групи студента",
        tags: ["Students"]
    )]
    #[OA\Parameter(
        name: "student_id",
        description: "ID студента",
        in: "path",
        required: true,
        schema: new OA\Schema(type: "integer")
    )]
    #[OA\Parameter(
        name: "group_id",
        description: "ID нової групи студента",
        in: "path",
        required: true,
        schema: new OA\Schema(type: "integer")
    )]
    #[OA\Response(
        response: 200,
        description: "Групу студента успішно оновлено",
        content: new OA\JsonContent(
            type: "object",
            properties: [
                new OA\Property(property: "id", type: "integer", example: 1),
                new OA\Property(property: "name", type: "string", example: "Петро Іванов"),
                new OA\Property(
                    property: "group",
                    type: "object",
                    properties: [
                        new OA\Property(property: "id", type: "integer", example: 2),
                        new OA\Property(property: "name", type: "string", example: "Група A-2024")
                    ]
                )
            ]
        )
    )]
    #[OA\Response(
        response: 404,
        description: "Студента або групу не знайдено"
    )]
    public function setStudentGroup(int $student_id, int $group_id): JsonResponse
    {
        $group = $this->entityManager->getRepository(Group::class)->find($group_id);
        $student = $this->entityManager->getRepository(Student::class)->find($student_id);
        $student->setGroup($group);
        $this->entityManager->persist($student);
        $this->entityManager->flush();
        $json = $this->serializer->serialize(
            $student,
            "json",
            SerializationContext::create()->setSerializeNull(true)->setGroups(["details"])
        );
        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

}
