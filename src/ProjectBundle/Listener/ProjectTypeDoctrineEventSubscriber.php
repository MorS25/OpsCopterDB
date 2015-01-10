<?php

namespace OpsCopter\DB\ProjectBundle\Listener;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use OpsCopter\DB\ProjectBundle\ProjectTypeManager;

/**
 * Class ProjectTypeDoctrineEventSubscriber
 * @package OpsCopter\DB\ProjectBundle\Listener
 *
 * Register the defined project types as children of the Project entity type
 */
class ProjectTypeDoctrineEventSubscriber {

    protected $manager;

    public function __construct(ProjectTypeManager $manager) {
        $this->manager = $manager;
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $event) {
        $metadata = $event->getClassMetadata();
        $class = $metadata->getReflectionClass();

        if($class->getName() === 'OpsCopter\DB\ProjectBundle\Entity\Project' && $metadata instanceof \Doctrine\ORM\Mapping\ClassMetadata) {
            foreach($this->manager->getEntityTypes() as $type => $class) {
                $metadata->addDiscriminatorMapClass($type, $class);
            }
        }
    }
}
