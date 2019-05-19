<?php


namespace App\Agents;


use App\ApplicationService\CurrentWeatherService;
use App\ApplicationService\LocationApplicationService;
use App\ApplicationService\WeatherApplicationService;
use Pyrrah\Bundle\OpenWeatherMapBundle\Services\Client;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class WeatherAgent
{
    private $currentWeatherService;
    private $pyrrahclient;
    private  $locationService;
    private  $weatherService;
    private $projectDir;



    /**
     * WeatherAgent constructor.
     */
    public function __construct(WeatherApplicationService $weatherService,
                                LocationApplicationService $locationService,
                                CurrentWeatherService $currentWeatherService,
                                Client $pyrrahclient, ParameterBagInterface $params)
    {
        $this->weatherService=$weatherService;
        $this->locationService=$locationService;
        $this->pyrrahclient= $pyrrahclient;
        $this->currentWeatherService=$currentWeatherService;
        $this->projectDir=$params->get('kernel.project_dir');
    }

    public function getData(){
        return $this->currentWeatherService->
           getCurrentWeatherAtClientLocation($this->pyrrahclient,
                                            $this->locationService,
                                            $this->weatherService,
                                            $this->projectDir);
    }
}