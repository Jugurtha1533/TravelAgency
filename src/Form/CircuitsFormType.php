<?php

namespace App\Form;

use App\Entity\Categories;
use App\Entity\Image;
use App\Entity\Circuits;
use App\Repository\CategoriesRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Image as ConstraintsImage;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;


class CircuitsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', options:[
                'label' => 'Nom',
                'constraints'=>[
                    new NotBlank(
                        message:'Le nom ne peut être vide'
                    ),
                    new Length(
                       min:3,
                       max:200,
                       minMessage:'le titre doit faire au moins 3 caractères',
                       maxMessage:'le titre ne doit pas faire plus de 200 caractères'
                    )
                ]
            ])
            ->add('description')
            ->add('price',MoneyType::class, options:[
                'label' => 'Prix',
                'divisor'=>100,
                'constraints'=>[
                    new Positive(
                        message:'Le prix ne peut être négatif'
                    )
                ]
            ])
            ->add('stock', options:[
                'label' => 'Unités en stock',
                'constraints'=>[
                    new Positive(
                        message:'Le stock ne peut être négatif'
                    )
                ]
            ])
            ->add('categories', EntityType::class, [
                'class' => Categories::class,
                'choice_label' => 'name',
                'label' => 'Catégorie',
                'group_by' => 'parent.name',
                'query_builder' => function(CategoriesRepository $cr){
                    return $cr->createQueryBuilder('c')
                        ->where('c.parent IS NOT NULL')
                        ->orderBy('c.name', 'ASC');
                }
            ])
            ->add('images', FileType::class, [
                'label' => false,
                'multiple' => true,
                'mapped' => false,
                'required' => false,
                'constraints'=>[
                    new All(new ConstraintsImage([
                        'maxWidth'=>1280,
                        'maxWidthMessage'=>'L\image doit faire {{max_width}} pixels de large au maximum '

                    ]))
                    
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Circuits::class,
        ]);
    }
}