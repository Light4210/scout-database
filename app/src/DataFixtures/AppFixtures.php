<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Struct;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

class AppFixtures extends Fixture
{
    const LOAD_FIXTURES = [
        'TRAVELERS' => 5,
        'SCOUTS' => 20,
        'WOLVES' => 20,
        'STRUCTS' => 10
    ];

    protected $faker;

    public function __construct(
        private PasswordHasherFactoryInterface $PasswordHasherFactoryInterface)
    {

    }

    public function load(ObjectManager $manager)
    {
        $this->faker = Factory::create();
        $circle = new Struct();
        $circle->withAllData(Struct::CIRCLE_SLUG, $this->faker->company(), $this->faker->city(), $this->faker->address());
        $user = new User();
        $user->setEmail('admin@test.com');
        $user->setPassword($this->PasswordHasherFactoryInterface->getPasswordHasher($user)->hash('test'));
        $user->setName('Vitali');
        $user->setSurname('Tarnavskyi');
        $user->setMiddleName('Olegivich');
        $user->setStatus(User::STATUS_ACTIVE);
        $user->setRoles(['admin']);
        $user->setRole(User::ROLE_TRAVELLER);
        $user->setStruct($circle);
        $user->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($user);
        $sheaf = new User();
        $sheaf->setEmail($this->faker->email());
        $sheaf->setPassword($this->PasswordHasherFactoryInterface->getPasswordHasher($sheaf)->hash('test'));
        $sheaf->setName($this->faker->firstName());
        $sheaf->setSurname($this->faker->lastName());
        $sheaf->setMinistry(User::ACTIVE_MINISTRY['sheaf']['slug']);
        $sheaf->setMiddleName($this->faker->firstName());
        $sheaf->setStatus(User::STATUS_ACTIVE);
        $sheaf->setSheafOf($circle);
        $sheaf->setRoles(['admin']);
        $sheaf->setRole(User::ROLE_TRAVELLER);
        $sheaf->setStruct($circle);
        $sheaf->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($sheaf);
        $circle->setSheaf($sheaf);
        $manager->persist($circle);
        for ($i = 0; $i < $this::LOAD_FIXTURES['TRAVELERS']; $i++) {
            echo 'no';
            /////////////////struct////////////////
            $struct = new Struct();
            $struct->withAllData(Struct::COMMUNITY_SLUG, $this->faker->company(), $this->faker->city(), $this->faker->address());
            $manager->persist($struct);
            /////////////////traveller////////////////
            $traveller = new User();
            $traveller->withAllData(
                $this->faker->email(),
                [],
                $this->faker->firstName(),
                $this->faker->lastName(),
                $this->faker->firstName(),
                $this->faker->dateTimeBetween('--30 years', '++30 years'),
                (int)$this->faker->phoneNumber(),
                User::STATUS_ACTIVE,
                User::ACTIVE_MINISTRY['akela']['slug'],
                $this->faker->address(),
                User::ROLE_TRAVELLER
            );
            $traveller->setSheafOf($struct);
            $struct->setSheaf($traveller);
            $traveller->setStruct($circle);
            $manager->persist($traveller);
            /////////////////wolves////////////////
            for ($j = 0; $j < $this::LOAD_FIXTURES['WOLVES']; $j++) {
                $scout = new User();
                $scout->setEmail($this->faker->email());
                $scout->setAddress($this->faker->address());
                $scout->setPhoneNumber((int)$this->faker->phoneNumber());
                $scout->setDateOfBirth($this->faker->dateTimeBetween('--30 years', '++30 years'));
                $scout->setName($this->faker->firstName());
                $scout->setStruct($struct);
                $scout->setSurname($this->faker->lastName());
                $scout->setMiddleName($this->faker->firstName());
                $scout->setStatus(User::STATUS_ACTIVE);
                $scout->setRole(User::ROLE_WOLVES);
                $scout->setCreatedAt(new \DateTimeImmutable());
                $manager->persist($scout);
                echo "generated $j user \n";
            }
            $manager->flush();
        }

        $circle = new Struct();
        $circle->withAllData(Struct::CIRCLE_SLUG, $this->faker->company(), $this->faker->city(), $this->faker->address());
        $sheaf = new User();
        $sheaf->setEmail($this->faker->email());
        $sheaf->setPassword($this->PasswordHasherFactoryInterface->getPasswordHasher($sheaf)->hash('test'));
        $sheaf->setName($this->faker->firstName());
        $sheaf->setSurname($this->faker->lastName());
        $sheaf->setMinistry(User::ACTIVE_MINISTRY['sheaf']['slug']);
        $sheaf->setMiddleName($this->faker->firstName());
        $sheaf->setStatus(User::STATUS_ACTIVE);
        $sheaf->setRoles(['admin']);
        $sheaf->setRole(User::ROLE_TRAVELLER);
        $sheaf->setStruct($circle);
        $sheaf->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($sheaf);
        $circle->setSheaf($sheaf);
        $manager->persist($circle);
        for ($i = 0; $i < $this::LOAD_FIXTURES['TRAVELERS']; $i++) {
            echo 'no';
            /////////////////struct////////////////
            $struct = new Struct();
            $struct->withAllData(Struct::TROOP_SLUG, $this->faker->company(), $this->faker->city(), $this->faker->address());
            $manager->persist($struct);
            /////////////////traveller////////////////
            $traveller = new User();
            $traveller->withAllData(
                $this->faker->email(),
                [],
                $this->faker->firstName(),
                $this->faker->lastName(),
                $this->faker->firstName(),
                $this->faker->dateTimeBetween('--30 years', '++30 years'),
                (int)$this->faker->phoneNumber(),
                User::STATUS_ACTIVE,
                User::ACTIVE_MINISTRY['troopLeader']['slug'],
                $this->faker->address(),
                User::ROLE_TRAVELLER
            );
            $traveller->setSheafOf($struct);
            $struct->setSheaf($traveller);
            $traveller->setStruct($circle);
            $manager->persist($traveller);
            /////////////////scout////////////////
            for ($j = 0; $j < $this::LOAD_FIXTURES['WOLVES']; $j++) {
                $scout = new User();
                $scout->setEmail($this->faker->email());
                $scout->setAddress($this->faker->address());
                $scout->setPhoneNumber((int)$this->faker->phoneNumber());
                $scout->setDateOfBirth($this->faker->dateTimeBetween('--30 years', '++30 years'));
                $scout->setName($this->faker->firstName());
                $scout->setStruct($struct);
                $scout->setSurname($this->faker->lastName());
                $scout->setMiddleName($this->faker->firstName());
                $scout->setStatus(User::STATUS_ACTIVE);
                $scout->setRole(User::ROLE_SCOUT);
                $scout->setCreatedAt(new \DateTimeImmutable());
                $manager->persist($scout);
                echo "generated $j user \n";
            }
            $manager->flush();
        }
    }
}
