<?php

namespace OpsCopter\DB\ProjectBundle;

use OpsCopter\DB\ProjectBundle\Provider\ProjectProvider;

class ProjectTypeManager {

    /**
     * @var ProjectProvider[]
     */
    protected $providers = array();

    public function __construct(array $providers = array()) {
        $this->setProviders($providers);
    }

    public function addProvider(ProjectProvider $provider) {
        $this->providers[$provider->getName()] = $provider;
    }

    public function getProvider($name) {
        if(isset($this->providers[$name])) {
            return $this->providers[$name];
        }
        throw new \InvalidArgumentException(sprintf('Provider %s is not registered', $name));
    }

    public function setProviders(array $providers) {
        $this->providers = array();
        foreach($providers as $provider) {
            $this->addProvider($provider);
        }
    }

    public function getProviderByEntityClass($class) {
        foreach($this->providers as $provider) {
            if($provider->getEntityClass() === $class) {
                return $provider;
            }
        }
        throw new \InvalidArgumentException(sprintf('Provider for class:%s is not registered', $class));
    }

    public function getProviderByUri($uri) {
        foreach($this->providers as $provider) {
            if($provider->isValidUri($uri)) {
                return $provider;
            }
        }
        throw new \InvalidArgumentException(sprintf('Provider for uri:%s is not registered', $uri));
    }

    public function getEntityTypes() {
        $types = array();
        foreach($this->providers as $provider) {
            $types[$provider->getName()] = $provider->getEntityClass();
        }
        return $types;
    }
}
