<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

class AppFixtures extends Fixture
{
    public function __construct(private PasswordHasherFactoryInterface $PasswordHasherFactoryInterface)
    {

    }

    public function load(ObjectManager $manager, )
    {
        $user = new User();
        $user->setEmail('admin@test.com');
        $user->setPassword($this->PasswordHasherFactoryInterface->getPasswordHasher($user)->hash('test'));
        $user->setName('Vitali');
        $user->setSurname('Tarnavskyi');
        $user->setMiddleName('Olegivich');
        $user->setStatus(User::STATUS['active']);
        $user->setRole(User::ROLES['traveller']);
        $manager->persist($user);

        $manager->flush();
    }
}
