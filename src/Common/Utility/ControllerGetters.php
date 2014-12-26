<?php

namespace OpsCopter\DB\Common\Utility;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait ControllerGetters {

    protected function detectEm() {
        if(isset($this->em) && $this->em instanceof ObjectManager) {
            return $this->em;
        }
        if(method_exists($this, 'getManager')) {
            return $this->getManager();
        }
        throw new \Exception('Unable to find repository');
    }

    public function getProject($project_id, $throw = TRUE) {
        $project = $this->detectEm()->find('CopterDBProjectBundle:Project', $project_id);
        if(!$project && $throw) {
            throw new NotFoundHttpException("Project not found");
        }
        return $project;
    }

    public function getServer($server_id, $throw = TRUE) {
        $server = $this->detectEm()->find('CopterDBServerBundle:Server', $server_id);
        if(!$server && $throw) {
            throw new NotFoundHttpException("Server not found");
        }
        return $server;
    }
}
