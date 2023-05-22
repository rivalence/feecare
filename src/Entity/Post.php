<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Post
 *
 * @ORM\Table(name="post", indexes={@ORM\Index(name="IDX_5A8A6C8D55AB140", columns={"auteur"})})
 * @ORM\Entity
 */
class Post
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_post", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="post_id_post_seq", allocationSize=1, initialValue=1)
     */
    private $idPost;

    /**
     * @var string|null
     *
     * @ORM\Column(name="legend", type="text", nullable=true)
     */
    private $legend;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_post", type="date", nullable=true)
     */
    private $datePost;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="time_post", type="time", nullable=true)
     */
    private $timePost;

    /**
     * @var string|null
     *
     * @ORM\Column(name="data_post", type="string", length=100, nullable=true)
     */
    private $dataPost;

    /**
     * @var Users
     *
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="auteur", referencedColumnName="id_user")
     * })
     */
    private $auteur;

    public function getLegend(): ?string
    {
        return $this->legend;
    }

    public function setLegend(?string $legend): self
    {
        $this->legend = $legend;

        return $this;
    }

    public function getDatePost(): ?\DateTimeInterface
    {
        return $this->datePost;
    }

    public function setDatePost(?\DateTimeInterface $datePost): self
    {
        $this->datePost = $datePost;

        return $this;
    }

    public function getTimePost(): ?\DateTimeInterface
    {
        return $this->timePost;
    }

    public function setTimePost(?\DateTimeInterface $timePost): self
    {
        $this->timePost = $timePost;

        return $this;
    }

    public function getDataPost(): ?string
    {
        return $this->dataPost;
    }

    public function setDataPost(?string $dataPost): self
    {
        $this->dataPost = $dataPost;

        return $this;
    }

    public function getIdPost(): ?int
    {
        return $this->idPost;
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


}
