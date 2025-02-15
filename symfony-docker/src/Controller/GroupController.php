<?php

namespace App\Controller;

use App\Entity\Group;
use App\Service\GroupService;
use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;
use JMS\Serializer\SerializerInterface;

final class GroupController extends AbstractController
{
    private readonly GroupService $groupService;
    private readonly SerializerInterface $serializer;
    public function __construct(GroupService $groupService, SerializerInterface $serializer){
        $this->groupService = $groupService;
        $this->serializer = $serializer;
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
        $groups = $this->groupService->getAllGroups();
        $json = $this->serializer->serialize(
            $groups,
            'json',
            SerializationContext::create()->setGroups(['list'])->setSerializeNull(true),
        );

        return new JsonResponse($json, Response::HTTP_OK, [], true);
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
        $group = $this->groupService->getGroupById($id);
        $json = $this->serializer->serialize(
            $group,
            'json',
            SerializationContext::create()->setGroups(['details'])->setSerializeNull(true),
        );
        return new JsonResponse($json, Response::HTTP_OK, [], true);
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
        $students = $this->groupService->getAllStudentsInGroup($id);
        if($students === []){
            return new JsonResponse(null,Response::HTTP_NOT_FOUND);
        }
        $json = $this->serializer->serialize(
            $students,
            'json',
            SerializationContext::create()->setGroups(['list'])->setSerializeNull(true),
        );
        return new JsonResponse($json, Response::HTTP_OK, [], true);
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
        $schedule = $this->groupService->getGroupSchedule($id);
        $json = $this->serializer->serialize(
            $schedule,
            'json',
            SerializationContext::create()->setGroups(['details'])->setSerializeNull(true),
        );
        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    #[Route('/api/group-schedule/{id}/{day}', name: 'app_group_schedule_day', methods: ['GET'])]
    #[OA\Get(
        path: "/api/group-schedule/{id}/{day}",
        summary: "Отримання розкладу групи на конкретний день",
        tags: ["Groups"]
    )]
    #[OA\Parameter(
        name: "id",
        description: "ID групи",
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
    public function getGroupScheduleForDay(int $id, string $day): JsonResponse{
        $schedule = $this->groupService->getGroupScheduleForDay($id, $day);
        $json = $this->serializer->serialize(
            $schedule,
            'json',
            SerializationContext::create()->setGroups(['details'])->setSerializeNull(true),
        );
        return new JsonResponse($json, Response::HTTP_OK, [], true);
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
        $newGroup = $this->groupService->createGroup($group);
        $json = $this->serializer->serialize(
            $newGroup,
            'json',
            SerializationContext::create()->setGroups(['details'])->setSerializeNull(true),
        );
        return new JsonResponse($json, Response::HTTP_CREATED, [], true);
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
        $updateGroup = $this->groupService->updateGroup($id, $data);
        $json = $this->serializer->serialize(
            $updateGroup,
            'json',
            SerializationContext::create()->setGroups(['details'])->setSerializeNull(true),
        );
        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    #[Route('/api/group/{id}', name: 'app_group_delete', methods: ['DELETE'])]
    #[OA\Delete(
        path: "/api/group/{id}/",
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
        $isDeleted = $this->groupService->deleteGroup($id);
        if(!$isDeleted){
            return new JsonResponse($isDeleted, Response::HTTP_BAD_REQUEST);
        }
        return new JsonResponse($isDeleted, Response::HTTP_OK);
    }
}
