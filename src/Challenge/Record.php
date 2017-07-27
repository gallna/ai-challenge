<?php
namespace Challenge;

class Record extends \ArrayObject
{
    private $distance;
    
    public function getDistance()
    {
        return $this->distance;
    }
    public function setDistance(float $distance)
    {
        $this->distance = $distance;
        return $this;
    }
}
