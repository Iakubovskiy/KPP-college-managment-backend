<?php

namespace App\Controller;

use App\Service\AuthService;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

final class AuthController extends AbstractController
{
    private AuthService $authService;
    private readonly SerializerInterface $serializer;
    public function __construct(AuthService $authService, SerializerInterface $serializer){
        $this->authService = $authService;
        $this->serializer = $serializer;
    }
    #[Route("/api/login_check", name: "api_login", methods: ["POST"])]
    #[OA\Post(
        path: "/api/login_check",
        summary: "Login for user",
        tags: ["Auth"],
        description: "JWT Authentication",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["username", "password"],
                properties: [
                    new OA\Property(property: "username", type: "string", example: "user@example.com"),
                    new OA\Property(property: "password", type: "string", example: "password")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Success",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "token", type: "string", example: "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...")
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Wrong login or password"
            )
        ],
        security: []
    )]
    public function fakeLogin(): JsonResponse{
        return new JsonResponse(['message'=>'just for swagger']);
    }

    #[Route("/api/register", name: "api_register", methods: ["POST"])]
    #[OA\Post(
        path: "/api/register",
        summary: "Реєстрація нового користувача",
        tags: ["Auth"]
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            type: "object",
            required: ["role", "email", "password", "name", "surname"],
            properties: [
                new OA\Property(property: "role", type: "string", example: "ROLE_STUDENT"),
                new OA\Property(property: "email", type: "string", format: "email", example: "user@example.com"),
                new OA\Property(property: "password", type: "string", format: "password", example: "securepassword"),
                new OA\Property(property: "name", type: "string", example: "Іван"),
                new OA\Property(property: "surname", type: "string", example: "Петренко"),
                new OA\Property(property: "dateOfBirth", type: "string", format: "date", example: "2000-01-01", nullable: true),
                new OA\Property(property: "group_id", type: "integer", example: 1, nullable: true)
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: "Успішна реєстрація",
        content: new OA\JsonContent(
            type: "object",
            properties: [
                new OA\Property(property: "new user", type: "object")
            ]
        )
    )]
    #[OA\Response(
        response: 400,
        description: "Помилка валідації"
    )]
    public function  register(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if ($data === null) {
            return new JsonResponse(["message" => "Некоректний JSON", "data"=>$data], Response::HTTP_BAD_REQUEST);
        }
        $role = $data["role"];
        $email = $data["email"];
        $password = $data["password"];
        $name = $data["name"];
        $surname = $data["surname"];
        $extraData = [];
        if(array_key_exists("dateOfBirth", $data)){
            $dateOfBirth = $data["dateOfBirth"] ?? null;
            $extraData["dateOfBirth"] = $dateOfBirth;
        }

        if(array_key_exists("group_id", $data)) {
            $group_id = $data["group_id"] ?? null;
            $extraData["group_id"] = $group_id;
        }
        $newUser = $this->authService->registe($email, $password, $role, $name, $surname, $extraData);
        $json = $this->serializer->serialize($newUser, 'json', SerializationContext::create()->setGroups(['list']));

        return new JsonResponse($json, Response::HTTP_CREATED, [], true);
    }
}
