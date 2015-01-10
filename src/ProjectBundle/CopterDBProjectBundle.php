<?php

namespace OpsCopter\DB\ProjectBundle;

use OpsCopter\DB\ProjectBundle\DependencyInjection\Compiler\RegisterProjectProvidersCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class CopterDBProjectBundle extends Bundle
{

    public function build(ContainerBuilder $container) {
        $container->addCompilerPass(new RegisterProjectProvidersCompilerPass());
    }

}
