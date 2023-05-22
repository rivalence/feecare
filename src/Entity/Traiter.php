<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Traiter
 *
 * @ORM\Table(name="traiter", indexes={@ORM\Index(name="IDX_D4A42E866971227", columns={"educateur_key"}), @ORM\Index(name="IDX_D4A42E86B062378", columns={"famille_key"})})
 * @ORM\Entity
 */
class Traiter
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_traiter", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="traiter_id_traiter_seq", allocationSize=1, initialValue=1)
     */
    private $idTraiter;

    /**
     * @var Users
     *
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="educateur_key", referencedColumnName="id_user")
     * })
     */
    private $educateurKey;

    /**
     * @var Users
     *
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="famille_key", referencedColumnName="id_user")
     * })
     */
    private $familleKey;

    public function getIdTraiter(): ?int
    {
        return $this->idTraiter;
    }

    public function getEducateurKey(): ?Users
    {
        return $this->educateurKey;
    }

    public function setEducateurKey(?Users $educateurKey): self
    {
        $this->educateurKey = $educateurKey;

        return $this;
    }

    public function getFamilleKey(): ?Users
    {
        return $this->familleKey;
    }

    public function setFamilleKey(?Users $familleKey): self
    {
        $this->familleKey = $familleKey;

        return $this;
    }


}
