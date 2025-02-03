<?php
namespace App\Service;

use App\Entity\Admin;
use App\Entity\Group;
use App\Entity\Student;
use App\Entity\Teacher;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AuthService {
    private EntityManagerInterface $em;
    private UserPasswordHasherInterface $passwordHarsher;

    public function __construct(EntityManagerInterface $em, UserPasswordHasherInterface $passwordHarsher) {
        $this->em = $em;
        $this->passwordHarsher = $passwordHarsher;
    }

    public function registe(string $email, string $password, string $role, string $firstName, string $surname, array $extraData): User {
        $existingUser = $this->em->getRepository(User::class)->findOneBy(["email"=> $email]);
        if($existingUser) {
            throw new \Exception("This email is allready used");
        }

        switch($role) {
            case "ROLE_STUDENT":
                $user = new Student();
                if(isset($extraData['date_of_birth'])) {
                    $user->setDateOfBirth(new \DateTime($extraData['date_of_birth']));
                }
                if(isset($extraData['group_id'])) {
                    /**
                     * @var Group $group
                     */
                    $group = $this->em->getRepository(Group::class)->find($extraData['group_id']);
                    if(!$group)
                    {
                        throw new \Exception('not correct id');
                    }
                    $user->setGroup($group);
                }
                break;

            case 'ROLE_ADMIN':
                $user = new Admin();
                break;

            case 'ROLE_TEACHER':
                $user = new Teacher();
                break;

            default:
                throw new \Exception('invalide role');
        }
        $user->setFirstName($firstName);
        $user->setSurname($surname);
        $user->setEmail($email);
        $hashed_password = $this->passwordHarsher->hashPassword($user, $password);
        $user->setPassword($hashed_password);

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }
}
