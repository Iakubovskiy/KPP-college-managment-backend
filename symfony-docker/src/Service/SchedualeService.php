<?php

use App\Entity\Scheduale;
use Doctrine\ORM\EntityManager;

class SchedualeService{
    private EntityManager $em;

    public function __construct(EntityManager $em){
        $this->em = $em;
    }

    public function createSchedualRecord(Scheduale $scheduale):Scheduale{
        $this->em->persist($scheduale);
        $this->em->flush();
        return $scheduale;
    }

    public function updateScheduale(int $id, Scheduale $newScheduale): Scheduale{
        /**
         * @var Scheduale $oldScheduale
         */
        $oldScheduale = $this->em->getRepository(Scheduale::class)->find($id);
        $oldScheduale->setDay($newScheduale->getDay());
        $oldScheduale->setTime($newScheduale->getTime());
        $oldScheduale->setGroup($newScheduale->getGroup());
        $oldScheduale->setSubject($newScheduale->getSubject());
        $this->em->persist($oldScheduale);
        $this->em->flush();
        return $oldScheduale;
    }

    public function deleteScheduale(int $id): bool{
        $sched = $this->em->getRepository(Scheduale::class)->find($id);
        if(!$sched){
            return false;
        }
        $this->em->remove($sched);
        $this->em->flush();
        return true;
    }
}