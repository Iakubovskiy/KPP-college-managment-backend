<?php
namespace App\Service;

use App\Entity\Subject;
use Doctrine\ORM\EntityManagerInterface;

class SubjectService{
    private EntityManagerInterface $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getAllSubjects():array{
        $subjects = $this->em->getRepository(Subject::class)->findAll();
        return $subjects;
    }

    public function getSubjectsById(int $id):Subject{
        $subject = $this->em->getRepository(Subject::class)->find($id);
        return $subject;
    }

    public function createSubject(Subject $subject): Subject{
        $this->em->persist($subject);
        $this->em->flush();
        return $subject;
    }

    public function updateSubject(int $id, Subject $subject):Subject{
        $oldSubject = $this->em->getRepository(Subject::class)->find($id);
        $oldSubject->mapFromOneObjectToAnother($subject);
        $this->em->persist($oldSubject);
        $this->em->flush();
        return $oldSubject;
    }

    public function deleteSubject(int $id):bool{
        $subject = $this->em->getRepository(Subject::class)->find($id);
        $this->em->remove($subject);
        $this->em->flush();
        return true;
    }
}
