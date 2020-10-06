<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * MessageRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MessageRepository extends EntityRepository
{
    public function getAmountOfMessages()
    {   
        return intval($this->createQueryBuilder('message')
        ->select('COUNT(message)')
        ->getQuery()->getSingleScalarResult());
    }
    public function getCount($count)
    { 
      $em = $this->getEntityManager();
      $sql   = "SELECT m FROM AppBundle:Message m ORDER BY m.date DESC";
      $query = $em->createQuery($sql);
      $query->setMaxResults($count);
      $iterableResult = $query->iterate();
      return $iterableResult;
    }
    public function paginaMessage($page = 1,$num = 5)
    {
 
        $firstresult = (int)$num * ((int)$page - 1 );
    
      $query = $this->createQueryBuilder('m')
      ->orderBy('m.date','DESC')
      ->setFirstResult($firstresult)
      ->setMaxResults($num)
      ->getQuery();
    return $query->getResult();
    }
}