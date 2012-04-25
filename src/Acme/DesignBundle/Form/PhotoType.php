<?php

namespace Acme\DesignBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class PhotoType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('name', 'text')
            ->add('description', 'textarea')
            ->add('file')
            ->add('type')
            ->add('object')
        ;
    }

    public function getName()
    {
        return 'acme_designbundle_phototype';
    }
}
