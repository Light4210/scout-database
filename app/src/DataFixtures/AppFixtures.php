<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Struct;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use function Symfony\Component\String\u;

class AppFixtures extends Fixture
{
    const LOAD_FIXTURES = [
        'TRAVELERS' => 10,
        'SCOUTS' => 35,
        'WOLVES' => 40,
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
            $user->setMinistry($this->faker->randomElement(array_keys(User::ACTIVE_MINISTRY)));
            $user->setDateOfBirth($this->faker->dateTimeBetween('--30 years', '++30 years'));
            $user->setPassword($this->PasswordHasherFactoryInterface->getPasswordHasher($user)->hash($this->faker->password()));
            $user->setName($this->faker->firstName());
            $user->setSurname($this->faker->lastName());
            $user->setMiddleName($this->faker->firstName());
            $user->setStatus(User::STATUS_ACTIVE);
            $user->setRole(User::ROLE_TRAVELLER);
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
            $user->setStatus(User::STATUS_ACTIVE);
            $user->setRole(User::ROLE_SCOUT);
            $user->setCreatedAt(new \DateTimeImmutable());
            $manager->persist($user);
            echo "generated $i user \n";
        }

        for ($i = 0; $i < $this::LOAD_FIXTURES['WOLVES']; $i++) {
            $user = new User();
            $user->setEmail($this->faker->email());
            $user->setAddress($this->faker->address());
            $user->setPhoneNumber((int)$this->faker->phoneNumber());
            $user->setDateOfBirth($this->faker->dateTimeBetween('--30 years', '++30 years'));
            $user->setName($this->faker->firstName());
            $user->setSurname($this->faker->lastName());
            $user->setMiddleName($this->faker->firstName());
            $user->setStatus(User::STATUS_ACTIVE);
            $user->setRole(User::ROLE_WOLVES);
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
            $user->setStatus(User::STATUS_PASSIVE);
            $user->setRole($this->faker->randomElement([User::ROLE_SCOUT, User::ROLE_TRAVELLER, User::ROLE_WOLVES]));
            $user->setCreatedAt(new \DateTimeImmutable());
            $manager->persist($user);
            echo "generated $i user \n";
        }

        $manager->flush();

        $userRepository = $manager->getRepository(User::class);
        $userWithoutStruct = $userRepository->findBy(['role' => User::ROLE_TRAVELLER, 'status' => User::STATUS_ACTIVE], [], self::LOAD_FIXTURES['STRUCTS']);

        for ($i = 0; $i < $this::LOAD_FIXTURES['STRUCTS']; $i++) {
            /** @var User $user */
            $user = $userWithoutStruct[$i];
            $struct = new Struct();
            $val = User::ACTIVE_MINISTRY[$user->getMinistry()]['struct_slug'];
            $struct->setType($val);
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
            $members = $userRepository->findBy(['role' => User::ACTIVE_MINISTRY[$user->getMinistry()], 'struct' => null, 'status' => User::STATUS_ACTIVE], [], 20);
            foreach ($members as $member) {
                $member->setStruct($user->getSheafOf());
                $manager->persist($member);
            }
            $manager->flush();
        }

        $circle = $manager->getRepository(Struct::class)->findOneBy(['type' => Struct::STRUCT['circle']['slug']]);
        $struct = $manager->getRepository(Struct::class)->findOneBy(['type' => Struct::STRUCT['troop']['slug']]);
        if (empty($troop)) {
            $struct = $manager->getRepository(Struct::class)->findOneBy(['type' => Struct::STRUCT['community']['slug']]);
        }
        $user = new User();
        $user->setEmail('admin@test.com');
        $user->setPassword($this->PasswordHasherFactoryInterface->getPasswordHasher($user)->hash('test'));
        $user->setName('Vitali');
        $user->setSurname('Tarnavskyi');
        $user->setMiddleName('Olegivich');
        $user->setStatus(User::STATUS_ACTIVE);
        $user->setRole(User::ROLE_TRAVELLER);
        $user->setMinistry(Struct::STRUCT[$struct->getType()]['sheaf']['slug']);
        $user->setSheafOf($struct);
        $user->setStruct($circle);
        $user->setCreatedAt(new \DateTimeImmutable());
        $struct->setSheaf($user);
        $struct->setName($this->faker->company());
        $manager->persist($user);
        $manager->persist($struct);
        $manager->flush();
    }
}
