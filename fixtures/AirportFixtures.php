<?php

declare(strict_types=1);

namespace BestEverWorld\AirportManager\Fixtures;

use BestEverWorld\AirportManager\App\Entity\Airport;
use BestEverWorld\AirportManager\App\Entity\Country;
use BestEverWorld\AirportManager\App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AirportFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    public static function getGroups(): array
    {
        return ['default'];
    }

    public function getDependencies(): array
    {
        return [
            CountryFixtures::class,
            UserFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        foreach ($this->getData() as $data) {
            $entity = (new Airport(...array_values($data)));

            $this->addReference(sprintf('airport_ref:%s', $data['code']), $entity);

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
            'name' => 'Malta International Airport (Luqa Airport)',
            'code' => 'MLA',
            'city' => 'Luqa / Gudja',
            'country' => $this->getReference('country_ref:MLT', Country::class),
            'created_by' => $this->getReference('user_ref:airport.operator@example.org', User::class),
            'updated_by' => $this->getReference('user_ref:airport.operator@example.org', User::class),
        ];
        yield [
            'name' => 'Xewkija Heliport (Gozo Heliport)',
            'code' => 'GZM',
            'city' => 'Xewkija',
            'country' => $this->getReference('country_ref:MLT', Country::class),
            'created_by' => $this->getReference('user_ref:airport.operator@example.org', User::class),
            'updated_by' => $this->getReference('user_ref:airport.operator@example.org', User::class),
        ];
        yield [
            'name' => 'Comino heliport',
            'code' => 'JCO',
            'city' => 'Comino',
            'country' => $this->getReference('country_ref:MLT', Country::class),
            'created_by' => $this->getReference('user_ref:airport.operator@example.org', User::class),
            'updated_by' => $this->getReference('user_ref:airport.operator@example.org', User::class),
        ];
        yield [
            'name' => 'Adana Airport',
            'code' => 'ADA',
            'city' => 'Adana',
            'country' => $this->getReference('country_ref:TUR', Country::class),
            'created_by' => $this->getReference('user_ref:airport.operator@example.org', User::class),
            'updated_by' => $this->getReference('user_ref:airport.operator@example.org', User::class),
        ];
        yield [
            'name' => 'Antalya Airport',
            'code' => 'AYT',
            'city' => 'Antalya',
            'country' => $this->getReference('country_ref:TUR', Country::class),
            'created_by' => $this->getReference('user_ref:airport.operator@example.org', User::class),
            'updated_by' => $this->getReference('user_ref:airport.operator@example.org', User::class),
        ];
        yield [
            'name' => 'Diyarbakır Airport',
            'code' => 'DIY',
            'city' => 'Diyarbakır',
            'country' => $this->getReference('country_ref:TUR', Country::class),
            'created_by' => $this->getReference('user_ref:airport.operator@example.org', User::class),
            'updated_by' => $this->getReference('user_ref:airport.operator@example.org', User::class),
        ];
        yield [
            'name' => 'Albury Airport',
            'code' => 'ABX',
            'city' => 'Albury',
            'country' => $this->getReference('country_ref:AUS', Country::class),
            'created_by' => $this->getReference('user_ref:airport.operator@example.org', User::class),
            'updated_by' => $this->getReference('user_ref:airport.operator@example.org', User::class),
        ];
        yield [
            'name' => 'Armidale Airport',
            'code' => 'ARM',
            'city' => 'Armidale',
            'country' => $this->getReference('country_ref:AUS', Country::class),
            'created_by' => $this->getReference('user_ref:airport.operator@example.org', User::class),
            'updated_by' => $this->getReference('user_ref:airport.operator@example.org', User::class),
        ];
        yield [
            'name' => 'Ballina Byron Gateway Airport',
            'code' => 'BNK',
            'city' => 'Ballina',
            'country' => $this->getReference('country_ref:AUS', Country::class),
            'created_by' => $this->getReference('user_ref:airport.operator@example.org', User::class),
            'updated_by' => $this->getReference('user_ref:airport.operator@example.org', User::class),
        ];
        yield [
            'name' => 'Abha International Airport',
            'code' => 'AHB',
            'city' => 'Abha',
            'country' => $this->getReference('country_ref:SAU', Country::class),
            'created_by' => $this->getReference('user_ref:airport.operator@example.org', User::class),
            'updated_by' => $this->getReference('user_ref:airport.operator@example.org', User::class),
        ];
        yield [
            'name' => 'Al-Ahsa International Airport',
            'code' => 'HOF',
            'city' => 'Al-Hofuf, Al-Ahsa',
            'country' => $this->getReference('country_ref:SAU', Country::class),
            'created_by' => $this->getReference('user_ref:airport.operator@example.org', User::class),
            'updated_by' => $this->getReference('user_ref:airport.operator@example.org', User::class),
        ];
        yield [
            'name' => 'Prince Naif bin Abdulaziz International Airport',
            'code' => 'ELQ',
            'city' => 'Buraidah',
            'country' => $this->getReference('country_ref:SAU', Country::class),
            'created_by' => $this->getReference('user_ref:airport.operator@example.org', User::class),
            'updated_by' => $this->getReference('user_ref:airport.operator@example.org', User::class),
        ];
        yield [
            'name' => 'Zayed International Airport',
            'code' => 'AUH',
            'city' => 'Abu Dhabi',
            'country' => $this->getReference('country_ref:ARE', Country::class),
            'created_by' => $this->getReference('user_ref:airport.operator@example.org', User::class),
            'updated_by' => $this->getReference('user_ref:airport.operator@example.org', User::class),
        ];
        yield [
            'name' => 'Al Bateen Executive Airport',
            'code' => 'AZI',
            'city' => 'Abu Dhabi',
            'country' => $this->getReference('country_ref:ARE', Country::class),
            'created_by' => $this->getReference('user_ref:airport.operator@example.org', User::class),
            'updated_by' => $this->getReference('user_ref:airport.operator@example.org', User::class),
        ];
        yield [
            'name' => 'Al Ain International Airport',
            'code' => 'AAN',
            'city' => 'Al Ain',
            'country' => $this->getReference('country_ref:ARE', Country::class),
            'created_by' => $this->getReference('user_ref:airport.operator@example.org', User::class),
            'updated_by' => $this->getReference('user_ref:airport.operator@example.org', User::class),
        ];
        yield [
            'name' => 'Kansai International Airport',
            'code' => 'KIX',
            'city' => 'Osaka',
            'country' => $this->getReference('country_ref:JPN', Country::class),
            'created_by' => $this->getReference('user_ref:airport.operator@example.org', User::class),
            'updated_by' => $this->getReference('user_ref:airport.operator@example.org', User::class),
        ];
        yield [
            'name' => 'Narita International Airport',
            'code' => 'NRT',
            'city' => 'Chiba',
            'country' => $this->getReference('country_ref:JPN', Country::class),
            'created_by' => $this->getReference('user_ref:airport.operator@example.org', User::class),
            'updated_by' => $this->getReference('user_ref:airport.operator@example.org', User::class),
        ];
        yield [
            'name' => 'Chubu Centrair International Airport',
            'code' => 'NGO',
            'city' => 'Aichi',
            'country' => $this->getReference('country_ref:JPN', Country::class),
            'created_by' => $this->getReference('user_ref:airport.operator@example.org', User::class),
            'updated_by' => $this->getReference('user_ref:airport.operator@example.org', User::class),
        ];
    }
}
