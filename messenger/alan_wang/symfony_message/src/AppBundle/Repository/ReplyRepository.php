<?php

namespace AppBundle\Entity;

/**
 * MessageRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ReplyRepository extends \Doctrine\ORM\EntityRepository
{
    public function findOneByIdJoinedToMessage($id)
    {
        $query = $this->getEntityManager()
            ->createQuery(
                'SELECT r, m FROM AppBundle:Reply r
        JOIN r.message m
        WHERE r.message_id = :id'
            )->setParameter('id', $id);

        return $query->getOneOrNullResult();
    }
}