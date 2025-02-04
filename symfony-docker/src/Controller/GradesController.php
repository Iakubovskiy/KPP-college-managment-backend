<?php

namespace App\Controller;

use App\Entity\Grade;
use App\Entity\Student;
use App\Entity\Subject;
use App\Entity\Teacher;
use App\Service\GradeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

final class GradesController extends AbstractController
{
    private GradeService $gradeService;
    private EntityManagerInterface $em;
    public function __construct(GradeService $gradeService, EntityManagerInterface $em){
        $this->gradeService = $gradeService;
        $this->em = $em;
    }


    #[Route("/api/grades", name: "api_create_grade", methods: ["POST"])]
    #[OA\Post(
        path: "/api/grades",
        summary: "Додавання оцінки студенту",
        tags: ["Grades"]
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            type: "object",
            required: ["grade", "student_id", "subject_id", "teacher_id"],
            properties: [
                new OA\Property(property: "grade", type: "integer", example: 10),
                new OA\Property(property: "date_and_time", type: "string", format: "date-time", example: "2024-02-04T10:00:00Z", nullable: true),
                new OA\Property(property: "student_id", type: "integer", example: 1),
                new OA\Property(property: "subject_id", type: "integer", example: 2),
                new OA\Property(property: "teacher_id", type: "integer", example: 3)
            ]
        )
    )]
    #[OA\Response(
        response: 201,
        description: "Оцінка успішно додана",
        content: new OA\JsonContent(
            type: "object",
            properties: [
                new OA\Property(property: "id", type: "integer", example: 1),
                new OA\Property(property: "grade", type: "integer", example: 10),
                new OA\Property(property: "date_and_time", type: "string", format: "date-time", example: "2024-02-04T10:00:00Z"),
                new OA\Property(property: "student_id", type: "integer", example: 1),
                new OA\Property(property: "subject_id", type: "integer", example: 2),
                new OA\Property(property: "teacher_id", type: "integer", example: 3)
            ]
        )
    )]
    #[OA\Response(
        response: 400,
        description: "Помилка валідації"
    )]
    public function createGrade(Request $request): JsonResponse{
        $data = json_decode($request->getContent(), true);
        $grade = new Grade();
        $grade->setGrade($data['grade']);
        $grade->setDateAndTime(new \DateTime($data['date_and_time']));
        $grade->setStudent($this->em->getRepository(Student::class)->find($data['student_id']));
        $grade->setSubject($this->em->getRepository(Subject::class)->find($data['subject_id']));
        $grade->setTeacher($this->em->getRepository(Teacher::class)->find($data['teacher_id']));

        $this->gradeService->createGrade($grade);
        return new JsonResponse($grade, Response::HTTP_CREATED);
    }

    #[Route("/api/grades/{id}", name: "api_update_grade", methods: ["PUT"])]
    #[OA\Put(
        path: "/api/grades/{id}",
        summary: "Оновлення оцінки студента",
        tags: ["Grades"]
    )]
    #[OA\Parameter(
        name: "id",
        description: "ID оцінки",
        in: "path",
        required: true,
        schema: new OA\Schema(type: "integer")
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            type: "object",
            required: ["grade"],
            properties: [
                new OA\Property(property: "grade", type: "integer", example: 10)
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: "Оцінка успішно оновлена",
        content: new OA\JsonContent(
            type: "object",
            properties: [
                new OA\Property(property: "id", type: "integer", example: 1),
                new OA\Property(property: "grade", type: "integer", example: 10),
                new OA\Property(property: "date_and_time", type: "string", format: "date-time", example: "2024-02-04T10:00:00Z"),
                new OA\Property(property: "student_id", type: "integer", example: 1),
                new OA\Property(property: "subject_id", type: "integer", example: 2),
                new OA\Property(property: "teacher_id", type: "integer", example: 3)
            ]
        )
    )]
    #[OA\Response(
        response: 400,
        description: "Помилка валідації"
    )]
    public function updateGrade(Request $request, int $id): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $grade = $data['grade'];
        $new_grade = $this->gradeService->updateGrade($id, $grade);
        return new JsonResponse($new_grade, Response::HTTP_OK);
    }

    #[Route("/api/grades/{id}", name: "api_delete_grade", methods: ["DELETE"])]
    #[OA\Delete(
        path: "/api/grades/{id}",
        summary: "Видалення оцінки",
        tags: ["Grades"]
    )]
    #[OA\Parameter(
        name: "id",
        description: "ID оцінки",
        in: "path",
        required: true,
        schema: new OA\Schema(type: "integer")
    )]
    #[OA\Response(
        response: 204,
        description: "Оцінка успішно видалена"
    )]
    #[OA\Response(
        response: 400,
        description: "Помилка при видаленні оцінки"
    )]
    public function deleteGrade(Request $request, int $id): JsonResponse
    {
        $is_deleted = $this->gradeService->deleteGrade($id);
        if($is_deleted)
            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        else
            return new JsonResponse(null, Response::HTTP_BAD_REQUEST);
    }
}
