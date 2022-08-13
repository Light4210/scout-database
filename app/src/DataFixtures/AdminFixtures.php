<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Struct;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

class AdminFixtures extends Fixture
{

    protected $faker;

    public function __construct(
        private PasswordHasherFactoryInterface $PasswordHasherFactoryInterface)
    {

    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('admin@test.com');
        $user->setPassword($this->PasswordHasherFactoryInterface->getPasswordHasher($user)->hash('test'));
        $user->setName('Vitali');
        $user->setGender(User::MALE);
        $user->setSurname('Tarnavskyi');
        $user->setMiddleName('Olegivich');
        $user->setMinistry(User::ROLE_ADMIN);
        $user->setStatus(User::STATUS_ACTIVE);
        $user->setRoles(['admin']);
        $user->setRole(User::ROLE_TRAVELLER);
        $user->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($user);
        $manager->flush();
    }
}
