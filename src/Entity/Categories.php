<?php

namespace App\Entity;

use App\Entity\Trait\SlugTrait;
use App\Repository\CategoriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Integer;


#[ORM\Entity(repositoryClass: CategoriesRepository::class)]
class Categories
{
    use SlugTrait;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(type: 'integer')]
    private $categoryReservation;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'categories')]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    private $parent = null;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: self::class)]
    private Collection $categories;

    #[ORM\OneToMany(mappedBy: 'categories', targetEntity: Circuits::class)]
    private Collection $circuits;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->circuits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    
    public function getCategoryReservation(): ?int
    {
        return $this->categoryReservation;
    }

    public function setCategoryReservation(int $categoryReservation): self
    {
        $this->categoryReservation = $categoryReservation;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(self $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
            $category->setParent($this);
        }


        return $this;
    }

    public function removeCategory(self $category): self
    {
        if ($this->categories->removeElement($category)) {
            // set the owning side to null (unless already changed)
            if ($category->getParent() === $this) {
                $category->setParent(null);
            }
        }

        return $this;
    }

    /**
     *  @return Collection|Circuits[]
     */
    public function getCircuits(): Collection
    {
        return $this->circuits;
    }

    public function addCircuit(Circuits $circuit): self
    {
        if (!$this->circuits->contains($circuit)) {
            $this->circuits[] = $circuit;
            $circuit->setCategories($this);
        }

        return $this;
    }

    public function removeCircuit(Circuits $circuit): self
    {
        if ($this->circuits->removeElement($circuit)) {
            // set the owning side to null (unless already changed)
            if ($circuit->getCategories() === $this) {
                $circuit->setCategories(null);
            }
        }

        return $this;
    }
}
