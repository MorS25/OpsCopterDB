<?php

namespace OpsCopter\DB\ProjectBundle\Tests\DependencyInjection\Compiler;

use OpsCopter\DB\ProjectBundle\DependencyInjection\Compiler\RegisterProjectProvidersCompilerPass;
use Symfony\Component\DependencyInjection\Reference;

class RegisterProjectProvidersCompilerPassTest extends \PHPUnit_Framework_TestCase {

    public function testRegistrationWithNoManager() {
        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder', array('has', 'getDefinition'));
        $container->expects($this->at(0))
            ->method('has')
            ->with('provider.manager')
            ->willReturn(FALSE);
        $container->expects($this->never())
            ->method('getDefinition');

        $pass = new RegisterProjectProvidersCompilerPass('provider.manager');
        $pass->process($container);
    }

    public function testRegistration() {
        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder', array('has', 'getDefinition', 'findTaggedServiceIds'));
        $definition = $this->getMock('Symfony\Component\DependencyInjection\Definition');
        $container->expects($this->once())
            ->method('has')
            ->with('provider.manager')
            ->willReturn(TRUE);
        $container->expects($this->once())
            ->method('findTaggedServiceIds')
            ->willReturn(array(
                'provider.foo' => array()
            ));
        $container->expects($this->once())
            ->method('getDefinition')
            ->with('provider.manager')
            ->willReturn($definition);

        $definition->expects($this->once())
            ->method('addMethodCall')
            ->with('addProvider', array(new Reference('provider.foo')));

        $pass = new RegisterProjectProvidersCompilerPass('provider.manager');
        $pass->process($container);
    }


}
