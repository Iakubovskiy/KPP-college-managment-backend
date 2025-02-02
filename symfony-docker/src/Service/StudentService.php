<?php

use App\Entity\Grade;
use App\Entity\Student;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;

class StudentService{
    private EntityManager $em;

    public function __construct(EntityManager $em){
        $this->em = $em;
    }

    public function getStudentGradesInSubject(int $studentId, int $subjectid) : array{
        $student = $this->em->getRepository(Student::class)->find($studentId);
        
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq("subject",$subjectid));

        return $student->getGrades()->matching($criteria)->toArray();
    }
}
