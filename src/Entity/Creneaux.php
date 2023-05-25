<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Creneaux
 *
 * @ORM\Table(name="creneaux", indexes={@ORM\Index(name="IDX_77F13C6D6971227", columns={"educateur_key"})})
 * @ORM\Entity
 */
class Creneaux
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_creneau", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="creneaux_id_creneau_seq", allocationSize=1, initialValue=1)
     */
    private $idCreneau;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_creneau", type="date", nullable=true)
     */
    private $dateCreneau;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="time_creneau", type="time", nullable=true)
     */
    private $timeCreneau;

    /**
     * @var Users
     *
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="educateur_key", referencedColumnName="id_user")
     * })
     */
    private $educateurKey;

    public function getDateCreneau(): ?\DateTime
    {
        return $this->dateCreneau;
    }

    public function setDateCreneau(?\DateTime $dateCreneau): self
    {
        $this->dateCreneau = $dateCreneau;

        return $this;
    }

    public function getTimeCreneau(): ?\DateTime
    {
        return $this->timeCreneau;
    }

    public function setTimeCreneau(?\DateTime $timeCreneau): self
    {
        $this->timeCreneau = $timeCreneau;

        return $this;
    }

    public function getIdCreneau(): ?int
    {
        return $this->idCreneau;
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


}
