<?php
namespace Openview\NotificationsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Link in a notification
 *
 * @ORM\Entity
 * @ORM\Table(name="notification_link")
 */
class NotificationLink {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * Link identifier
     * @ORM\Column(name="link_key", type="string", length=128)
     */
    protected $key;
    /**
     * Link visible name
     * @ORM\Column(name="link_name", type="text")
     */
    protected $name;
    /**
     * Link URI
     * @ORM\Column(name="link_uri", type="text")
     */
    protected $uri;
    /**
     * Link title option
     * @ORM\Column(name="link_title", type="text")
     */
    protected $title;
    /**
     * @ORM\ManyToOne(targetEntity="Notification", inversedBy="links")
     * @ORM\JoinColumn(name="notification_id", referencedColumnName="id")
     */
    protected $notification;

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
     * Set key
     *
     * @param string $key
     * @return NotificationLink
     */
    public function setKey($key)
    {
        $this->key = $key;
    
        return $this;
    }

    /**
     * Get key
     *
     * @return string 
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return NotificationLink
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
     * Set uri
     *
     * @param string $uri
     * @return NotificationLink
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
    
        return $this;
    }

    /**
     * Get uri
     *
     * @return string 
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return NotificationLink
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set notification
     *
     * @param \Openview\NotificationsBundle\Entity\Notification $notification
     * @return NotificationLink
     */
    public function setNotification(\Openview\NotificationsBundle\Entity\Notification $notification = null)
    {
        $this->notification = $notification;
    
        return $this;
    }

    /**
     * Get notification
     *
     * @return \Openview\NotificationsBundle\Entity\Notification 
     */
    public function getNotification()
    {
        return $this->notification;
    }
}