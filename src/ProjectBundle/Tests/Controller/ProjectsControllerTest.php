<?php

namespace OpsCopter\DB\ProjectBundle\Tests\Controller;

use OpsCopter\DB\Common\Tests\DatabaseWebTestCase;
use FOS\RestBundle\Util\Codes;
use OpsCopter\DB\ProjectBundle\Entity\GithubProject;
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
        $this->assertJsonResponseCount($this->client->getResponse(), 1);
    }

    public function testJSONPageProjects() {
        $this->createProjects(25, 'foo', 'http://github.com/foo/bar');
        $this->client->request('GET', '/projects.json');
        $this->assertJsonResponseCount($this->client->getResponse(), 10);

        $this->client->request('GET', '/projects.json?count=12');
        $this->assertJsonResponseCount($this->client->getResponse(), 12);

        $this->client->request('GET', '/projects.json?count=12&page=3');
        $this->assertJsonResponseCount($this->client->getResponse(), 1);
    }

    protected function createProjects($count, $name, $uri) {
        for($i = 1; $i < $count; $i++) {
            $project = new GithubProject($name . $i);
            $project->setName($name . $i);
            $project->setUri($uri . $i);
            $this->em->persist($project);
        }
        $this->em->flush();
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

    protected function assertJsonResponseCount($response, $count, $statusCode = 200) {
        $this->assertJsonResponse($response, $statusCode);
        $this->assertCount($count, json_decode($response->getContent()));
    }
}
