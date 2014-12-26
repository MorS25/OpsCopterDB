<?php

namespace OpsCopter\DB\ServerBundle\DataFixtures\ORM;

use OpsCopter\DB\ServerBundle\Entity\Server;
use OpsCopter\DB\ServerBundle\Entity\ServerFact;
use OpsCopter\DB\ServerBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadServerData extends AbstractFixture implements OrderedFixtureInterface {

    private $container;

    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $server = new Server('a');
        $server->setName('Fixture Server');
        $server->addFact(new ServerFact('PHP_VERSION', PHP_VERSION));
        $server->addFact(new ServerFact('HOSTNAME', 'fixtures.example.org'));

        $manager->persist($server);

        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder() {
        return 2;
    }
}
