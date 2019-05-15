<?php


namespace App\ApplicationService;


use App\Entity\ToDoItem;
use App\Form\Model\ToDoItemFormModel;

class NaturalLanguageProcessingApplicationService
{
    private $dates=array(
        "today",
        "tomorrow",
        "Monday", "monday",
        "Tuesday", "tuesday",
        "Wednesday","wednesday",
        "Thursday", "thursday",
        "Friday", "friday",
        "Saturday", "saturday",
        "Sunday", "sunday",
        "in", "next"


    );

    public function processToDo(ToDoItemFormModel $unprocessed){

        $toDoItem=new ToDoItem();
        $toDoToProcess=$unprocessed->unprocessedToDO;
        $parts=explode(" ",$toDoToProcess);
        foreach (array_slice($parts,0) as $part){
            if(in_array($part,$this->dates)){
                $key=array_search($part,$parts);
                if($part=="in" && ($parts[$key+2]=="days" || $parts[$key+2]=="day")){
                    $numberOfDays=$parts[$key+1];
                    $toDoItem->setCalendarDate(new \DateTime('today + '.$numberOfDays.' days'));
                    unset($parts[$key]);
                    unset($parts[$key+1]);
                    unset($parts[$key+2]);
                }
                else if($part=="in" && ($parts[$key+2]=="weeks"|| $parts[$key+2]=="week")){
                    $numberOfWeeks=$parts[$key+1];
                    $toDoItem->setCalendarDate(new \DateTime('today + '.($numberOfWeeks*7).' days'));
                    unset($parts[$key]);
                    unset($parts[$key+1]);
                    unset($parts[$key+2]);
                }
                else if($part=="next" && $parts[$key+1]=="week"){

                    $toDoItem->setCalendarDate(new \DateTime('today + 7 days'));
                    unset($parts[$key]);
                    unset($parts[$key+1]);

                }
                else {
                    $toDoItem->setCalendarDate(new \DateTime($part));
                    unset($parts[$key]);
                }

            }

        }
        $toDoItem->setName(implode(" ",$parts));
        $toDoItem->setProject($unprocessed->project);
        return $toDoItem;

    }

}