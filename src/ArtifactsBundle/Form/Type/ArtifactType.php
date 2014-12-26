<?php

namespace OpsCopter\DB\ArtifactsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ArtifactType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('project', 'entity', array(
                'class' => 'CopterDBProjectBundle:Project',
                'property' => 'name',
            ))
            ->add('filesystem', 'text')
            ->add('filename', 'text')
            ->add('type', 'text')
            ->add('submit', 'submit');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'OpsCopter\DB\ArtifactsBundle\Entity\Artifact',
        ));
    }

    public function getName() {
        return 'artifact';
    }
}
