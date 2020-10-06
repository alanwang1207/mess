<?php
// src/AppBundle/Entity/Message.php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MessageRepository")
 * @ORM\Table(name="message",options={"engine"="InnoDB","charset"="utf8"})
 */
class Message
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer",nullable=false)
     * @ORM\GeneratedValue
     */
    protected $id;
    /**
     * @ORM\Column(type="string",length=20,nullable=false)
     */
    protected $name;
    /**
     * @ORM\Column(type="string",length=1000,nullable=false)
     */
    protected $text;
    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    protected $date;

    /**
     * @ORM\OneToMany(targetEntity="Reply" , mappedBy="message",cascade={"persist", "remove"})
     */
    protected $replys;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->replys = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Message
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set text
     *
     * @param string $text
     *
     * @return Message
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Message
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Add reply
     *
     * @param \AppBundle\Entity\Reply $reply
     *
     * @return Message
     */
    public function addReply(\AppBundle\Entity\Reply $reply)
    {
        $reply->setMessage($this);
        $this->replys->add($reply);
        return $this;
    }

    /**
     * Remove reply
     *
     * @param \AppBundle\Entity\Reply $reply
     */
    public function removeReply(\AppBundle\Entity\Reply $reply)
    {
        $this->replys->removeElement($reply);
    }

    /**
     * Get replys
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReplys()
    {
        return $this->replys;
    }
    
}
