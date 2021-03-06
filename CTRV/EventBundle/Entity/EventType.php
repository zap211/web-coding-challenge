<?php

namespace CTRV\EventBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CTRV\CommonBundle\Entity\EventType
 *
 * @ORM\Table(name="event_type")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="CTRV\EventBundle\Entity\EventTypeRepository")
 */
class EventType
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
     * @var string $label
     *
     * @ORM\Column(name="label", type="string", length=256, nullable=false)
     */
    private $label;
    
    /**
     * @var string $code
     *
     * @ORM\Column(name="code", type="string", length=256, nullable=true)
     */
    private $code;
    
    /**
     * @var string $language
     *
     * @ORM\Column(name="language", type="string", length=256, nullable=false)
     */
    private $language;
    
    /**
     *
     * @ORM\Column(name="img_url", type="string", length=256,nullable=true)
     */
    private $img_url;


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
     * Set label
     *
     * @param string $label
     * @return EventType
     */
    public function setLabel($label)
    {
        $this->label = $label;
    
        return $this;
    }

    /**
     * Get label
     *
     * @return string 
     */
    public function getLabel()
    {
        return $this->label;
    }
    public function __toString() {
    	return $this->label;
    }
    

    /**
     * Set code
     *
     * @param string $code
     * @return EventType
     */
    public function setCode($code)
    {
        $this->code = $code;
    
        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set language
     *
     * @param string $language
     * @return EventType
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    
        return $this;
    }

    /**
     * Get language
     *
     * @return string 
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set img_url
     *
     * @param string $imgUrl
     * @return EventType
     */
    public function setImgUrl($imgUrl)
    {
        $this->img_url = $imgUrl;
    
        return $this;
    }

    /**
     * Get img_url
     *
     * @return string 
     */
    public function getImgUrl()
    {
        return $this->img_url;
    }
}