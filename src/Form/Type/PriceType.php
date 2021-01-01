<?php

namespace App\Form\Type;

use App\Form\DataTransformer\CentimesTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PriceType extends AbstractType
{

    //On utilise notre dataTransformer
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Si notre option divide est true on applique notre dataTransform
        if ($options['divide'] === false) {
            return;
        }
        $builder->addModelTransformer(new CentimesTransformer);
    }
    // fonction qui dit de quelle grande classe de symfony on hérite
    public function getParent()
    {
        return NumberType::class;
    }

    //fct de l'abstract qu'on vient surcharger
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'divide' => true
        ]);
    }
}
