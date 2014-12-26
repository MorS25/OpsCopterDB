<?php

namespace OpsCopter\DB\Common\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ConfirmType extends AbstractType {


    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('Confirm', 'submit');
    }

    public function getName() {
        return 'project';
    }
}
