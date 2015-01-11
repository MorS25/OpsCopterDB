<?php

namespace OpsCopter\DB\ProjectBundle\Tests;

use OpsCopter\DB\ProjectBundle\ProjectTypeManager;

class ProjectTypeManagerTest extends \PHPUnit_Framework_TestCase {

    public function testRegisterProvider() {
        $provider = $this->getMockProvider('foo');

        $manager = new ProjectTypeManager(array($provider));
        $this->assertSame($provider, $manager->getProvider('foo'));
    }

    public function testReplaceProvider() {
        $provider1 = $this->getMockProvider('foo');
        $provider2 = $this->getMockProvider('foo');

        $manager = new ProjectTypeManager(array($provider1, $provider2));
        $this->assertSame($provider2, $manager->getProvider('foo'));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Provider foo is not registered
     */
    public function testGetInvalidProvider() {
        $manager = new ProjectTypeManager(array());
        $manager->getProvider('foo');
    }

    public function testValidUriDetection() {
        $provider = $this->getMockProvider('foo');
        $provider->expects($this->once())
            ->method('isValidUri')
            ->willReturn(TRUE);

        $manager = new ProjectTypeManager(array($provider));

        $this->assertSame($provider, $manager->getProviderByUri('http://google.com'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidUriDetection() {
        $provider = $this->getMockProvider('foo');
        $provider->expects($this->once())
            ->method('isValidUri')
            ->willReturn(FALSE);

        $manager = new ProjectTypeManager(array($provider));

        $manager->getProviderByUri('http://google.com');
    }

    public function testEntityMappingConfig() {
        $provider = $this->getMockProvider('foo');
        $provider->expects($this->once())
            ->method('getEntityClass')
            ->willReturn('Foo\Bar\Baz');

        $manager = new ProjectTypeManager(array($provider));

        $expected = array(
            'foo' => 'Foo\Bar\Baz'
        );
        $this->assertEquals($expected, $manager->getEntityTypes());
    }

    public function testGetProviderForEntityClass() {
        $provider = $this->getMockProvider('foo');
        $provider->expects($this->once())
            ->method('getEntityClass')
            ->willReturn('Foo\Bar\Baz');

        $manager = new ProjectTypeManager(array($provider));

        $this->assertSame($provider, $manager->getProviderByEntityClass('Foo\Bar\Baz'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetProviderForInvalidEntityClass() {
        $provider = $this->getMockProvider('foo');
        $provider->expects($this->once())
            ->method('getEntityClass')
            ->willReturn('Foo\Bar\Baz');

        $manager = new ProjectTypeManager(array($provider));

        $manager->getProviderByEntityClass('Baz\Foo\Bar');
    }

    protected function getMockProvider($name) {
        $provider = $this->getMock('OpsCopter\DB\ProjectBundle\Provider\ProjectProvider');
        $provider->expects($this->any())
            ->method('getName')
            ->willReturn($name);
        return $provider;
    }
}
