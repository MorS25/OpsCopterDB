<?php

namespace OpsCopter\DB\ProjectBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class RegisterProjectProvidersCompilerPass implements CompilerPassInterface {

    protected $managerId;

    public function __construct($manager_id = 'copter_db_project.type_manager') {
        $this->managerId = $manager_id;
    }

    public function process(ContainerBuilder $container) {
        $provider_ids = $container->findTaggedServiceIds('copter_db_project.provider');

        if(!$container->has($this->managerId)) {
            return;
        }

        foreach($provider_ids as $id => $tag) {
            $container->getDefinition($this->managerId)
                ->addMethodCall('addProvider', array(new Reference($id)));
        }
    }
}
