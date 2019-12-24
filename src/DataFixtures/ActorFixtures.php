<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;
use App\Service\Slugify;

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
        $slugify = new Slugify();
        foreach (self::ACTORS as $key => $actorName) {
            $actor = new Actor();
            $actor->setName($actorName);
            $actor->setSlug($slugify->generate($actorName));
            $manager->persist($actor);
            $this->addReference('acteur_' . $key, $actor);
        }
        $faker = Faker\Factory::create('en_US');
        for ($i = 6; $i < 56; $i++) {
            $fakeActor = new Actor();
            $fakeActor->setName($faker->name);
            $fakeActor->setSlug($slugify->generate($fakeActor->getName()));
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
