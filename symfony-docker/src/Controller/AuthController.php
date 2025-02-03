<?php

namespace App\Controller;

use App\Service\AuthService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

final class AuthController extends AbstractController
{
    private AuthService $authService;
    public function __construct(AuthService $authService){
        $this->authService = $authService;
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
    public function  register(Request $request, LoggerInterface $logger): JsonResponse
    {
        $data = (json_decode($request->getContent(), true));
        $role = $data["role"];
        $email = $data["email"];
        $password = $data["password"];
        $dateOfBirth = $data["dateOfBirth"] ?? null;
        $group_id = $data["group_id"] ?? null;
        $name = $data["name"];
        $surname = $data["surname"];
        $extraData = [];
        if($dateOfBirth)
            $extraData["dateOfBirth"] = $dateOfBirth;
        if($group_id)
            $extraData["group_id"] = $group_id;

        return new JsonResponse(["new user" =>
            $this->authService->registe($email, $password, $role, $name, $surname, $extraData)
        ]);
    }
}
