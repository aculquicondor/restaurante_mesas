<?php

namespace Restaurant\TablesBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OrderType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('date');
        $builder->add('table');
        $builder->add('active');
        $builder->add('employee');
    }

    public function getName()
    {
        return 'order';
    }

}