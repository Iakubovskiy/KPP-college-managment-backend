<?php

namespace App\Controller;

use App\Entity\Subject;
use App\Entity\Teacher;
use App\Service\SubjectService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;

final class SubjectController extends AbstractController
{
    private readonly SubjectService $subjectService;
    private readonly SerializerInterface $serializer;
    private readonly EntityManagerInterface $entityManager;
    public function __construct(SubjectService $subjectService, SerializerInterface $serializer, EntityManagerInterface $entityManager){
        $this->subjectService = $subjectService;
        $this->serializer = $serializer;
        $this->entityManager = $entityManager;
    }
    #[Route('/api/subject', name: 'app_subjects', methods: ['GET'])]
    #[OA\Get(
        path: "/api/subject",
        summary: "Отримання списку всіх предметів",
        tags: ["Subjects"]
    )]
    #[OA\Response(
        response: 200,
        description: "Список предметів успішно отримано",
        content: new OA\JsonContent(
            type: "array",
            items: new OA\Items(
                properties: [
                    new OA\Property(property: "id", type: "integer", example: 1),
                    new OA\Property(property: "name", type: "string", example: "Математика")
                ]
            )
        )
    )]
    public function getAll(): JsonResponse
    {
        $json = $this->serializer->serialize(
            $this->subjectService->getAllSubjects(),
            'json',
            SerializationContext::create()->setGroups(['list'])->setSerializeNull(true),
        );
        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    #[Route('/api/subject/{id}', name: 'app_subject', methods: ['GET'])]
    #[OA\Get(
        path: "/api/subject/{id}",
        summary: "Отримання інформації про конкретний предмет",
        tags: ["Subjects"]
    )]
    #[OA\Parameter(
        name: "id",
        description: "ID предмету",
        in: "path",
        required: true,
        schema: new OA\Schema(type: "integer")
    )]
    #[OA\Response(
        response: 200,
        description: "Інформація про предмет успішно отримана",
        content: new OA\JsonContent(
            type: "object",
            properties: [
                new OA\Property(property: "id", type: "integer", example: 1),
                new OA\Property(property: "name", type: "string", example: "Математика")
            ]
        )
    )]
    #[OA\Response(
        response: 404,
        description: "Предмет не знайдено"
    )]
    public function getSubject(int $id): JsonResponse{
        $json = $this->serializer->serialize(
            $this->subjectService->getSubjectsById($id),
            'json',
            SerializationContext::create()->setSerializeNull(true)->setGroups(["details"])
        );
        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    #[Route('/api/subject', name: 'app_create_subject', methods: ['POST'])]
    #[OA\Post(
        path: "/api/subject",
        summary: "Створення нового предмету",
        tags: ["Subjects"]
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            type: "object",
            required: ["name"],
            properties: [
                new OA\Property(property: "name", type: "string", example: "Фізика")
            ]
        )
    )]
    #[OA\Response(
        response: 201,
        description: "Предмет успішно створено",
        content: new OA\JsonContent(
            type: "object",
            properties: [
                new OA\Property(property: "id", type: "integer", example: 1),
                new OA\Property(property: "name", type: "string", example: "Фізика")
            ]
        )
    )]
    #[OA\Response(
        response: 400,
        description: "Помилка валідації даних"
    )]
    public function createSubject(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $requiredKeys = ['name', 'hours_per_week', 'teacher_id'];
        $missingKeys = [];

        foreach ($requiredKeys as $key) {
            if (!array_key_exists($key, $data)) {
                $missingKeys[] = $key;
            }
        }

        if (!empty($missingKeys)) {
            return new JsonResponse(
                ["message" => "Не вистачає наступних обов'язкових полів: " . implode(", ", $missingKeys)],
                Response::HTTP_BAD_REQUEST
            );
        }

        $subject = new Subject();
        $subject->setName($data['name']);
        $subject->setHoursPerWeek($data['hours_per_week']);
        $teacher = $this->entityManager->getRepository(Teacher::class)->find($data['teacher_id']);
        $subject->setTeachers($teacher);
        $json = $this->serializer->serialize(
            $this->subjectService->createSubject($subject),
            'json',
            SerializationContext::create()->setSerializeNull(true)->setGroups(["details"])
        );
        return new JsonResponse($json, Response::HTTP_CREATED, [], true);
    }

    #[Route('/api/subject/{id}', name: 'app_update_subject', methods: ['PUT'])]
    #[OA\Put(
        path: "/api/subject/{id}",
        summary: "Оновлення інформації про предмет",
        tags: ["Subjects"]
    )]
    #[OA\Parameter(
        name: "id",
        description: "ID предмету",
        in: "path",
        required: true,
        schema: new OA\Schema(type: "integer")
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            type: "object",
            required: ["name"],
            properties: [
                new OA\Property(property: "name", type: "string", example: "Інформатика")
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: "Інформація про предмет успішно оновлена",
        content: new OA\JsonContent(
            type: "object",
            properties: [
                new OA\Property(property: "id", type: "integer", example: 1),
                new OA\Property(property: "name", type: "string", example: "Інформатика")
            ]
        )
    )]
    #[OA\Response(
        response: 404,
        description: "Предмет не знайдено"
    )]
    public function updateSubject(Request $request, int $id): JsonResponse{
        $data = json_decode($request->getContent(), true);
        $newSubject = $this->subjectService->getSubjectsById($id);
        if(array_key_exists("name", $data)){
            $newSubject->setName($data['name']);
        }
        if(array_key_exists("hours_per_week", $data)){
            $newSubject->setHoursPerWeek($data['hours_per_week']);
        }
        if(array_key_exists("teacher_id", $data)){
            $teacher = $this->entityManager->getRepository(Teacher::class)->find($data['teacher_id']);
            $newSubject->setTeachers($teacher);
        }

        $json = $this->serializer->serialize(
            $this->subjectService->updateSubject($id, $newSubject),
            'json',
            SerializationContext::create()->setSerializeNull(true)->setGroups(["details"]),
        );
        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    #[Route('/api/subject/{id}', name: 'app_subject_delete', methods: ['DELETE'])]
    #[OA\Delete(
        path: "/api/subject/{id}",
        summary: "Видалення предмету",
        tags: ["Subjects"]
    )]
    #[OA\Parameter(
        name: "id",
        description: "ID предмету",
        in: "path",
        required: true,
        schema: new OA\Schema(type: "integer")
    )]
    #[OA\Response(
        response: 200,
        description: "Предмет успішно видалено"
    )]
    #[OA\Response(
        response: 404,
        description: "Предмет не знайдено"
    )]
    public function deleteSubject(int $id): JsonResponse{
        return new JsonResponse($this->subjectService->deleteSubject($id), Response::HTTP_OK);
    }
}
