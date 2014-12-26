<?php

namespace OpsCopter\DB\ArtifactsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ResourceRequestType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('project', 'entity', array(
                'class' => 'CopterDBProjectBundle:Project',
                'property' => 'name',
            ))
            ->add('filename', 'text')
            ->add('replace', 'checkbox')
            ->add('submit', 'submit');
    }

    public function getName() {
        return 'resource';
    }
}
