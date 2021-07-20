<?php

namespace App\Entity;

use App\Repository\PizzaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PizzaRepository::class)
 * @ORM\Table(name="t_pizza")
 */
class Pizza
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="DesignPizz", type="string", length=255)
     */
    private $DesignPizz;

    /**
     * @ORM\Column(name="TarifPizz", type="decimal", precision=5, scale=2)
     */
    private $TarifPizz;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDesignPizz(): ?string
    {
        return $this->DesignPizz;
    }

    public function setDesignPizz(string $DesignPizz): self
    {
        $this->DesignPizz = $DesignPizz;

        return $this;
    }

    public function getTarifPizz(): ?string
    {
        return $this->TarifPizz;
    }

    public function setTarifPizz(string $TarifPizz): self
    {
        $this->TarifPizz = $TarifPizz;

        return $this;
    }
}
