<?php

namespace App\DataFixtures;

use App\Entity\Categories;
use App\Entity\Traits\SlugTrait;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoriesFixtures extends Fixture
{
    private $counter = 1;
    private $i=1;

    public function __construct(private SluggerInterface $slugger){}

    public function load(ObjectManager $manager): void
    {
        $parent = $this->createCategory('Circuits', null, $manager);
        
        $this->createCategory('Europe', $parent, $manager);
        $this->createCategory('Amérique du Nord', $parent, $manager);
        $this->createCategory('Amérique du Sud', $parent, $manager);
        $this->createCategory('Afrique', $parent, $manager);
        $this->createCategory('Asie', $parent, $manager);
        $this->createCategory('Moyen-Orient', $parent, $manager);
        $this->createCategory('Océanie', $parent, $manager);





      $parent = $this->createCategory(' Activiés', null, $manager);

        $this->createCategory('Activités hivernales', $parent, $manager);
        $this->createCategory('Activités istivales', $parent, $manager);
       
                
       

       
        $manager->flush();
    }

    public function createCategory(string $name, Categories $parent = null, ObjectManager $manager)
    {
        $category = new Categories();
        $category->setName($name);
        $category->setSlug($this->slugger->slug($category->getName())->lower());
        $category->setCategoryReservation($this->i++);
        $category->setParent($parent);
        $manager->persist($category);

        $this->addReference('cat-'.$this->counter, $category);
        $this->counter++;

        return $category;
    }
}
