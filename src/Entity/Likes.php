<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Likes
 *
 * @ORM\Table(name="likes", indexes={@ORM\Index(name="IDX_49CA4E7DF14AFF1E", columns={"give_like"}), @ORM\Index(name="IDX_49CA4E7DFA0A66F9", columns={"getliked"})})
 * @ORM\Entity
 */
class Likes
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_like", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="likes_id_like_seq", allocationSize=1, initialValue=1)
     */
    private $idLike;

    /**
     * @var Users
     *
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="give_like", referencedColumnName="id_user")
     * })
     */
    private $giveLike;

    /**
     * @var Post
     *
     * @ORM\ManyToOne(targetEntity="Post")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="getliked", referencedColumnName="id_post")
     * })
     */
    private $getliked;

    public function getIdLike(): ?int
    {
        return $this->idLike;
    }

    public function getGiveLike(): ?Users
    {
        return $this->giveLike;
    }

    public function setGiveLike(?Users $giveLike): self
    {
        $this->giveLike = $giveLike;

        return $this;
    }

    public function getGetliked(): ?Post
    {
        return $this->getliked;
    }

    public function setGetliked(?Post $getliked): self
    {
        $this->getliked = $getliked;

        return $this;
    }
}
