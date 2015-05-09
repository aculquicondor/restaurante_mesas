<?php
/**
 * Created by PhpStorm.
 * User: ajax
 * Date: 09/05/15
 * Time: 07:32 AM
 */

namespace Restaurant\TablesBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OrderItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('menuItem');
        $builder->add('observation')
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
        ));
    }

    public function getName()
    {
        return 'orderItem';
    }
}