<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Messages
 *
 * @ORM\Table(name="messages", indexes={@ORM\Index(name="IDX_DB021E9655AB140", columns={"auteur"}), @ORM\Index(name="IDX_DB021E96FEA9FF92", columns={"destinataire"})})
 * @ORM\Entity
 */
class Messages
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_message", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="messages_id_message_seq", allocationSize=1, initialValue=1)
     */
    private $idMessage;

    /**
     * @var string|null
     *
     * @ORM\Column(name="data_msg", type="text", nullable=true)
     */
    private $dataMsg;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_msg", type="date", nullable=true)
     */
    private $dateMsg;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="time_msg", type="time", nullable=true)
     */
    private $timeMsg;

    /**
     * @var Users
     *
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="auteur", referencedColumnName="id_user")
     * })
     */
    private $auteur;

    /**
     * @var Users
     *
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="destinataire", referencedColumnName="id_user")
     * })
     */
    private $destinataire;

    public function getDataMsg(): ?string
    {
        return $this->dataMsg;
    }

    public function setDataMsg(?string $dataMsg): self
    {
        $this->dataMsg = $dataMsg;

        return $this;
    }

    public function getDateMsg(): ?\DateTimeInterface
    {
        return $this->dateMsg;
    }

    public function setDateMsg(?\DateTimeInterface $dateMsg): self
    {
        $this->dateMsg = $dateMsg;

        return $this;
    }

    public function getTimeMsg(): ?\DateTimeInterface
    {
        return $this->timeMsg;
    }

    public function setTimeMsg(?\DateTimeInterface $timeMsg): self
    {
        $this->timeMsg = $timeMsg;

        return $this;
    }

    public function getIdMessage(): ?int
    {
        return $this->idMessage;
    }

    public function getAuteur(): ?Users
    {
        return $this->auteur;
    }

    public function setAuteur(?Users $auteur): self
    {
        $this->auteur = $auteur;

        return $this;
    }

    public function getDestinataire(): ?Users
    {
        return $this->destinataire;
    }

    public function setDestinataire(?Users $destinataire): self
    {
        $this->destinataire = $destinataire;

        return $this;
    }


}
