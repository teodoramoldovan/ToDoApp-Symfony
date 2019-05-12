<?php


namespace App\ApplicationService;


use App\Domain\Model\TipDTO;
use App\Domain\Model\WeatherDTO;

class WeatherApplicationService
{
    public function deserializeWeather($response)
    {
        $condition=$response->weather[0]->main;
        $temperature=$response->main->temp;
        $windSpeed=$response->wind->speed;
        $cloudPercentage=$response->clouds->all;
        $visibility=$response->visibility;
        $locationName=$response->name;
        $icon=$response->weather[0]->icon;
        $description=$response->weather[0]->description;
        $tip=new TipDTO($condition);
        $suggestion=$tip->getTip();

        return new WeatherDTO($condition,$temperature,$windSpeed,
            $cloudPercentage,$visibility,$locationName,$icon,$description,$suggestion);
    }

}