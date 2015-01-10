<?php

namespace OpsCopter\DB\ProjectBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ProjectUriType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('uri', 'url');
        $builder->add('submit', 'submit');
    }

    public function getName() {
        return 'project';
    }
}
