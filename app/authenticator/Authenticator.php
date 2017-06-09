<?php
/**
 * User: Michal Formánek
 * Date: 5.2.17
 * Time: 9:55
 */

namespace App\Authenticator;

use App\Repository\UserRepository;
use Nette;
use Nette\Security\AuthenticationException;
use Nette\Security\IIdentity;
use Nette\Security\Passwords;


class Authenticator implements Nette\Security\IAuthenticator
{
    /** @var UserRepository */
    private $userRepository;

    public function __construct(UserRepository $repository)
    {
        $this->userRepository = $repository;
    }

    /**
     * Performs an authentication against e.g. database.
     * and returns IIdentity on success or throws AuthenticationException
     * @return IIdentity
     * @throws AuthenticationException
     */
    function authenticate(array $credentials)
    {
        list($username, $password) = $credentials;
        $user = $this->userRepository->findByUsername($username);
        if (!$user) {
            throw new Nette\Security\AuthenticationException('The username is incorrect.', self::IDENTITY_NOT_FOUND);
        } elseif (!Passwords::verify($password, $user->getPassword())) {
            throw new Nette\Security\AuthenticationException('The password is incorrect.', self::INVALID_CREDENTIAL);
        }
        return new Nette\Security\Identity($user->getId(), $user->getUsername(),$user); //TODO: Odstranit hash hesla, naplnit rozumne!!!
            //TODO: REHASH...
    }
}