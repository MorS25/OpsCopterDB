<?php

namespace OpsCopter\DB\Common\Tests;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bridge\Doctrine\DataFixtures\ContainerAwareLoader as DataFixturesLoader;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\BrowserKit\Client;

class DatabaseWebTestCase extends WebTestCase {

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @var Client
     */
    protected $client;

    public function setUp() {
        parent::setUp();
        static::bootKernel();

        $container = static::$kernel->getContainer();
        $entityManager = $container->get('doctrine')->getManager();
        $metadata = $entityManager->getMetadataFactory()->getAllMetadata();
        if(!empty($metadata)) {
            $tool = new SchemaTool($entityManager);
            $tool->dropSchema($metadata);
            $tool->createSchema($metadata);
        }

        $paths = array();
        foreach (static::$kernel->getBundles() as $bundle) {
            $paths[] = $bundle->getPath().'/DataFixtures/ORM';
        }

        $loader = new DataFixturesLoader($container);
        foreach ($paths as $path) {
            if (is_dir($path)) {
                $loader->loadFromDirectory($path);
            }
        }

        $fixtures = $loader->getFixtures();
        if (!$fixtures) {
            throw new \InvalidArgumentException(
                sprintf('Could not find any fixtures to load in: %s', "\n\n- ".implode("\n- ", $paths))
            );
        }
        $purger = new ORMPurger($entityManager);
        $purger->setPurgeMode(ORMPurger::PURGE_MODE_TRUNCATE);
        $executor = new ORMExecutor($entityManager, $purger);
        $executor->execute($fixtures);

        $this->em = $entityManager;

        $this->client = self::createPrivilegedClient();
    }

    protected static function createPrivilegedClient(array $options = array(), array $server = array()) {
        $server = array_merge($server, array(
            'PHP_AUTH_USER' => 'restapi',
            'PHP_AUTH_PW' => 'secretpw',
        ));
        return parent::createClient($options, $server);
    }

    protected function assertJsonResponse(Response $response, $statusCode = 200) {
        $message = 'Success';
        if($response->getStatusCode() === 500) {
            $message = 'Error';
            if($content = json_decode($response->getContent())) {
                if(!empty($content->error->exception)) {
                    $exception = reset($content->error->exception);
                    $message = $exception->message;
                }
            }

        }
        $this->assertEquals($statusCode, $response->getStatusCode(), sprintf('JSON Response returned with: %s', $message));
        $this->assertJson($response->getContent(), 'JSON Response OK');

        $this->assertTrue(
            $response->headers->contains('Content-Type', 'application/json'),
            'JSON Content Type was set'
        );
    }

    protected function getManager() {
        return $this->em;
    }
}
