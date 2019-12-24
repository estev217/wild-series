<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;
use App\Service\Slugify;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        $i = 0;
        $slugify = new Slugify();
        for ($s = 0; $s < 10; $s++) {
            for ($n = 1; $n < 11; $n++) {
                $episode = new Episode();
                $episode->setTitle($faker->sentence($nbWords = 4, $variableNbWords = true));
                $episode->setNumber($n);
                $episode->setSynopsis($faker->text(200));
                $episode->setSlug($slugify->generate($episode->getTitle()));
                $manager->persist($episode);
                $this->addReference('episode_' . $i, $episode);
                $i++;
                $episode->setSeason($this->getReference('season_' . $s));
            }
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [SeasonFixtures::class];
    }
}
