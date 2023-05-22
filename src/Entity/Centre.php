<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Centre
 *
 * @ORM\Table(name="centre", indexes={@ORM\Index(name="IDX_C6A0EA756186CA22", columns={"user_key"})})
 * @ORM\Entity
 */
class Centre
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_centre", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="centre_id_centre_seq", allocationSize=1, initialValue=1)
     */
    private $idCentre;

    /**
     * @var string|null
     *
     * @ORM\Column(name="capacites", type="text", nullable=true)
     */
    private $capacites;

    /**
     * @var \Users
     *
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_key", referencedColumnName="id_user")
     * })
     */
    private $userKey;

    public function getCapacites(): ?string
    {
        return $this->capacites;
    }

    public function setCapacites(?string $capacites): self
    {
        $this->capacites = $capacites;

        return $this;
    }

    public function getIdCentre(): ?int
    {
        return $this->idCentre;
    }

    public function getUserKey(): ?Users
    {
        return $this->userKey;
    }

    public function setUserKey(?Users $userKey): self
    {
        $this->userKey = $userKey;

        return $this;
    }


}
