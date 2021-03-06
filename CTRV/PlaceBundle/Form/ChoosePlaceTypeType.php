<?php

namespace CTRV\PlaceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class ChoosePlaceTypeType extends AbstractType
{
	
	
    public function buildForm (FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('placeType', 'entity', array (
    			'class' => 'CTRVPlaceBundle:PlaceType',
    			'query_builder' => function(EntityRepository $er) {
    					return $er->createQueryBuilder('u')
    					->orderBy('u.label', 'ASC');
    				},
    			'label'=>'place.ajouterForm.placeType',
    			'attr'=> array('class'=>'input-xlarge')
    	))
        ;
    }

    
    public function getName()
    {
        return 'choose_place_type';
    }
}
