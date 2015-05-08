<?php

namespace Restaurant\TablesBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TableType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('available');
        $builder->add('occupationTime', 'text',
            array('data' => null));
        $builder->add('capacity');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Restaurant\TablesBundle\Document\Table',
            'csrf_protection' => false,
        ));
    }

    public function getName()
    {
        return 'table';
    }
}