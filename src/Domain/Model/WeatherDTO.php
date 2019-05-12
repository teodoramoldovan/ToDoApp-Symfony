<?php


namespace App\Domain\Model;


class WeatherDTO
{

    private $condition;
    private $temperature;
    private $windSpeed;
    private $cloudPercentage;
    private $visibility;
    private $locationName;
    private $icon;
    private $description;
    private $suggestion;


    public function __construct($condition, $temperature, $windSpeed,
                                $cloudPercentage, $visibility,
                                $locationName, $icon, $description,$suggestion)
    {
        $this->condition = $condition;
        $this->temperature = $temperature;
        $this->windSpeed = $windSpeed;
        $this->cloudPercentage = $cloudPercentage;
        $this->visibility = $visibility;
        $this->locationName = $locationName;
        $this->icon=$icon;
        $this->description=$description;
        $this->suggestion=$suggestion;
    }

    /**
     * @return mixed
     */
    public function getSuggestion()
    {
        return $this->suggestion;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @return mixed
     */
    public function getCondition()
    {
        return $this->condition;
    }

    /**
     * @return mixed
     */
    public function getTemperature()
    {
        return $this->temperature;
    }

    /**
     * @return mixed
     */
    public function getWindSpeed()
    {
        return $this->windSpeed;
    }

    /**
     * @return mixed
     */
    public function getCloudPercentage()
    {
        return $this->cloudPercentage;
    }

    /**
     * @return mixed
     */
    public function getVisibility()
    {
        return $this->visibility;
    }

    /**
     * @return mixed
     */
    public function getLocationName()
    {
        return $this->locationName;
    }


}