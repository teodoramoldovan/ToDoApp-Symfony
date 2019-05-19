<?php


namespace App\Framework;


use App\Agents\ToDoSearchAgent;
use App\Agents\WeatherAgent;
use Pyrrah\Bundle\OpenWeatherMapBundle\Services\Client;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class AgentFramework
{
    private $weatherAgent;
    private $toDoSearchAgent;
    public $agents=[
        'WeatherAgent'=> [
            'noun'=>'weather',
            'verb'=>'display'
        ],
        'ToDoSearchAgent'=>[
            'noun'=>'toDo',
            'verb'=>'search'
        ]

    ];
    private $actualAgents;

    /**
     * AgentFramework constructor.
     */
    public function __construct(WeatherAgent $weatherAgent,ToDoSearchAgent $toDoSearchAgent)
    {
        $this->weatherAgent=$weatherAgent;
        $this->toDoSearchAgent=$toDoSearchAgent;
        $this->actualAgents=[
            'WeatherAgent'=>$this->weatherAgent,
            'ToDoSearchAgent'=>$this->toDoSearchAgent
        ];

    }

    public function findAgent($noun,$verb){
        foreach ($this->agents as $agent){
            if($agent['noun']==$noun && $agent['verb']==$verb){
                return array_search($agent,$this->agents);

            }
        }
        return "not found";
    }

    public function receiveIntent($noun,$verb){
        $foundAgent=$this->findAgent($noun,$verb);
        if($foundAgent!="not found"){
            return $this->actualAgents[$foundAgent]->getData();
        }
        return "muita";
    }

}