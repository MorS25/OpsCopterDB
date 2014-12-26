<?php

namespace OpsCopter\DB\ServerBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ServerFactType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('type', 'text')
            ->add('value', 'text');
//            ->add('save', 'submit');

        /**
         * @see Symfony\Bundle\SecurityBundle\Tests\Functional\Bundle\CsrfFormLoginBundle\Form\UserLoginFormType
         * for an example of how to manipulate the data after submission.
         */
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'OpsCopter\DB\ServerBundle\Entity\ServerFact'
        ));
    }

    public function getName() {
        return 'facts';
    }
}
