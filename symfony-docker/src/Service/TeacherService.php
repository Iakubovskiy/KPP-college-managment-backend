<?php

use App\Entity\Teacher;
use Doctrine\ORM\EntityManager;

class TeacherService{
    private EntityManager $em;

    public function __construct(EntityManager $em){
        $this->em = $em;
    }

    public function getTeacherSchedual(int $id): array{
        /**
         * @var Teacher $teacher
         */
        $teacher = $this->em->getRepository(Teacher::class)->find($id);
        if(!$teacher){
            return [];
        }
        $subjects = $teacher->getSubjects();
        $scheduale = [];
        foreach($subjects as $subject){
            foreach($subject->setSchedules() as $schedule){
                $scheduale[]=[
                    'subject'=> $subject->getName(),
                    'day'=> $subject->getDay(),
                    'time'=> $schedule->getTime()->format('H:i'),
                    'group'=> $schedule->getGroup()->getName(),
                ];
            }
        }
        return $scheduale;
    }

    public function getTeacherSchedualForDay(int $id, string $day): array{
        /**
         * @var Teacher $teacher
         */
        $teacher = $this->em->getRepository(Teacher::class)->find($id);
        if(!$teacher){
            return [];
        }
        $subjects = $teacher->getSubjects();
        $scheduale = [];
        foreach($subjects as $subject){
            foreach($subject->setSchedules() as $schedule){
                if($schedule->getDay() == $day){
                    $scheduale[]=[
                        'subject'=> $subject->getName(),
                        'day'=> $subject->getDay(),
                        'time'=> $schedule->getTime()->format('H:i'),
                        'group'=> $schedule->getGroup()->getName(),
                    ];
                }
            }
        }
        return $scheduale;
    }
}