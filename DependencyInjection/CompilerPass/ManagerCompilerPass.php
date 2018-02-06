<?php

namespace Youshido\TokenAuthenticationBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Youshido\TokenAuthenticationBundle\Service\UniversalObjectManager;

/**
 * Class ManagerCompilerPass
 */
class ManagerCompilerPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     */
    public function process(ContainerBuilder $container)
    {
        $platform = $container->getParameter('token_authentication.platform');

        if ($platform === UniversalObjectManager::PLATFORM_ODM) {
            $definition = $container->findDefinition('doctrine.odm.mongodb.document_manager');
        } else {
            $definition = $container->findDefinition('doctrine.orm.entity_manager');
        }

        $container->setDefinition('token_authentication.manager', $definition);
    }
}
