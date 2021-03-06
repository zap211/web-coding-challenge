<?php

namespace CTRV\FlowBundle\Entity;

use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\ManyToOne;

use Doctrine\ORM\Mapping as ORM;

/**
 * CTRV\CommonBundle\Entity\PublicMessage
 *
 * @ORM\Table(name="public_message")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="CTRV\FlowBundle\Entity\PublicMessageRepository")
 */
class PublicMessage
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string $date
     *
     * @ORM\Column(name="date", type="datetime", nullable=false)
     */
    private $date;

    /**
     * @var string $content
     *
     * @ORM\Column(name="content", type="text", nullable=false)
     */
    private $content;

    /**
     * @var boolean $isDeleted
     *
     * @ORM\Column(name="is_deleted", type="boolean", nullable=true)
     */
    private $isDeleted;
    
    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="\CTRV\CommonBundle\Entity\User",inversedBy="sentPublicMessages")
     * @JoinColumn(name="sender_userid",referencedColumnName="userid", onDelete="CASCADE")
     */
    private $sender;

    /**
     * @var City
     * @ORM\ManyToOne(targetEntity="\CTRV\CommonBundle\Entity\City",inversedBy="publicMessages")
     * @JoinColumn(name="city_id",referencedColumnName="id", onDelete="CASCADE")
     */
    private $city;
    
    /**
     * @ManyToOne(targetEntity="PublicMessage")
     * @JoinColumn(name="answered_id", referencedColumnName="id",onDelete="CASCADE")
     */
   	private $answeredId;
   	
   	/**
   	 * @OneToMany(targetEntity="PublicMessage", mappedBy="parent")
   	 */
   	private $childrenMessages;
   	
   	/**
   	 * @ManyToOne(targetEntity="PublicMessage", inversedBy="children")
   	 * @JoinColumn(name="parent_id", referencedColumnName="id",onDelete="CASCADE")
   	 */
   	private $parentMessage;


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
     * Set date
     *
     * @param string $date
     * @return PublicMessage
     */
    public function setDate($date)
    {
        $this->date = $date;
    
        return $this;
    }

    /**
     * Get date
     *
     * @return string 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return PublicMessage
     */
    public function setContent($content)
    {
        $this->content = $content;
    
        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set isDeleted
     *
     * @param boolean $isDeleted
     * @return PublicMessage
     */
    public function setIsDeleted($isDeleted)
    {
        $this->isDeleted = $isDeleted;
    
        return $this;
    }

    /**
     * Get isDeleted
     *
     * @return boolean 
     */
    public function getIsDeleted()
    {
        return $this->isDeleted;
    }

    /**
     * Set sender
     *
     * @param CTRV\CommonBundle\Entity\User $sender
     * @return PublicMessage
     */
    public function setSender(\CTRV\CommonBundle\Entity\User $sender = null)
    {
        $this->sender = $sender;
    
        return $this;
    }

    /**
     * Get sender
     *
     * @return CTRV\CommonBundle\Entity\User 
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * Set city
     *
     * @param CTRV\CommonBundle\Entity\City $city
     * @return PublicMessage
     */
    public function setCity(\CTRV\CommonBundle\Entity\City $city = null)
    {
        $this->city = $city;
    
        return $this;
    }

    /**
     * Get city
     *
     * @return CTRV\CommonBundle\Entity\City 
     */
    public function getCity()
    {
        return $this->city;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->childrenMessages = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Set answeredId
     *
     * @param CTRV\FlowBundle\Entity\PublicMessage $answeredId
     * @return PublicMessage
     */
    public function setAnsweredId(\CTRV\FlowBundle\Entity\PublicMessage $answeredId = null)
    {
        $this->answeredId = $answeredId;
    
        return $this;
    }

    /**
     * Get answeredId
     *
     * @return CTRV\FlowBundle\Entity\PublicMessage 
     */
    public function getAnsweredId()
    {
        return $this->answeredId;
    }

    /**
     * Add childrenMessages
     *
     * @param CTRV\FlowBundle\Entity\PublicMessage $childrenMessages
     * @return PublicMessage
     */
    public function addChildrenMessage(\CTRV\FlowBundle\Entity\PublicMessage $childrenMessages)
    {
        $this->childrenMessages[] = $childrenMessages;
    
        return $this;
    }

    /**
     * Remove childrenMessages
     *
     * @param CTRV\FlowBundle\Entity\PublicMessage $childrenMessages
     */
    public function removeChildrenMessage(\CTRV\FlowBundle\Entity\PublicMessage $childrenMessages)
    {
        $this->childrenMessages->removeElement($childrenMessages);
    }

    /**
     * Get childrenMessages
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getChildrenMessages()
    {
        return $this->childrenMessages;
    }

    /**
     * Set parentMessage
     *
     * @param CTRV\FlowBundle\Entity\PublicMessage $parentMessage
     * @return PublicMessage
     */
    public function setParentMessage(\CTRV\FlowBundle\Entity\PublicMessage $parentMessage = null)
    {
        $this->parentMessage = $parentMessage;
    
        return $this;
    }

    /**
     * Get parentMessage
     *
     * @return CTRV\FlowBundle\Entity\PublicMessage 
     */
    public function getParentMessage()
    {
        return $this->parentMessage;
    }
}