<?php

namespace Vend\PheatBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Vend\PheatBundle\DependencyInjection\VendPheatExtension;

class VendPheatBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new VendPheatExtension();
    }
}
