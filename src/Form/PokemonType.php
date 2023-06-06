<?php

namespace App\Form;

use App\Entity\Debilidad;
use App\Entity\Pokemon;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PokemonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre',null, [
            'label'=>'Nombre del Pokemon', 
            'attr'=>['placeholder'=>'Introduce el nombre del Pokemon']
            ] )
            ->add('descripcion')
            ->add('ficheroImagen', FileType::class,[
                'required' => false,
                'mapped' => false,
            ])
            ->add('codigo')
            ->add('debilidades', EntityType::class, [
                // looks for choices from this entity
                'class' => Debilidad::class,
            
                // uses the User.username property as the visible option string
                'choice_label' => 'nombre',
            
                // used to render a select box, check boxes or radios
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('enviar', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pokemon::class,
        ]);
    }
}
