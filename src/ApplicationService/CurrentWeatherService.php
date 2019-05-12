<?php


namespace App\ApplicationService;


use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;
use MaxMind\Db\Reader\InvalidDatabaseException;
use Pyrrah\Bundle\OpenWeatherMapBundle\Services\Client;
use Symfony\Component\HttpFoundation\Response;

class CurrentWeatherService
{
    public function getCurrentWeatherAtClientLocation(Client $pyrrahclient,
                                                      LocationApplicationService $locationService,
                                                      WeatherApplicationService $weatherService,
                                                        $projectDir){

        $GeoLiteDatabasePath = $projectDir . '/private/Geolite2-City/GeoLite2-City.mmdb';

        //$ip=$request->getClientIp();


        try{

            $reader=new Reader($GeoLiteDatabasePath);
            // if you are in the production environment you can retrieve the
            // user's IP with $request->getClientIp()
            // Note that in a development environment 127.0.0.1 will
            // throw the AddressNotFoundException


            // fixed ip in cluj
            $record = $reader->city('82.78.33.18');

        } catch (AddressNotFoundException $ex) {
            // Couldn't retrieve geo information from the given IP
            return new Response("It wasn't possible to retrieve information about the providen IP");
        } catch(InvalidDatabaseException $ex){
            return new Response("Cannot find database");
        }
        $location=$locationService->deserializeLocation($record);


        //$weather = $pyrrahclient->getWeather($location->getCity());//getWeather('Cluj-Napoca');
        $response = $pyrrahclient->query('weather', array('q' => $location->getCity()));
        //dd($response);
        $weather=$weatherService->deserializeWeather($response);
        //dd($weather);

        return $weather;
    }

}