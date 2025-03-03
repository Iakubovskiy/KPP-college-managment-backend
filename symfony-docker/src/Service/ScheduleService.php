<?php
namespace App\Service;

use App\Entity\Schedule;
use Doctrine\ORM\EntityManagerInterface;

class ScheduleService{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em){
        $this->em = $em;
    }

    public function getAll():array
    {
        $schedules = $this->em->getRepository(Schedule::class)->findAll();
        $new_schedules = [];
        foreach ($schedules as $schedule){
            $new_schedules[] = [
                'id' => $schedule->getId(),
                'subject'=> $schedule->getSubject(),
                '_day'=> $schedule->getDay(),
                'time'=> $schedule->getTime()->format('H:i'),
                'group'=> $schedule->getGroup(),
            ];
        }
        return $new_schedules;
    }

    public function createScheduleRecord(Schedule $schedule):Schedule{
        $this->em->persist($schedule);
        $this->em->flush();
        return $schedule;
    }

    public function updateSchedule(int $id, Schedule $newSchedule): Schedule{
        /**
         * @var Schedule $oldSchedule
         */
        $oldSchedule = $this->em->getRepository(Schedule::class)->find($id);
        $oldSchedule->setDay($newSchedule->getDay());
        $oldSchedule->setTime($newSchedule->getTime());
        $oldSchedule->setGroup($newSchedule->getGroup());
        $oldSchedule->setSubject($newSchedule->getSubject());
        $this->em->persist($oldSchedule);
        $this->em->flush();
        return $oldSchedule;
    }

    public function deleteSchedule(int $id): bool{
        $schedule = $this->em->getRepository(Schedule::class)->find($id);
        if(!$schedule){
            return false;
        }
        $this->em->remove($schedule);
        $this->em->flush();
        return true;
    }
}
