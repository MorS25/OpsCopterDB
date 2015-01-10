<?php

namespace OpsCopter\DB\ProjectBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ProjectUrl extends Constraint {

    public $message = "The url: %url% does not match a known provider.";

    public $provider;


    public function validatedBy() {
        return 'project_url_validator';
    }
}
