<?php

namespace App\DataFixtures;

use App\Entity\Circuits;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;
use Faker;

class CircuitsFixtures extends Fixture
{
    public function __construct(private SluggerInterface $slugger){}

    public function load(ObjectManager $manager): void
    {
        // use the factory to create a Faker\Generator instance
        $faker = Faker\Factory::create('fr_FR');

        for($prod = 1; $prod <= 10; $prod++){
            $circuit = new Circuits();
            $circuit->setName($faker->text(15));
            $circuit->setDescription($faker->text());
            $circuit->setSlug($this->slugger->slug($circuit->getName())->lower());
            $circuit->setPrice($faker->numberBetween(900, 150000));
            $circuit->setStock($faker->numberBetween(0, 10));

            //On va chercher une référence de catégorie
            $category = $this->getReference('cat-'. rand(1, 8));
            $circuit->setCategories($category);

            $this->setReference('prod-'.$prod, $circuit);
            $manager->persist($circuit);
        }

        $manager->flush();
    }
}
