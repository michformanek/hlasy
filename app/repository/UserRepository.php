<?php

namespace App\Repository;

use Nette,
    Kdyby,
    App\Model,
    Nette\Security\Passwords;
use Doctrine\ORM\EntityRepository;

/**
 * Created by PhpStorm.
 * User: mformanek
 * Date: 5.2.17
 * Time: 18:54
 */
class UserRepository extends EntityRepository
{

    /**
     * Finds User by given username.
     */
    public function findByUsername(string $username) //TODO PHP 7.1 nullable return
    {
        return $this->findOneBy(array('username' => $username));
    }

    public function addUser(){
        $user = new Model\User();
        $user->setEmail("michformanek@gmail.com");
        $user->setPassword(Passwords::hash("this is joke"));
        $user->setUsername("michal");
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }
}