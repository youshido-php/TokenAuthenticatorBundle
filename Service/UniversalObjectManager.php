<?php

namespace Youshido\TokenAuthenticationBundle\Service;


use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Youshido\TokenAuthenticationBundle\Document\AccessToken as AccessTokenDocument;
use Youshido\TokenAuthenticationBundle\Entity\AccessToken as AccessTokenEntity;


class UniversalObjectManager
{
    const MODE_ODM = "odm";
    const MODE_ORM = "orm";

    /** @var ContainerInterface */
    private $container;

    /** @var string */
    private $mode;

    /**
     * @var ObjectManager
     */
    private $manager;

    public function __construct(ContainerInterface $container, $mode)
    {
        $this->container = $container;
        $this->mode      = $mode;
        $this->manager   = $this->getObjectManager();
    }

    /**
     * @return ObjectManager
     */
    public function getObjectManager()
    {
        return $this->mode == self::MODE_ODM ? $this->container->get('doctrine.odm.mongodb.document_manager') : $this->container->get('doctrine.orm.entity_manager');
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
        return $this->mode === self::MODE_ORM ? new AccessTokenEntity() : new AccessTokenDocument();
    }

}