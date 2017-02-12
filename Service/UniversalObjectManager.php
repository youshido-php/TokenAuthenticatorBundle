<?php

namespace Youshido\TokenAuthenticationBundle\Service;


use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Youshido\TokenAuthenticationBundle\Document\AccessToken as AccessTokenDocument;
use Youshido\TokenAuthenticationBundle\Entity\AccessToken as AccessTokenEntity;


class UniversalObjectManager
{
    const PLATFORM_ODM = "odm";
    const PLATFORM_ORM = "orm";

    /** @var ContainerInterface */
    private $container;

    /** @var string */
    private $platform;

    /**
     * @var ObjectManager
     */
    private $manager;

    public function __construct(ContainerInterface $container, $platform)
    {
        $this->container = $container;
        $this->platform  = $platform;
        $this->manager   = $this->getObjectManager();
    }

    /**
     * @return ObjectManager
     */
    public function getObjectManager()
    {
        return $this->platform == self::PLATFORM_ODM ? $this->container->get('doctrine.odm.mongodb.document_manager') : $this->container->get('doctrine.orm.entity_manager');
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