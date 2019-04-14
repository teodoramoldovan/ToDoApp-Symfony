<?php


namespace App\Form;


use App\Domain\Model\WorkspaceDTO;
use App\Entity\CheckPoint;
use App\Entity\ToDoItem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CheckPointFormType extends AbstractType
{
    /**
     * @var WorkspaceDTO $workspace
     */
    private $workspace;
    public function buildForm(FormBuilderInterface $builder, array $options){
        /** @var CheckPoint|null $checkPoint */
        $checkPoint=$options['data']??null;


        $this->workspace=$options['workspace'];
        $toDoItem=$options['toDoItem'];
        $builder
            ->add('name',TextType::class, [

            ])


            ->add('toDoItem',TextType::class,[
                'data'=>$toDoItem->getName(),
                'disabled'=>true,
                'required'=>true,
            ])

        ;

    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CheckPoint::class,
            'workspace'=>null,
            'toDoItem'=>null
        ]);
    }


}