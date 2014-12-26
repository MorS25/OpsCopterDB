<?php

namespace OpsCopter\DB\ProjectBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ProjectType extends AbstractType {


    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('id', 'text')
            ->add('name', 'text')
            ->add('save', 'submit');

        /**
         * @see Symfony\Bundle\SecurityBundle\Tests\Functional\Bundle\CsrfFormLoginBundle\Form\UserLoginFormType
         * for an example of how to manipulate the data after submission.
         */
    }

    public function getName() {
        return 'project';
    }
}
