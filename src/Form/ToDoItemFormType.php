<?php


namespace App\Form;


use App\ApplicationService\ProjectApplicationService;
use App\Domain\Model\WorkspaceDTO;
use App\Entity\Project;
use App\Entity\Tag;
use App\Entity\ToDoItem;
use App\Entity\Workspace;
use App\Repository\ProjectRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ToDoItemFormType extends AbstractType
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
            ->add('heading',ChoiceType::class,[
                'choices'=>['This Evening'=>'this_evening'],
                'label'=>'When exactly?',
                'help'=>'If you check this, leave calendar date empty',
                'expanded'=>true,
                'placeholder'=>false,
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
            'workspace'=>null,
        ]);
    }

}