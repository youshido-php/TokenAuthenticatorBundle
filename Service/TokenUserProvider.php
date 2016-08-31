<?php
/**
 * Date: 22.12.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\TokenAuthenticationBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Youshido\TokenAuthenticationBundle\Entity\AccessToken;
use Youshido\TokenAuthenticationBundle\Service\Helper\AccessTokenHelper;

class TokenUserProvider implements UserProviderInterface
{

    /** @var  string */
    protected $userClass;

    /** @var  string */
    protected $loginField;

    /** @var  AccessTokenHelper */
    private $tokenHelper;

    /** @var  EntityManager */
    private $em;

    public function __construct(EntityManager $em, AccessTokenHelper $tokenHelper, $userClass, $loginField)
    {
        $this->em          = $em;
        $this->tokenHelper = $tokenHelper;
        $this->userClass   = $userClass;
        $this->loginField  = $loginField;
    }

    /**
     * @param $apiKey
     *
     * @return null|\Youshido\TokenAuthenticationBundle\Entity\AccessToken
     */
    public function findTokenByApiKey($apiKey)
    {
        return $this->tokenHelper->find($apiKey);
    }

    /**
     * @param AccessToken $token
     *
     * @return object
     */
    public function loadUserByToken(AccessToken $token)
    {
        return $this->em->getRepository($this->getUserClass())->find($token->getModelId());
    }

    /**
     * @inheritdoc
     */
    public function loadUserByUsername($username)
    {
        return $this->em->getRepository($this->getUserClass())->findOneBy([$this->getLoginField() => $username]);
    }

    /**
     * @inheritdoc
     */
    public function refreshUser(UserInterface $user)
    {
        throw new UnsupportedUserException();
    }

    /**
     * @inheritdoc
     */
    public function supportsClass($class)
    {
        return $this->getUserClass() === $class;
    }

    /**
     * @return string
     */
    public function getUserClass()
    {
        return $this->userClass;
    }

    /**
     * @param string $userClass
     */
    public function setUserClass($userClass)
    {
        $this->userClass = $userClass;
    }

    /**
     * @return string
     */
    public function getLoginField()
    {
        return $this->loginField;
    }

    /**
     * @param string $field
     */
    public function setLoginField($field)
    {
        $this->loginField = $field;
    }
}
