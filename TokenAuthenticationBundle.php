<?php

namespace Youshido\TokenAuthenticationBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Youshido\TokenAuthenticationBundle\DependencyInjection\CompilerPass\ManagerCompilerPass;

class TokenAuthenticationBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ManagerCompilerPass());
    }
}
