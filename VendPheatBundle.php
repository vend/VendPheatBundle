<?php

namespace Vend\PheatBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Vend\PheatBundle\DependencyInjection\ProviderCompilerPass;
use Vend\PheatBundle\DependencyInjection\SessionBagCompilerPass;
use Vend\PheatBundle\DependencyInjection\VendPheatExtension;

class VendPheatBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new VendPheatExtension();
    }

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new SessionBagCompilerPass());
        $container->addCompilerPass(new ProviderCompilerPass());
    }
}
