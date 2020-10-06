<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="reply",options={"engine"="InnoDB","charset"="utf8"})
 */
class Reply
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer",nullable=false)
     * @ORM\GeneratedValue
     */
    protected $id;
    /**
     * @ORM\ManyToOne(targetEntity="Message", inversedBy="replys", cascade={"persist"})
     * @ORM\JoinColumn(name="message_id", referencedColumnName="id", nullable=false)
     */
    protected $message;
    /**
     * @ORM\Column(type="string",length=20)
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
     * @param mixed $message
     */

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
     * @return Reply
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
     * @return Reply
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
     * @return Reply
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
     * Set message
     *
     * @param \AppBundle\Entity\Message $message
     *
     * @return Reply
     */
    public function setMessage(\AppBundle\Entity\Message $message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return \AppBundle\Entity\Message
     */
    public function getMessage()
    {
        return $this->message;
    }
}
