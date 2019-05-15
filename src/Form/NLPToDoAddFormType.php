<?php


namespace App\Form;


use App\Domain\Model\WorkspaceDTO;
use App\Entity\Project;
use App\Entity\Tag;
use App\Entity\ToDoItem;
use App\Form\Model\ToDoItemFormModel;
use App\Repository\ProjectRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NLPToDoAddFormType extends AbstractType
{
    /**
     * @var WorkspaceDTO $workspace
     */
    private $workspace;
    public function buildForm(FormBuilderInterface $builder, array $options){
        /** @var ToDoItem|null $toDoItem */
        $toDoItem=$options['data']??null;


        $this->workspace=$options['workspace'];


        $builder
            ->add('unprocessedToDO',TextType::class, [
                'label'=>'To-do',
            ])
            ->add('project',EntityType::class,[
                'class'=>Project::class,
                'query_builder' => function (ProjectRepository $projectRepository) {

                    return $projectRepository->queryProjectsByWorkspace($this->workspace->slug);
                },
                'choice_label'=>'name',
                'label' => 'Project',
                'placeholder'=>'Choose a project',
                'required'=>true,
            ])

        ;

    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ToDoItemFormModel::class,
            'workspace'=>null,
        ]);
    }

}