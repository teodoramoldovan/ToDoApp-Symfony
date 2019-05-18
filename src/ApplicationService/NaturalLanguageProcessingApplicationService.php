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



    );

    public function processToDo(ToDoItemFormModel $unprocessed){

        $toDoItem=new ToDoItem();
        $toDoToProcess=$unprocessed->unprocessedToDO;
        $pattern="/((in [0-9]+ days?)|(in [0-9]+ weeks?))|(next week)/";
        $pattern2="/[0-9]+/";
        if(preg_match($pattern,$toDoToProcess,$array)){
            $match=$array[0];
            $name=str_replace($array[0],"",$toDoToProcess);

            if(strpos($match,"next week")!==false){
                $toDoItem->setCalendarDate(new \DateTime('today + 7 days'));
            }
            else if(preg_match($pattern2,$match,$array2)){
                $date=$array2[0];

                if(strpos($match,"day")!==false){
                    $toDoItem->setCalendarDate(new \DateTime('today + '.$date.' days'));
                }
                else if(strpos($match,"week")!==false){
                    $toDoItem->setCalendarDate(new \DateTime('today + '.($date*7).' days'));
                }
            }
            $toDoItem->setName($name);

        }
        else {
            $parts=explode(" ",$toDoToProcess);
            foreach (array_slice($parts,0) as $part){
                if(in_array($part,$this->dates)){
                    $key=array_search($part,$parts);

                    $toDoItem->setCalendarDate(new \DateTime($part));
                    unset($parts[$key]);
                }
            }
            $toDoItem->setName(implode(" ",$parts));
        }
        $toDoItem->setProject($unprocessed->project);
        return $toDoItem;

    }

}