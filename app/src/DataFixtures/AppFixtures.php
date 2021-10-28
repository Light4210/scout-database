<?php

namespace App\DataFixtures;

use App\Entity\Struct;
use App\Entity\User;
use App\Repository\StructRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

class AppFixtures extends Fixture
{
    const LOAD_FIXTURES = [
        'TRAVELERS' => 10,
        'SCOUTS' => 35,
        'WOLVIES' => 40,
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
        for ($i = 0; $i < $this::LOAD_FIXTURES['TRAVELERS']; $i++) {
            $user = new User();
            $user->setEmail($this->faker->email());
            $user->setAddress($this->faker->address());
            $user->setPhoneNumber((int)$this->faker->phoneNumber());
            $user->setMinistry($this->faker->randomElement(array_keys(User::MINISTRY)));
            $user->setDateOfBirth($this->faker->dateTimeBetween('--30 years', '++30 years'));
            $user->setPassword($this->PasswordHasherFactoryInterface->getPasswordHasher($user)->hash($this->faker->password()));
            $user->setName($this->faker->firstName());
            $user->setSurname($this->faker->lastName());
            $user->setMiddleName($this->faker->firstName());
            $user->setStatus(User::STATUS['active']);
            $user->setRole(User::ROLES['traveller']);
            $user->setCreatedAt(new \DateTimeImmutable());
            $manager->persist($user);
            echo "generated $i user \n";
        }

        for ($i = 0; $i < $this::LOAD_FIXTURES['SCOUTS']; $i++) {
            $user = new User();
            $user->setEmail($this->faker->email());
            $user->setAddress($this->faker->address());
            $user->setPhoneNumber((int)$this->faker->phoneNumber());
            $user->setDateOfBirth($this->faker->dateTimeBetween('--30 years', '++30 years'));
            $user->setName($this->faker->firstName());
            $user->setSurname($this->faker->lastName());
            $user->setMiddleName($this->faker->firstName());
            $user->setStatus(User::STATUS['active']);
            $user->setRole(User::ROLES['scout']);
            $user->setCreatedAt(new \DateTimeImmutable());
            $manager->persist($user);
            echo "generated $i user \n";
        }

        for ($i = 0; $i < $this::LOAD_FIXTURES['WOLVIES']; $i++) {
            $user = new User();
            $user->setEmail($this->faker->email());
            $user->setAddress($this->faker->address());
            $user->setPhoneNumber((int)$this->faker->phoneNumber());
            $user->setDateOfBirth($this->faker->dateTimeBetween('--30 years', '++30 years'));
            $user->setName($this->faker->firstName());
            $user->setSurname($this->faker->lastName());
            $user->setMiddleName($this->faker->firstName());
            $user->setStatus(User::STATUS['active']);
            $user->setRole(User::ROLES['wolvies']);
            $user->setCreatedAt(new \DateTimeImmutable());
            $manager->persist($user);
            echo "generated $i user \n";
        }

        for ($i = 0; $i < $this::LOAD_FIXTURES['TRAVELERS']; $i++) {
            $user = new User();
            $user->setEmail($this->faker->email());
            $user->setAddress($this->faker->address());
            $user->setPhoneNumber((int)$this->faker->phoneNumber());
            $user->setDateOfBirth($this->faker->dateTimeBetween('--30 years', '++30 years'));
            $user->setName($this->faker->firstName());
            $user->setSurname($this->faker->lastName());
            $user->setMiddleName($this->faker->firstName());
            $user->setStatus(User::STATUS['passive']);
            $user->setRole($this->faker->randomElement(User::ROLES));
            $user->setCreatedAt(new \DateTimeImmutable());
            $manager->persist($user);
            echo "generated $i user \n";
        }

        $manager->flush();

        $userRepository = $manager->getRepository(User::class);
        $userWithoutStruct = $userRepository->findBy(['role' => User::ROLES['traveller'], 'status' => User::STATUS['active']], [], self::LOAD_FIXTURES['STRUCTS']);


        for ($i = 0; $i < $this::LOAD_FIXTURES['STRUCTS']; $i++) {
            $user = $userWithoutStruct[$i];
            $struct = new Struct();
            $struct->setType(Struct::STRUCT[User::MINISTRY[$user->getMinistry()]['sheafOf']]['name']);
            $struct->setAddress($this->faker->address());
            $struct->setName($this->faker->company());
            $struct->setCity($this->faker->city());
            $struct->setSheaf($user);
            $struct->setCreatedAt(new \DateTimeImmutable());
            $manager->persist($struct);
            $manager->persist($user);
            echo "generated $i struct \n";
        }

        $manager->flush();

        $usersWithStruct = $userWithoutStruct;

        foreach ($usersWithStruct as $user) {
            $members = $userRepository->findBy(['role' => User::ROLES[User::MINISTRY[$user->getMinistry()]['membersRole']], 'struct' => null, 'status' => User::STATUS['active']], [], 20);
            foreach ($members as $member) {
                $member->setStruct($user->getSheafOf());
                $manager->persist($member);
            }
            $manager->flush();
        }

        $circle = $manager->getRepository(Struct::class)->findOneBy(['type'=>Struct::STRUCT_NAMES['circle']]);
        $struct = $manager->getRepository(Struct::class)->findOneBy(['type'=>Struct::STRUCT_NAMES['troop']]);
        if(empty($troop)){
            $struct = $manager->getRepository(Struct::class)->findOneBy(['type'=>Struct::STRUCT_NAMES['community']]);
        }
        $user = new User();
        $user->setEmail('admin@test.com');
        $user->setPassword($this->PasswordHasherFactoryInterface->getPasswordHasher($user)->hash('test'));
        $user->setName('Vitali');
        $user->setSurname('Tarnavskyi');
        $user->setMiddleName('Olegivich');
        $user->setStatus(User::STATUS['active']);
        $user->setRole(User::ROLES['traveller']);
        $user->setMinistry(Struct::STRUCT[$struct->getType()]['sheaf']['name']);
        $user->setSheafOf($struct);
        $user->setStruct($circle);
        $user->setCreatedAt(new \DateTimeImmutable());
        $struct->setSheaf($user);
        $manager->persist($user);
        $manager->persist($struct);
        $manager->flush();
    }
}
