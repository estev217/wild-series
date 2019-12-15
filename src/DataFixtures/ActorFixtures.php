<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;

class ActorFixtures extends Fixture implements DependentFixtureInterface
{
    const ACTORS = [
        'Jean Dujardin',
        'Eric Judor',
        'Danny Boon',
        'Omar Sy',
        'Franck Dubosc',
        'Guillaume Canet',
    ];

    public function load(ObjectManager $manager)
    {
        foreach (self::ACTORS as $key => $actorName) {
            $actor = new Actor();
            $actor->setName($actorName);
            $manager->persist($actor);
            $this->addReference('acteur_' . $key, $actor);
        }
        $faker = Faker\Factory::create('en_US');
        for ($i = 6; $i < 56; $i++) {
            $fakeActor = new Actor();
            $fakeActor->setName($faker->name);
            $manager->persist($fakeActor);
            $this->addReference('acteur_' . $i, $fakeActor);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
    return [ProgramFixtures::class];
    }
}
