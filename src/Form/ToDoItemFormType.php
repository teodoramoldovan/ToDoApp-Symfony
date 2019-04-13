<?php


namespace App\Form;


use App\Entity\Tag;
use App\Entity\ToDoItem;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ToDoItemFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options){
        /** @var ToDoItem|null $toDoItem */
        $toDoItem=$options['data']??null;

        $builder
            ->add('name',TextType::class, [

            ])
            ->add('description',null,[
                'rows'=>5,
                'required'=>false,
            ])
            ->add('tags',EntityType::class,[
                'class'=>Tag::class,
                'choice_label'=>'name',
                'label' => 'Tags',
                'expanded'=>true,
                'multiple'=>true,
                'required'=>false,

            ])
            ->add('calendarDate',DateType::class,[
                'widget' => 'single_text',
                'required'=>false,
            ])
            ->add('deadline',DateType::class,[
                'widget' => 'single_text',
                'required'=>false,
            ])
            ;

    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ToDoItem::class,
            'tags' => null,
        ]);
    }

}