<?php

namespace OpsCopter\DB\ProjectBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class CopterDBProjectExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        if($container->getParameter('kernel.environment') === 'test') {
            $container->getDefinition('copter_db_project.provider.github')
                ->setClass('OpsCopter\DB\ProjectBundle\Tests\Fixtures\DummyProjectProvider')
                ->setArguments(array('github'));

            $container->getDefinition('copter_db_project.provider.bitbucket')
                ->setClass('OpsCopter\DB\ProjectBundle\Tests\Fixtures\DummyProjectProvider')
                ->setArguments(array('bitbucket'));
        }
    }
}
