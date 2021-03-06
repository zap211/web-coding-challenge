<?php

namespace CTRV\PlaceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class PlaceTypeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('label','text',array('label'=>'place.ajouterForm.libelle'))
            ->add('code','text',array('label'=>'place.ajouterForm.code'))
            ->add('language','choice',array('label'=>'place.ajouterForm.langue','choices'=>array(
                  'FRENCH'   => 'place.ajouterForm.fr',
                  'ENGLISH' => 'place.ajouterForm.en',
                  'SPANISH'   => 'place.ajouterForm.es',
    ),
            		))
            ->add('img_url','file',array('label'=>'place.ajouterForm.select_file','required'=>false))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CTRV\PlaceBundle\Entity\PlaceType'
        ));
    }

    public function getName()
    {
        return 'ctrv_placebundle_placetypetype';
    }
}
