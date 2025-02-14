<?php
namespace App\Service;

use App\Entity\Teacher;
use Doctrine\ORM\EntityManagerInterface;

class TeacherService{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em){
        $this->em = $em;
    }

    public function GetAllTeachers(): array
    {
        return $this->em->getRepository(Teacher::class)->findAll();

    }

    public function getTeacherSchedule(int $id): array{
        /**
         * @var Teacher $teacher
         */
        $teacher = $this->em->getRepository(Teacher::class)->find($id);
        if(!$teacher){
            return [];
        }
        $subjects = $teacher->getSubjects();
        $schedules = [];
        foreach($subjects as $subject){
            foreach($subject->getSchedules() as $schedule){
                $schedules[]=[
                    'subject'=> $subject->getName(),
                    'day'=> $schedule->getDay(),
                    'time'=> $schedule->getTime()->format('H:i'),
                    'group'=> $schedule->getGroup()->getName(),
                ];
            }
        }
        return $schedules;
    }

    public function getTeacherScheduleForDay(int $id, string $day): array{
        /**
         * @var Teacher $teacher
         */
        $teacher = $this->em->getRepository(Teacher::class)->find($id);
        if(!$teacher){
            return [];
        }
        $subjects = $teacher->getSubjects();
        $schedules = [];
        foreach($subjects as $subject){
            foreach($subject->getSchedules() as $schedule){
                if($schedule->getDay() == $day){
                    $schedules[]=[
                        'id'=> $schedule->getId(),
                        'subject'=> $subject->getName(),
                        'day'=> $schedule->getDay(),
                        'time'=> $schedule->getTime()->format('H:i'),
                        'group'=> $schedule->getGroup()->getName(),
                    ];
                }
            }
        }
        return $schedules;
    }
}
