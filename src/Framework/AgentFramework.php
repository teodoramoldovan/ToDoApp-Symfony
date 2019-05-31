<?php


namespace App\Framework;


use App\Agents\ToDoSearchAgent;
use App\Agents\WeatherAgent;

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
        $filteredAgents=$this->findAgentsByVerb($verb);
        $agent=$this->findAgentByNoun($filteredAgents,$noun);
        return array_search($agent,$this->agents);

    }

    public function receiveIntent($noun,$verb){
        $foundAgent=$this->findAgent($noun,$verb);
        return $this->actualAgents[$foundAgent]->getData();
    }

    public function findAgentsByVerb($verb){
        $agentsFound=array();
        foreach($this->agents as $agent){
            if($agent['verb']==$verb){
                array_push($agentsFound,$agent);
            }
        }
        return $agentsFound;
    }
    public function findAgentByNoun($agents,$noun){
        foreach($agents as $agent){
            if($agent['noun']==$noun){
                return $agent;
            }
        }
    }

}