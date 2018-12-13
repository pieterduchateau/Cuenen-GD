<?php

namespace AppBundle\Form;

use AppBundle\Entity\Offerte_objects;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class offerte_CUE_objects_form extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('omschrijving',
            TextareaType::class,
            array(
                'attr' => array('style' => 'height:34px')
            ));
        $builder->add('aantal');
        $builder->add('prijs');
        $builder->add('totaal');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Offerte_objects::class,
        ));
    }
}
