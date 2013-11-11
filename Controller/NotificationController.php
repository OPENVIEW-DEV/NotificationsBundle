<?php
namespace Openview\NotificationsBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;


/**
 * Controller to manage notifications
 * 
 */
class NotificationController extends Controller
{
    
    /**
     * List notifications for the currently logged user
     * 
     */
    public function listAction($userid) {
        $notifications = array();
        $user = $this->get('security.context')->getToken()->getUser();  
        
        // se vedo il mio utente o se sono admin
        if (($user->getId() == $userid) || ($this->get('security.context')->isGranted('ROLE_ADMIN'))) {
            $notificationsRepo = $this->get('doctrine')
                        ->getRepository('OpenviewNotificationsBundle:Notification');
            // se sono admin e vedo le mie notifiche...
            if (($user->getId() == $userid) && ($this->get('security.context')->isGranted('ROLE_ADMIN'))) {
                $queryBuilder = $notificationsRepo->createQueryBuilder('n')
                    ->where("(n.destinationClass like :classname) and 
                        (n.isDeleted=0) and (n.isArchived=0)")
                    ->setParameter('classname', '%Entity%User')
                    ->orderBy('n.createdAt', 'DESC');
            } else {
                $queryBuilder = $notificationsRepo->createQueryBuilder('n')
                    ->where("(n.destinationClass like :classname) and 
                        (n.destinationId = :myId) and
                        (n.isDeleted=0) and (n.isArchived=0)")
                    ->setParameter('myId', $userid)
                    ->setParameter('classname', '%Entity%User')
                    ->orderBy('n.createdAt', 'DESC');
            }
            $query = $queryBuilder->getQuery();
            $notifications = $query->getResult();
        } else {
            // avviso della mancanza dei permessi
            $this->get('session')->getFlashBag()->add(
                'notice', $this->get('translator')->trans(
                        'messages.norights.otherusers',
                        array(),
                        'notifications'
                )
            );
        }
        
        return $this->render('OpenviewNotificationsBundle:Notification:list.html.twig', array( 
            'notifications'=>$notifications
        ));
    }
    
    
    
    /**
     * Draw the icon for unread notifications in the navigation bar
     * 
     * @return \Symfony\Component\HttpFoundation\Response Html code that builds the icons
     */
    public function navbarIconAction() {
        $user = $this->get('security.context')->getToken()->getUser();
        
        if ($this->get('security.context')->isGranted('ROLE_USER')) {
            $notificationsRepo = $this->get('doctrine')
                        ->getRepository('OpenviewNotificationsBundle:Notification');
            $query = $notificationsRepo->createQueryBuilder('n')
                            ->where('(n.destinationClass like :classname) and 
                                (n.destinationId = :myId) and
                                (n.isDeleted=0) and (n.isArchived=0) and (n.isRead=0)')
                            ->setParameter('myId', $user->getId())
                            ->setParameter('classname', '%Entity%User')
                            ->getQuery();
            $notifications = $query->getResult();
            if (count($notifications) > 0) {
                return $this->render('OpenviewNotificationsBundle:Notification:_navbar_element.html.twig', array(
                                'notificationsCount'=>count($notifications),
                            ));
            }
        }
        
        return new Response('');
    }
    
    
    
    /**
     * Sets a Notification as read
     * 
     * @param int $id Notification id
     */
    public function setReadAction($id) {
        $this->setProperty($id, 'read', true);
        // redirect to notifications list
        return $this->redirect($this->generateUrl('openview_notifications_list'));
    }
    
    
    
    /**
     * Sets a Notification as deleted
     * 
     * @param int $id Notification id
     */
    public function setDeletedAction($id) {
        $this->setProperty($id, 'deleted', true);
        // redirect to notifications list
        return $this->redirect($this->generateUrl('openview_notifications_list'));
    }
    
    
    /**
     * Sets a Notification as archived
     * 
     * @param int $id Notification id
     */
    public function setArchivedAction($id) {
        $this->setProperty($id, 'archived', true);
        // redirect to notifications list
        return $this->redirect($this->generateUrl('openview_notifications_list'));
    }
    
    
    
    /**
     * Sets a notification property value, checking for its ownership
     * 
     */
    protected function setProperty($id, $propname, $value) {
        $notificationsRepo = $this->get('doctrine')
                    ->getRepository('OpenviewNotificationsBundle:Notification');
        $notification = $notificationsRepo->findOneById($id);
        if ($notification) {
            $user = $this->get('security.context')->getToken()->getUser();
            // the notification must be addressed to me
            if ((strpos($notification->getDestinationClass(), get_class($user)) !== false) && 
                ($notification->getDestinationId() == $user->getId())) {
                // sets specified property value
                switch (strtolower($propname)) {
                    case 'read':
                        $notification->setIsRead($value);
                        break;
                    case 'deleted':
                        $notification->setIsDeleted($value);
                        break;
                    case 'archived':
                        $notification->setIsArchived($value);
                        break;
                }
                $em = $this->get('doctrine')->getManager();
                $em->persist($notification);
                $em->flush();
            }
        }
    }
    
    
    
    /**
     * Show the latest notifications widget
     * 
     */
    public function latestWidgetAction($userid) {
        $user = $this->get('security.context')->getToken()->getUser();
        
        $notificationsRepo = $this->get('doctrine')
                    ->getRepository('OpenviewNotificationsBundle:Notification');
        if (($user->getId() == $userid) || ($this->get('security.context')->isGranted('ROLE_ADMIN'))) {
            $query = $notificationsRepo->createQueryBuilder('n')
                            ->where('(n.destinationClass like :classname) and 
                                (n.destinationId = :myId) and
                                (n.isDeleted=0) and (n.isArchived=0)')
                            ->setParameter('myId', $userid)
                            ->setParameter('classname', '%Entity%User')
                            ->orderBy('n.createdAt', 'DESC')
                            ->setMaxResults(3)
                            ->getQuery();
            $notifications = $query->getResult();
        } else {
            $notifications = null;
        }
        
        return $this->render('OpenviewNotificationsBundle:Notification:_latest_widget.html.twig', array(
                'notifications'=>$notifications,
                'userid'=>$userid,
            ));
    }
    
    
}

