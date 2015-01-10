<?php

namespace OpsCopter\DB\ProjectBundle\Importer;

use Doctrine\ORM\EntityRepository;

class ProjectImporter {

    protected $types = array(

    );

    public function __construct(EntityRepository $r) {
        $r->getClassName();
        $this->types = array(
            ''
        )
    }
}
