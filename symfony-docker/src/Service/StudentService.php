<?php
namespace App\Service;

use App\Entity\Student;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;

class StudentService{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em){
        $this->em = $em;
    }

    public function getStudentGradesInSubject(int $studentId, int $subjectId) : array{
        $student = $this->em->getRepository(Student::class)->find($studentId);

        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq("subject",$subjectId));

        return $student->getGrades()->matching($criteria)->toArray();
    }
}
