<?php

namespace AppBundle\Entity;

/**
 * NotificationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class NotificationRepository extends \Doctrine\ORM\EntityRepository
{
	public function createNotification($params)
	{
		if(!isset($params) || count($params) < 1) {
			return false;
		}
		
		$em = $this->getEntityManager();

		$notification = new Notification();
		$notification->setAuthorId($params['author_id']);
		$notification->setNotificationText($params['notification']);
		$notification->setCreatedOn(new \DateTime());

		$em->persist($notification);
		$em->flush();

		return true;
	}
}
