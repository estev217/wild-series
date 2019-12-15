<?php

namespace App\DataFixtures;

use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        $i = 0;
        for ($s = 0; $s < 6; $s++) {
            for ($n = 1; $n < 11; $n++) {
                $season = new Season();
                $season->setNumber($n);
                $season->setYear($faker->year($max = 'now'));
                $season->setDescription($faker->text($maxNbChars = 400));
                $manager->persist($season);
                $this->addReference('season_' . $i, $season);
                $i++;
                $season->setProgram($this->getReference('program_' . $s));
            }
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [ProgramFixtures::class];
    }
}
