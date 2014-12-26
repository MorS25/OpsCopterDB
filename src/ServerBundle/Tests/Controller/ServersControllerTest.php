<?php

namespace OpsCopter\DB\ServerBundle\Tests\Controller;

use OpsCopter\DB\Common\Tests\DatabaseWebTestCase;
use FOS\RestBundle\Util\Codes;

class ServersControllerTest extends DatabaseWebTestCase
{

    public function testJSONCreateServer() {
        $this->client->request('POST', '/servers.json', array(
            'server' => array(
                'id' => __FUNCTION__,
                'name' => __FUNCTION__,
                'description' => __FUNCTION__,
            )
        ));
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, Codes::HTTP_CREATED);
        $this->getServer(__FUNCTION__);
    }

    public function testJSONGetServers() {
        $this->client->request('GET', '/servers.json');
        $this->assertJsonResponse($this->client->getResponse());
        $this->assertTrue((bool) strstr($this->client->getResponse()->getContent(), 'Fixture Server'));
    }

    public function testJSONUpdateServer() {
        $this->client->request('PUT', '/servers/a.json', array(
            'server' => array(
                'id' => 'a-updated',
                'name' => 'a-updated',
                'description' => '',
            )
        ));
        $this->assertJsonResponse($this->client->getResponse(), Codes::HTTP_CREATED);
        $server = $this->getServer('a-updated');
        $this->assertEquals('a-updated', $server->name, 'Server was updated.');
    }

    public function testJSONDeleteServer() {
        $this->client->request('DELETE', '/servers/a.json');
        $this->assertJsonResponse($this->client->getResponse(), Codes::HTTP_CREATED);
        $this->getServer('a', 404);
    }

    /**
     * @param string $id
     */
    protected function getServer($id, $statusCode = 200) {
        if(empty($id)) {
            throw new \Exception("Server ID not specified.");
        }
        $this->client->request('GET', '/servers/' . $id . '.json');
        $this->assertJsonResponse($this->client->getResponse(), $statusCode);
        return json_decode($this->client->getResponse()->getContent());
    }
}
