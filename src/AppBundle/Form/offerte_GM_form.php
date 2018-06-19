<?php

namespace AppBundle\Form;

use AppBundle\Entity\Offerte;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class offerte_GM_form extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('customerNr');
        $builder->add('titel');
        $builder->add('deliveryAddress');
        $builder->add('postcode');
        $builder->add('place');
        $builder->add('delivery_date');
        $builder->add('korting');
        $builder->add('extra_cost');
        $builder->add('BTW');

        $builder->add('objects', CollectionType::class, array(
            'entry_type' => offerte_GM_objects_form::class,
            'entry_options' => array('label' => false),
            'allow_add' => true,
            'allow_delete' => true,
            'label' => " ",
            'by_reference' => false,
        ));

        $builder->add('save', SubmitType::class, array(
            'attr' => array('class' => 'save'),
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Offerte::class,
        ));
    }
}
