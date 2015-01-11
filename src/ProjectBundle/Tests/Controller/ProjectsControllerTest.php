<?php

namespace OpsCopter\DB\ProjectBundle\Tests\Controller;

use OpsCopter\DB\Common\Tests\DatabaseWebTestCase;
use FOS\RestBundle\Util\Codes;
use OpsCopter\DB\ProjectBundle\Tests\Fixtures\DummyProjectProvider;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ProjectsControllerTest extends DatabaseWebTestCase
{
    protected $provider;

    protected function preFixtures(ContainerInterface $container) {
        $this->provider = new DummyProjectProvider('github');
        $container->get('copter_db_project.type_manager')
            ->setProviders(array($this->provider));
    }

    public function testJSONCreateProject() {
        $this->client->request('POST', '/projects.json', array(
            'project' => array(
                'uri' => 'https://github.com/foo/bar',
            ),
        ));
        /** @var \Symfony\Component\HttpFoundation\Response $response */
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, Codes::HTTP_CREATED);
    }

    public function testJSONGetProject() {
        $this->client->request('GET', '/projects/1.json');
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response);

        $project = json_decode($response->getContent());
        $this->assertEquals('rbayliss/OpsCopterDB', $project->identifier);
        $this->assertEquals('OpsCopterDB', $project->name);
        $this->assertEquals('A github project', $project->description);
        $this->assertEquals('https://github.com/rbayliss/OpsCopterDB', $project->uri);
    }

    public function testJSONGetProjectsByUri() {
        $this->client->request('GET', '/projects.json?uri=https://github.com/rbayliss/OpsCopterDB');
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response);
        $array = json_decode($response->getContent());
        $this->assertCount(1, $array);
    }

    public function testJsonGetProjects() {
        $this->client->request('GET', '/projects.json');
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response);

        $object = json_decode($response->getContent());
        $project = $object[0];
        $this->assertEquals('rbayliss/OpsCopterDB', $project->identifier);
        $this->assertEquals('OpsCopterDB', $project->name);
        $this->assertEquals('A github project', $project->description);
        $this->assertEquals('https://github.com/rbayliss/OpsCopterDB', $project->uri);
    }

    public function testJSONDeleteProject() {
        $this->client->request('DELETE', '/projects/1.json');
        $this->assertJsonResponse($this->client->getResponse(), Codes::HTTP_CREATED);
        $this->client->request('GET', '/projects/1.json');
        $this->assertJsonResponse($this->client->getResponse(), 404);
    }
}
