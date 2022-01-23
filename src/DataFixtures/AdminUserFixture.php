<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminUserFixture extends Fixture
{
    private $passwordHassher;

    public function __construct(UserPasswordHasherInterface $password_hasher)
    {
        $this->passwordHassher = $password_hasher;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('admin@admin.com');
        $user->setFirstName('Admin');
        $user->setLastName('Super');
        $user->setRoles(["ROLE_ADMIN", "ROLE_USER"]);
        $user->setStatus(true);
        $user->setIsVerified(true);
        $user->setPassword($this->passwordHassher->hashPassword($user, 'admin1234'));

        $manager->persist($user);
        $manager->flush();
    }
}
