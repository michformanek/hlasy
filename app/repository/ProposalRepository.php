<?php
/**
 * Created by PhpStorm.
 * User: mformanek
 * Date: 5.2.17
 * Time: 20:51
 */

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class ProposalRepository extends EntityRepository
{

    public function delete($id)
    {
        $em = $this->getEntityManager();
        $em->remove($this->find($id));
        $em->flush();
    }

    public function saveOrUpdate($proposal)
    {
        $em = $this->getEntityManager();
        $em->persist($proposal);
        $em->flush();
    }

    public function findAllForUser($user)
    {
        return $this->createQueryBuilder('proposal')
            ->where('proposal.author = :author')
            ->setParameter('author', $user)
            ->getQuery()
            ->getResult();
    }

    public function findDeletedForUser($user)
    {
        return $this->createQueryBuilder('proposal')
            ->where('proposal.author = :author and proposal.trash = true')
            ->setParameter('author', $user)
            ->getQuery()
            ->getResult();
    }
}
