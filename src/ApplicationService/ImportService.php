<?php


namespace App\ApplicationService;


use App\Entity\CheckPoint;
use App\Entity\Project;
use App\Entity\Tag;
use App\Entity\ToDoItem;

class ImportService
{
    private $toDoService;
    private $projectService;
    private $tagService;
    private $checkpointService;

    /**
     * ImportService constructor.
     * @param $toDoService
     * @param $projectService
     * @param $tagService
     * @param $checkpointService
     */
    public function __construct(ToDoItemApplicationService $toDoService,
                                ProjectApplicationService $projectService,
                                TagApplicationService $tagService,
                                CheckPointApplicationService $checkpointService)
    {
        $this->toDoService = $toDoService;
        $this->projectService = $projectService;
        $this->tagService = $tagService;
        $this->checkpointService = $checkpointService;
    }

    public function importData($data, $workspace)
    {

        foreach($data as $project){
            $newProject=new Project();

            $newProject->setName($project->name);
            $newProject->setDescription($project->description);
            $newProject->setWorkspace($workspace);

            foreach ($project->listTodos as $toDo){
                $newToDo=new ToDoItem();
                $newToDo->setName($toDo->name);
                $newToDo->setDescription($toDo->description);
                if($toDo->date!=0){

                    $date=intdiv($toDo->date,1000);
                    $datetime=new \DateTime("@{$date}");
                    $newToDo->setCalendarDate($datetime);
                }
                if($toDo->deadline!=0){
                    $date=intdiv($toDo->deadline,1000);
                    $datetime=new \DateTime("@{$date}");
                    $newToDo->setDeadline($datetime);
                }
                $newToDo->setDone($toDo->done);
                if($toDo->thisEvening==true){
                    $newToDo->setHeading('this_evening');
                }
                foreach($toDo->listTags as $tag){
                    if($this->findTagIfExists($tag)==null){

                        $newTag=new Tag();
                        $name=substr($tag->name,1);
                        $newTag->setName($name);
                        $this->tagService->addTag($newTag);
                        $newToDo->addTag($newTag);
                    }
                    else{

                        $eTag=$this->findTagIfExists($tag);
                        $newToDo->addTag($eTag);
                    }


                }

                foreach($toDo->listCheckpoints as $checkpoint){
                    $newCheckpoint=new CheckPoint();
                    $newCheckpoint->setName($checkpoint->description);
                    $newCheckpoint->setDone($checkpoint->checked);
                    $this->checkpointService->addCheckPoint($newCheckpoint);
                    $newToDo->addCheckPoint($newCheckpoint);

                }
                //$newToDo->setProject($newProject);
                $this->toDoService->addToDoItem($newToDo);
                $newProject->addToDoItem($newToDo);


            }
            $this->projectService->addProject($newProject);
            //dd($newProject);

        }

    }
    public function findTagIfExists($tag){
        $existentTags=$this->tagService->findAll();
        foreach($existentTags as $eTag){
            $name=substr($tag->name,1);
            if($name==$eTag->getName()){
                return $eTag;
            }
        }

        return null;
    }

}