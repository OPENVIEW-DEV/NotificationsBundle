<?php
namespace Openview\NotificationsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * Generic Notification of an event
 *
 * @ORM\Entity
 * @ORM\Table(name="notification")
 */
class Notification {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * Message to be sent
     * 
     * @ORM\Column(name="notificaton_message", type="text")
     */
    protected $message;
    /**
     * Severity (0-10) with 0=min and 10=max
     * 
     * @ORM\Column(type="integer")
     */
    protected $severity;
    /**
     * Message class.
     * Value should be one of: array('EMERG', 'ALERT', 'CRITICAL', 'ERROR', 'WARNING', 'NOTICE', 'INFO', 'OK', 'DEBUG')
     * 
     * @ORM\Column(name="notificaton_class", type="string", length=64, nullable=true)
     */
    protected $class;
    /**
     * Sender entity.
     * 
     * @ORM\Column(type="text", nullable=true)
     */
    protected $senderClass;
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $senderId;
    /**
     * Destination entity.
     * 
     * @ORM\Column(type="text", nullable=true)
     */
    protected $destinationClass;
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $destinationId;
    /**
     * Notification subject entity 8if appliable)
     * 
     * @ORM\Column(type="text", nullable=true)
     */
    protected $subjectClass;
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $subjectId;
    /**
     * Notification channel.
     * Must be one of array('msg', 'email', 'sms')
     * 
     * @ORM\Column(type="text", length=64)
     */
    protected $channel;
    /**
     * Creation DateTime
     * 
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;
    /**
     * @ORM\OneToMany(targetEntity="NotificationLink", mappedBy="notification")
     */
    protected $links;
    /**
     * @ORM\Column(type="boolean")
     */
    protected $isRead;
    /**
     * @ORM\Column(type="boolean")
     */
    protected $isArchived;
    /**
     * @ORM\Column(type="boolean")
     */
    protected $isDeleted;
    
    
    
    
    
    public function __construct() {
        $this->severity = 0;
        $this->channel = 'msg';
        $this->createdAt = new \DateTime();
        $this->links = new ArrayCollection();
        $this->isRead = false;
        $this->isArchived = false;
        $this->isDeleted = false;
    }
    
    
    
    
    /**
     * Returns the notification message generating the links
     * 
     * @return string
     */
    public function getDecodedMessage() {
        $sMessage = $this->message;
        
        // for each link in the notification
        foreach ($this->links as $link) {
            // if link key is found in message string
            if (strpos($sMessage, $link->getKey()) !== false) {
                // build link html code
                $sHtml = '<a href="' . $link->getUri() . '" title="' . $link->getTitle() . '">' . 
                        $link->getName() . '</a>';
                // replace key in message with the link code
                $sMessage = str_replace($link->getKey(), $sHtml, $sMessage);
            }
        }
        return $sMessage;
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
     * Set message
     *
     * @param string $message
     * @return Notification
     */
    public function setMessage($message)
    {
        $this->message = $message;
    
        return $this;
    }

    /**
     * Get message
     *
     * @return string 
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set severity
     *
     * @param integer $severity
     * @return Notification
     */
    public function setSeverity($severity)
    {
        $this->severity = $severity;
    
        return $this;
    }

    /**
     * Get severity
     *
     * @return integer 
     */
    public function getSeverity()
    {
        return $this->severity;
    }

    /**
     * Set class
     *
     * @param string $class
     * @return Notification
     */
    public function setClass($class)
    {
        $this->class = $class;
    
        return $this;
    }

    /**
     * Get class
     *
     * @return string 
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Set senderClass
     *
     * @param string $senderClass
     * @return Notification
     */
    public function setSenderClass($senderClass)
    {
        $this->senderClass = $senderClass;
    
        return $this;
    }

    /**
     * Get senderClass
     *
     * @return string 
     */
    public function getSenderClass()
    {
        return $this->senderClass;
    }

    /**
     * Set senderId
     *
     * @param integer $senderId
     * @return Notification
     */
    public function setSenderId($senderId)
    {
        $this->senderId = $senderId;
    
        return $this;
    }

    /**
     * Get senderId
     *
     * @return integer 
     */
    public function getSenderId()
    {
        return $this->senderId;
    }

    /**
     * Set destinationClass
     *
     * @param string $destinationClass
     * @return Notification
     */
    public function setDestinationClass($destinationClass)
    {
        $this->destinationClass = $destinationClass;
    
        return $this;
    }

    /**
     * Get destinationClass
     *
     * @return string 
     */
    public function getDestinationClass()
    {
        return $this->destinationClass;
    }

    /**
     * Set destinationId
     *
     * @param integer $destinationId
     * @return Notification
     */
    public function setDestinationId($destinationId)
    {
        $this->destinationId = $destinationId;
    
        return $this;
    }

    /**
     * Get destinationId
     *
     * @return integer 
     */
    public function getDestinationId()
    {
        return $this->destinationId;
    }

    /**
     * Set subjectClass
     *
     * @param string $subjectClass
     * @return Notification
     */
    public function setSubjectClass($subjectClass)
    {
        $this->subjectClass = $subjectClass;
    
        return $this;
    }

    /**
     * Get subjectClass
     *
     * @return string 
     */
    public function getSubjectClass()
    {
        return $this->subjectClass;
    }

    /**
     * Set subjectId
     *
     * @param integer $subjectId
     * @return Notification
     */
    public function setSubjectId($subjectId)
    {
        $this->subjectId = $subjectId;
    
        return $this;
    }

    /**
     * Get subjectId
     *
     * @return integer 
     */
    public function getSubjectId()
    {
        return $this->subjectId;
    }

    /**
     * Set channel
     *
     * @param string $channel
     * @return Notification
     */
    public function setChannel($channel)
    {
        $this->channel = $channel;
    
        return $this;
    }

    /**
     * Get channel
     *
     * @return string 
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Notification
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Add links
     *
     * @param \Openview\NotificationsBundle\Entity\NotificationLink $links
     * @return Notification
     */
    public function addLink(\Openview\NotificationsBundle\Entity\NotificationLink $links)
    {
        $this->links[] = $links;
    
        return $this;
    }

    /**
     * Remove links
     *
     * @param \Openview\NotificationsBundle\Entity\NotificationLink $links
     */
    public function removeLink(\Openview\NotificationsBundle\Entity\NotificationLink $links)
    {
        $this->links->removeElement($links);
    }

    /**
     * Get links
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * Set isRead
     *
     * @param boolean $isRead
     * @return Notification
     */
    public function setIsRead($isRead)
    {
        $this->isRead = $isRead;
    
        return $this;
    }

    /**
     * Get isRead
     *
     * @return boolean 
     */
    public function getIsRead()
    {
        return $this->isRead;
    }
    

    /**
     * Set isArchived
     *
     * @param boolean $isArchived
     * @return Notification
     */
    public function setIsArchived($isArchived)
    {
        $this->isArchived = $isArchived;
    
        return $this;
    }

    /**
     * Get isArchived
     *
     * @return boolean 
     */
    public function getIsArchived()
    {
        return $this->isArchived;
    }

    /**
     * Set isDeleted
     *
     * @param boolean $isDeleted
     * @return Notification
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
    
    
    
    
    
}