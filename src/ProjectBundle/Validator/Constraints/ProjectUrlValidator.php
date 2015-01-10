<?php

namespace OpsCopter\DB\ProjectBundle\Validator\Constraints;

use OpsCopter\DB\ProjectBundle\ProjectTypeManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ProjectUrlValidator extends ConstraintValidator {

    protected $manager;

    public function __construct(ProjectTypeManager $manager = NULL) {
        if($manager) {
            $this->setManager($manager);
        }
    }

    public function setManager(ProjectTypeManager $manager) {
        $this->manager = $manager;
    }

    public function validate($value, Constraint $constraint) {
        if($constraint->provider) {
            // Validate against a known project type.
            $provider = $this->manager->getProvider($constraint->provider);
            if(!$provider->isValidUri($value)) {
                $this->context->buildViolation($constraint->message)
                    ->setParameter('%url%', $value)
                    ->addViolation();
            }
        }
        else {
            try {
                $this->manager->getProviderByUri($value);
            }
            catch(\InvalidArgumentException $e) {
                $this->context->buildViolation($constraint->message)
                    ->setParameter('%url%', $value)
                    ->addViolation();
            }
        }
    }
}
