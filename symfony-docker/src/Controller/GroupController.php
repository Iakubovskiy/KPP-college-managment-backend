<?php

namespace App\Controller;

use App\Entity\Group;
use App\Service\GroupService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

final class GroupController extends AbstractController
{
    private readonly GroupService $groupService;
    public function __construct(GroupService $groupService){
        $this->groupService = $groupService;
    }
    #[Route('/api/group/', name: 'app_groups', methods: ['GET'])]
    #[OA\Get(
        path: "/api/group/",
        summary: "Отримання списку всіх груп",
        tags: ["Groups"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Список груп успішно отримано",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: "id", type: "integer", example: 1),
                            new OA\Property(property: "name", type: "string", example: "КН-41")
                        ]
                    )
                )
            )
        ]
    )]
    public function getAll(): JsonResponse
    {
        return new JsonResponse($this->groupService->getAllGroups(), Response::HTTP_OK);
    }

    #[Route('/api/group/{id}', name: 'app_group', methods: ['GET'])]
    #[OA\Get(
        path: "/api/group/{id}",
        summary: "Отримання інформації про конкретну групу",
        tags: ["Groups"]
    )]
    #[OA\Parameter(
        name: "id",
        description: "ID групи",
        in: "path",
        required: true,
        schema: new OA\Schema(type: "integer")
    )]
    #[OA\Response(
        response: 200,
        description: "Інформація про групу успішно отримана",
        content: new OA\JsonContent(
            type: "object",
            properties: [
                new OA\Property(property: "id", type: "integer", example: 1),
                new OA\Property(property: "name", type: "string", example: "КН-41")
            ]
        )
    )]
    public function getGroup(int $id): JsonResponse{
        return new JsonResponse($this->groupService->getGroupById($id), Response::HTTP_OK);
    }

    #[Route('/api/group-students/{id}', name: 'app_group_students', methods: ['GET'])]
    #[OA\Get(
        path: "/api/group-students/{id}",
        summary: "Отримання списку студентів групи",
        tags: ["Groups"]
    )]
    #[OA\Parameter(
        name: "id",
        description: "ID групи",
        in: "path",
        required: true,
        schema: new OA\Schema(type: "integer")
    )]
    #[OA\Response(
        response: 200,
        description: "Список студентів групи успішно отримано",
        content: new OA\JsonContent(
            type: "array",
            items: new OA\Items(
                properties: [
                    new OA\Property(property: "id", type: "integer", example: 1),
                    new OA\Property(property: "name", type: "string", example: "Іван Петров"),
                    new OA\Property(property: "email", type: "string", example: "ivan@example.com")
                ]
            )
        )
    )]
    public function getGroupStudents(int $id): JsonResponse{
        return new JsonResponse($this->groupService->getAllStudentsInGroup($id), Response::HTTP_OK);
    }

    #[Route('/api/group-schedule/{id}', name: 'app_group_schedule', methods: ['GET'])]
    #[OA\Get(
        path: "/api/group-schedule/{id}",
        summary: "Отримання розкладу групи",
        tags: ["Groups"]
    )]
    #[OA\Parameter(
        name: "id",
        description: "ID групи",
        in: "path",
        required: true,
        schema: new OA\Schema(type: "integer")
    )]
    #[OA\Response(
        response: 200,
        description: "Розклад групи успішно отримано",
        content: new OA\JsonContent(
            type: "array",
            items: new OA\Items(
                properties: [
                    new OA\Property(property: "id", type: "integer", example: 1),
                    new OA\Property(property: "subject", type: "string", example: "Математика"),
                    new OA\Property(property: "time", type: "string", format: "date-time", example: "2024-02-05T10:00:00Z")
                ]
            )
        )
    )]
    public function getGroupSchedule(int $id): JsonResponse{
        return new JsonResponse($this->groupService->getGroupSchedule($id), Response::HTTP_OK);
    }

    #[Route('/api/group', name: 'app_create_group', methods: ['POST'])]
    #[OA\Post(
        path: "/api/group",
        summary: "Створення нової групи",
        tags: ["Groups"]
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            type: "object",
            required: ["name"],
            properties: [
                new OA\Property(property: "name", type: "string", example: "КН-41")
            ]
        )
    )]
    #[OA\Response(
        response: 201,
        description: "Група успішно створена",
        content: new OA\JsonContent(
            type: "object",
            properties: [
                new OA\Property(property: "id", type: "integer", example: 1),
                new OA\Property(property: "name", type: "string", example: "КН-41")
            ]
        )
    )]
    public function createGroup(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $group = new Group();
        $group->setName($data['name']);
        return new JsonResponse($this->groupService->createGroup($group), Response::HTTP_CREATED);
    }

    #[Route('/api/group/{id}', name: 'app_update_group', methods: ['PUT'])]
    #[OA\Put(
        path: "/api/group/{id}",
        summary: "Оновлення інформації про групу",
        tags: ["Groups"]
    )]
    #[OA\Parameter(
        name: "id",
        description: "ID групи",
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
                new OA\Property(property: "name", type: "string", example: "КН-42")
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: "Інформація про групу успішно оновлена",
        content: new OA\JsonContent(
            type: "object",
            properties: [
                new OA\Property(property: "id", type: "integer", example: 1),
                new OA\Property(property: "name", type: "string", example: "КН-42")
            ]
        )
    )]

    public function updateGroup(Request $request, int $id): JsonResponse{
        $data = json_decode($request->getContent(), true);
        return new JsonResponse($this->groupService->updateGroup($id, $data), Response::HTTP_OK);
    }

    #[Route('/api/group/{id}/delete', name: 'app_group_delete', methods: ['DELETE'])]
    #[OA\Delete(
        path: "/api/group/{id}/delete",
        summary: "Видалення групи",
        tags: ["Groups"]
    )]
    #[OA\Parameter(
        name: "id",
        description: "ID групи",
        in: "path",
        required: true,
        schema: new OA\Schema(type: "integer")
    )]
    #[OA\Response(
        response: 200,
        description: "Група успішно видалена"
    )]
    public function deleteGroup(int $id): JsonResponse{
        return new JsonResponse($this->groupService->deleteGroup($id), Response::HTTP_OK);
    }
}
