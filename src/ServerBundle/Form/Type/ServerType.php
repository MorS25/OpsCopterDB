<?php

namespace OpsCopter\DB\ServerBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ServerType extends AbstractType {


    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('id', 'text')
            ->add('name', 'text')
            ->add('description', 'text')
            ->add('facts', 'collection', array(
                'type' => new ServerFactType(),
                'allow_add' => TRUE,
                'allow_delete' => TRUE,
                'by_reference' => FALSE,
            ))
            ->add('save', 'submit');

        /**
         * @see Symfony\Bundle\SecurityBundle\Tests\Functional\Bundle\CsrfFormLoginBundle\Form\UserLoginFormType
         * for an example of how to manipulate the data after submission.
         */
    }

    public function getName() {
        return 'server';
    }
}
