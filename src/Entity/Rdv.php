<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Rdv
 *
 * @ORM\Table(name="rdv", indexes={@ORM\Index(name="IDX_10C31F86B062378", columns={"famille_key"}), @ORM\Index(name="IDX_10C31F866971227", columns={"educateur_key"})})
 * @ORM\Entity
 */
class Rdv
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_rdv", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="rdv_id_rdv_seq", allocationSize=1, initialValue=1)
     */
    private $idRdv;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_rdv", type="date", nullable=true)
     */
    private $dateRdv;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="time_rdv", type="time", nullable=true)
     */
    private $timeRdv;

    /**
     * @var string|null
     *
     * @ORM\Column(name="statut", type="string", length=15, nullable=true)
     */
    private $statut;

    /**
     * @var \Users
     *
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="famille_key", referencedColumnName="id_user")
     * })
     */
    private $familleKey;

    /**
     * @var \Users
     *
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="educateur_key", referencedColumnName="id_user")
     * })
     */
    private $educateurKey;

    public function getDateRdv(): ?\DateTimeInterface
    {
        return $this->dateRdv;
    }

    public function setDateRdv(?\DateTimeInterface $dateRdv): self
    {
        $this->dateRdv = $dateRdv;

        return $this;
    }

    public function getTimeRdv(): ?\DateTimeInterface
    {
        return $this->timeRdv;
    }

    public function setTimeRdv(?\DateTimeInterface $timeRdv): self
    {
        $this->timeRdv = $timeRdv;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(?string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getIdRdv(): ?int
    {
        return $this->idRdv;
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
