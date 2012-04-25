<?php

namespace Acme\DesignBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class TypeObjectType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('name', 'text')
            ->add('description', 'textarea', array('required'=> false))
        ;
    }

    public function getName()
    {
        return 'acme_designbundle_typeobjecttype';
    }
}
