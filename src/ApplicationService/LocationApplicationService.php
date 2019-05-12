<?php


namespace App\ApplicationService;


use App\Domain\Model\LocationDTO;

class LocationApplicationService
{
    public function deserializeLocation($record)
    {
        $city=$record->city->name;
        $country=$record->country->name;
        $continent=$record->continent->name;
        $latitude=$record->location->latitude;
        $longitude=$record->location->longitude;
        $timeZone=$record->location->timeZone;
        $postalCode=$record->postal->code;

        return new LocationDTO($city,$country,$continent,$timeZone,$latitude,$longitude,$postalCode);


    }

}