<?php


namespace App\Domain\Model;


class LocationDTO
{
    private $city;
    private $country;
    private $continent;
    private $timeZone;
    private $latitude;
    private $longitude;
    private $postalCode;

    /**
     * LocationDTO constructor.
     * @param $city
     * @param $country
     * @param $continent
     * @param $timeZone
     * @param $latitude
     * @param $longitude
     * @param $postalCode
     */
    public function __construct($city, $country, $continent, $timeZone, $latitude, $longitude, $postalCode)
    {
        $this->city = $city;
        $this->country = $country;
        $this->continent = $continent;
        $this->timeZone = $timeZone;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->postalCode = $postalCode;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @return mixed
     */
    public function getContinent()
    {
        return $this->continent;
    }

    /**
     * @return mixed
     */
    public function getTimeZone()
    {
        return $this->timeZone;
    }

    /**
     * @return mixed
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @return mixed
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @return mixed
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }


}