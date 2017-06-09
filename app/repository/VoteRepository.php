<?php


namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class VoteRepository extends EntityRepository
{

    public function findSoonEnding()
    {
        $dateEnding = new \DateTime('now');
        $dateEnding->modify('- 7 days');

        return $this->createQueryBuilder('vote')
            ->where('vote.proposal.dateEnd = :date')
            ->setParameter('date', $dateEnding)
            ->getQuery()
            ->getResult();
    }
}