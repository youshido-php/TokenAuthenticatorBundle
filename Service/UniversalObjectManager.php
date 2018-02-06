<?php

namespace Youshido\TokenAuthenticationBundle\Service;

use Doctrine\Common\Persistence\ObjectManager;
use Youshido\TokenAuthenticationBundle\Document\AccessToken as AccessTokenDocument;
use Youshido\TokenAuthenticationBundle\Entity\AccessToken as AccessTokenEntity;

/**
 * Class UniversalObjectManager
 */
class UniversalObjectManager
{
    const PLATFORM_ODM = 'odm';
    const PLATFORM_ORM = 'orm';

    /** @var string */
    private $platform;

    /**
     * @var ObjectManager
     */
    private $manager;

    public function __construct($manager, $platform)
    {
        $this->platform = $platform;
        $this->manager  = $manager;
    }

    /**
     * @return ObjectManager
     */
    public function getObjectManager()
    {
        return $this->manager;
    }

    public function getRepository($repositoryName)
    {
        return $this->getObjectManager()->getRepository($repositoryName);
    }

    public function getTokenRepository()
    {
        return $this->getObjectManager()->getRepository('TokenAuthenticationBundle:AccessToken');
    }

    public function remove($object)
    {
        $this->manager->remove($object);
    }

    public function persist($object)
    {
        $this->manager->persist($object);
    }

    public function flush()
    {
        $this->manager->flush();
    }

    public function createNewTokenInstance()
    {
        return $this->platform === self::PLATFORM_ORM ? new AccessTokenEntity() : new AccessTokenDocument();
    }

}
