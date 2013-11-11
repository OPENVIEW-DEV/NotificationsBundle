<?php
namespace Openview\NotificationsBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Openview\NotificationsBundle\Entity\Notification;



class LoadNotificationsData implements FixtureInterface {
    
    
    public function load(ObjectManager $manager) {
        $n = new Notification();
        $n->setMessage('Messaggio di prova');
        $n->setChannel('sms');
        $n->setClass('WARNING');
        $n->setSeverity(7);
        $n->setSenderClass(get_class($this));
        $n->setSenderId(10);
        
        $manager->persist($n);
        $manager->flush();
    }
    
    
    
}
