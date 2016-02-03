<?php
/**
 * Date: 22.12.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\TokenAuthenticationBundle\Service;

use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Youshido\TokenAuthenticationBundle\Entity\AccessToken;

class TokenUserProvider implements UserProviderInterface
{

    use ContainerAwareTrait;

    /** @var  string */
    protected $userClass;

    /** @var  string */
    protected $loginField;

    /**
     * @param $apiKey
     * @return null|\Youshido\TokenAuthenticationBundle\Entity\AccessToken
     */
    public function findTokenByApiKey($apiKey)
    {
        return $this->container->get('access_token_helper')->find($apiKey);
    }

    /**
     * @param AccessToken $token
     *
     * @return object
     */
    public function loadUserByToken(AccessToken $token)
    {
        return $this->container->get('doctrine')
            ->getManager()
            ->getRepository($this->getUserClass())
            ->find($token->getModelId());
    }

    /**
     * @inheritdoc
     */
    public function loadUserByUsername($username)
    {
        return $this->container->get('doctrine')
            ->getManager()
            ->getRepository($this->userClass)
            ->findOneBy([$this->loginField => $username]);
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
        return $this->userClass === $class;
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
