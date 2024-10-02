<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\Fixtures;

use BestEverWorld\AirportManager\App\Entity\User;
use BestEverWorld\AirportManager\App\Model\UserRoleModel;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements FixtureGroupInterface
{
    public function __construct(
        private readonly UserPasswordHasherInterface $userPasswordHasher,
    ) {
    }

    public static function getGroups(): array
    {
        return ['default'];
    }

    public function load(ObjectManager $manager): void
    {
        foreach ($this->getData() as $data) {
            $entity = (new User(...array_values($data)));
            $entity->setPassword($this->userPasswordHasher->hashPassword($entity, $data['password']));

            $this->addReference(sprintf('user_ref:%s', $data['username']), $entity);

            $manager->persist($entity);
        }

        $manager->flush();
    }

    /**
     * @return array<array-key, array<array-key, string|array|bool>>
     */
    private function getData(): iterable
    {
        yield [
            'first_name' => 'Airport',
            'last_name' => 'Administrator',
            'username' => 'airport.administrator@example.org',
            'password' => 'airport.administrator@example.org',
            'roles' => [UserRoleModel::ROLE_USER, UserRoleModel::ROLE_ADMIN],
            'approved' => true,
            'disabled' => false,
        ];
        yield [
            'first_name' => 'Nicolaus',
            'last_name' => 'Copernicus',
            'username' => 'nicolaus.copernicus@example.org',
            'password' => 'nicolaus.copernicus@example.org',
            'roles' => [UserRoleModel::ROLE_USER, UserRoleModel::ROLE_ADMIN],
            'approved' => true,
            'disabled' => false,
        ];
        yield [
            'first_name' => 'Galileo',
            'last_name' => 'Galilei',
            'username' => 'galileo.galilei@example.org',
            'password' => 'galileo.galilei@example.org',
            'roles' => [UserRoleModel::ROLE_USER, UserRoleModel::ROLE_ADMIN],
            'approved' => true,
            'disabled' => false,
        ];
        yield [
            'first_name' => 'Robert',
            'last_name' => 'Boyle',
            'username' => 'robert.boyle@example.org',
            'password' => 'robert.boyle@example.org',
            'roles' => [UserRoleModel::ROLE_USER, UserRoleModel::ROLE_ADMIN],
            'approved' => true,
            'disabled' => false,
        ];
        yield [
            'first_name' => 'Isaac',
            'last_name' => 'Newton',
            'username' => 'isaac.newton@example.org',
            'password' => 'isaac.newton@example.org',
            'roles' => [UserRoleModel::ROLE_USER, UserRoleModel::ROLE_ADMIN],
            'approved' => true,
            'disabled' => false,
        ];
        yield [
            'first_name' => 'Albert',
            'last_name' => 'Einstein',
            'username' => 'albert.einstein@example.org',
            'password' => 'albert.einstein@example.org',
            'roles' => [UserRoleModel::ROLE_USER, UserRoleModel::ROLE_ADMIN],
            'approved' => true,
            'disabled' => false,
        ];
        yield [
            'first_name' => 'Airport',
            'last_name' => 'Operator',
            'username' => 'airport.operator@example.org',
            'password' => 'airport.operator@example.org',
            'roles' => [UserRoleModel::ROLE_USER, UserRoleModel::ROLE_OPERATOR],
            'approved' => true,
            'disabled' => false,
        ];
        yield [
            'first_name' => 'Pablo',
            'last_name' => 'Picasso',
            'username' => 'pablo.picasso@example.org',
            'password' => 'pablo.picasso@example.org',
            'roles' => [UserRoleModel::ROLE_USER, UserRoleModel::ROLE_OPERATOR],
            'approved' => true,
            'disabled' => false,
        ];
        yield [
            'first_name' => 'Michelangelo',
            'last_name' => 'Buonarroti',
            'username' => 'michelangelo.buonarroti@example.org',
            'password' => 'michelangelo.buonarroti@example.org',
            'roles' => [UserRoleModel::ROLE_USER, UserRoleModel::ROLE_OPERATOR],
            'approved' => true,
            'disabled' => false,
        ];
        yield [
            'first_name' => 'Henri',
            'last_name' => 'Matisse',
            'username' => 'henri.matisse@example.org',
            'password' => 'henri.matisse@example.org',
            'roles' => [UserRoleModel::ROLE_USER, UserRoleModel::ROLE_OPERATOR],
            'approved' => true,
            'disabled' => false,
        ];
        yield [
            'first_name' => 'Edvard',
            'last_name' => 'Munch',
            'username' => 'edvard.munch@example.org',
            'password' => 'edvard.munch@example.org',
            'roles' => [UserRoleModel::ROLE_USER, UserRoleModel::ROLE_OPERATOR],
            'approved' => true,
            'disabled' => false,
        ];
        yield [
            'first_name' => 'Johannes ',
            'last_name' => 'Vermeer',
            'username' => 'johannes.vermeer@example.org',
            'password' => 'johannes.vermeer@example.org',
            'roles' => [UserRoleModel::ROLE_USER, UserRoleModel::ROLE_OPERATOR],
            'approved' => true,
            'disabled' => false,
        ];
    }
}
