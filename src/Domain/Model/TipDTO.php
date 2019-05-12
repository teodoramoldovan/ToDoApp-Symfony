<?php


namespace App\Domain\Model;


class TipDTO
{
    private $tip;

    /**
     * TipDTO constructor.
     * @param $tip
     */
    public function __construct($weatherType)
    {
        $this->makeASuggestion($weatherType);
    }

    /**
     * @return mixed
     */
    public function getTip()
    {
        return $this->tip;
    }

    private function makeASuggestion($weatherType){
        switch($weatherType){
            case "Clear":
                $this->tip="You may need sunglasses";
                break;
            case "Snow":
                $this->tip="Put on winter clothes";
                break;
            case "Rain":
                $this->tip="Bring an umbrella";
                break;
            case "Drizzle":
                $this->tip="You may get a little wet...";
                break;
            case "Thunderstorm":
                $this->tip="Don't go out if you are afraid of thunders";
                break;
            case "Clouds":
                $this->tip="Sun is hidding right now";
                break;
            default:
                $this->tip="I don't have any tips for you";
                break;
        }
    }

}