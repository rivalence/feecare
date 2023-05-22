<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Identifiant
 *
 * @ORM\Table(name="identifiant")
 * @ORM\Entity
 */
class Identifiant
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="identifiant_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="libelle", type="string", length=15, nullable=true)
     */
    private $libelle;

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }


}
