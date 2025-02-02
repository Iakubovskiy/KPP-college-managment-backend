<?php

use App\Entity\Grade;
use Doctrine\ORM\EntityManager;

class GradeService{
    private EntityManager $em;

    public function __construct(EntityManager $em){
        $this->em = $em;
    }
    
    public function createGrade(Grade $grade): Grade{
        $this->em->persist($grade);
        $this->em->flush();
        return $grade;
    }

    public function updateGrade(int $id, int $grade): Grade{
        $oldGrade = $this->em->getRepository(Grade::class)->find($id);
        $oldGrade->setGrade($grade);
        $this->em->persist($oldGrade);
        $this->em->flush();
        return $oldGrade;
    }

    public function deleteGrade(int $id): bool{
        $grade = $this->em->getRepository(Grade::class)->find($id);
        if(!$grade){
            return false;
        }
        $this->em->remove($grade);
        $this->em->flush();
        return true;
    }

}
