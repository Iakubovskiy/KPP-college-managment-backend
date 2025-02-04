<?php
namespace App\Service;

use App\Entity\Group;
use Doctrine\ORM\EntityManagerInterface;

class GroupService {

    private EntityManagerInterface $entityManager;
    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }

    public function getAllGroups() : array {
        $groups = $this->entityManager->getRepository(Group::class)->findAll();
        return $groups;
    }

    public function getGroupById(int $id) : ?Group {
        $group = $this->entityManager->getRepository(Group::class)->find($id);
        if (!$group) {
            return null;
        }
        return $group;
    }

    public function getAllStudentsInGroup(int $group_id) : array {
        $group = $this->entityManager->getRepository(Group::class)->find($group_id);
        return $group->getStudents();
    }

    public function getGroupSchedule(int $group_id) : array {
        $group = $this->entityManager->getRepository(Group::class)->find($group_id);
        if (!$group) {
            return [];
        }
        return $group->getScheduleDays();
    }

    public function createGroup(Group $group): Group{
        $this->entityManager->persist($group);
        $this->entityManager->flush();
        return $group;
    }

    public function updateGroup(int $id, array $data) : ?Group {
        $group = $this->entityManager->getRepository(Group::class)->find($id);
        if (!$group) {
            return null;
        }
        if (isset($data["name"])) {
            $group->setName($data["name"]);
        }
        $this->entityManager->flush();
        return $group;
    }

    public function deleteGroup(int $id) : bool {
        $group = $this->entityManager->getRepository(Group::class)->find($id);
        if (!$group) {
         return false;
        }
        $this->entityManager->remove($group);
        $this->entityManager->flush();
        return true;
    }
}
