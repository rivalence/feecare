<?php
/*<link rel="stylesheet" href="styles/styles.css"/>
    	<script src="{{ asset('assets/scripts/map.js')}}"></script>

*/
namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Besoin
 *
 * @ORM\Table(name="besoin", indexes={@ORM\Index(name="IDX_8118E811E36F6CE7", columns={"centre_key"})})
 * @ORM\Entity
 */
class Besoin
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_besoin", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="besoin_id_besoin_seq", allocationSize=1, initialValue=1)
     */
    private $idBesoin;

    /**
     * @var string|null
     *
     * @ORM\Column(name="intitule", type="text", nullable=true)
     */
    private $intitule;

    /**
     * @var \Centre
     *
     * @ORM\ManyToOne(targetEntity="Centre")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="centre_key", referencedColumnName="id_centre")
     * })
     */
    private $centreKey;

    public function getIntitule(): ?string
    {
        return $this->intitule;
    }

    public function setIntitule(?string $intitule): self
    {
        $this->intitule = $intitule;

        return $this;
    }

    public function getIdBesoin(): ?int
    {
        return $this->idBesoin;
    }

    public function getCentreKey(): ?Centre
    {
        return $this->centreKey;
    }

    public function setCentreKey(?Centre $centreKey): self
    {
        $this->centreKey = $centreKey;

        return $this;
    }


}
