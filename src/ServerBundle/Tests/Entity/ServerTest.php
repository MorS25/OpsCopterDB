<?php

namespace OpsCopter\DB\ServerBundle\Tests\Entity;

use OpsCopter\DB\Common\Utility\ControllerGetters;
use OpsCopter\DB\ServerBundle\Entity\Server;
use OpsCopter\DB\ServerBundle\Entity\ServerFact;
use OpsCopter\DB\Common\Tests\DatabaseKernelTestCase;

class ServerTest extends DatabaseKernelTestCase {
    use ControllerGetters;

    public function testGetOs() {
        $server = new Server(__FUNCTION__);
        $server->addFact(new ServerFact('OS', 'Debian'));
        $this->assertEquals('Debian', $server->getOs());
    }

    public function testGetOsVersion() {
        $server = new Server(__FUNCTION__);
        $server->addFact(new ServerFact('OS_VERSION', '7.7'));
        $this->assertEquals('7.7', $server->getOsVersion());
    }

    public function testGetHostname() {
        $server = new Server(__FUNCTION__);
        $server->addFact(new ServerFact('HOSTNAME', 'test.local'));
        $this->assertEquals('test.local', $server->getHostname());
    }

    public function testCreateServer() {
        $server = new Server(__FUNCTION__);
        $server->setName('Server 1');
        $this->em->persist($server);
        $this->em->flush($server);


        $server = $this->getServer(__FUNCTION__);
        $this->assertEquals(__FUNCTION__, $server->getId(), 'Server was persisted to the database.');
    }

    public function testDeleteServer() {
        $server = $this->getServer('a');
        $id = $server->getId();
        $this->em->remove($server);

        $this->em->flush($server);
        $server = $this->getServer('a', FALSE);
        $this->assertNull($server);

        $facts = $this->em->getRepository('CopterDBServerBundle:ServerFact')->findBy(array('server' => $id));
        $this->assertEquals(0, count($facts));
    }

    public function testUpdateServer() {
        $server = $this->getServer('a');
        foreach($server->getFacts() as $fact) {
            $server->removeFact($fact);
        }
        $this->em->persist($server);
        $this->em->flush();

        $facts = $this->em->getRepository('CopterDBServerBundle:ServerFact')->findBy(array('server' => $server->getId()));
        $this->assertEquals(0, count($facts));
    }
}
