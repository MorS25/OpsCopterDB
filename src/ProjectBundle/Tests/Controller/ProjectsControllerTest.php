<?php

namespace OpsCopter\DB\ProjectBundle\Tests\Controller;

use OpsCopter\DB\Common\Tests\DatabaseWebTestCase;
use FOS\RestBundle\Util\Codes;

class ProjectsControllerTest extends DatabaseWebTestCase
{

    public function testJSONCreateProject() {
        $this->client->request('POST', '/projects.json', array(
            'project' => array(
                'id' => __FUNCTION__,
                'name' => __FUNCTION__,
            )
        ));
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, Codes::HTTP_CREATED);
        $this->getProjectJson(__FUNCTION__);
    }

    public function testJSONGetProjects() {
        $this->client->request('GET', '/projects.json');
        $this->assertJsonResponse($this->client->getResponse());
        $this->assertTrue((bool) strstr($this->client->getResponse()->getContent(), 'no-relations'));
    }

    public function testJSONUpdateProject() {
        $this->client->request('PUT', '/projects/no-relations.json', array(
            'project' => array(
                'id' => 'no-relations-updated',
                'name' => 'updated',
            )
        ));
        $this->assertJsonResponse($this->client->getResponse(), Codes::HTTP_CREATED);
        $project = $this->getProjectJson('no-relations-updated');
        $this->assertEquals('updated', $project->name, 'Project was updated.');
    }

    public function testJSONDeleteProject() {
        $this->client->request('DELETE', '/projects/no-relations.json');
        $this->assertJsonResponse($this->client->getResponse(), Codes::HTTP_CREATED);
        $this->getProjectJson('no-relations', 404);
    }

    public function testGetProjectById() {
        $projectA = $this->getProjectJson('no-relations');
        $projectB = $this->getProjectJson($projectA->id);
        $this->assertEquals($projectA, $projectB);
    }

    protected function getProjectJson($id, $statusCode = 200) {
        $this->client->request('GET', '/projects/' . $id . '.json');
        $this->assertJsonResponse($this->client->getResponse(), $statusCode);
        return json_decode($this->client->getResponse()->getContent());
    }


}
