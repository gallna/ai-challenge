<?php
namespace Challenge;

class RecordsBuilder
{
    /**
     * @var float
     */
    private $latitude;

    /**
     * @var float
     */
    private $longitude;

    /**
     * Nautical mile: Number of kilometers in nautical mile
     * @var float
     */
    const NM = 1.852;

    /**
     * Units of measurement
     */
    const KILOMETRES = 'K';
    const STATUTE_MILES = 'M';
    const NAUTICAL_MILES = 'N';

    /**
     * Constructor with base latitude and longitude used to calculate distance
     * @param float $latitude Latitude of start point
     * @param float $longitude Longitude of start point
     */
    public function __construct(float $latitude, float $longitude)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    /**
     * Validate and create single record from array. Expected format:
     * [
     *   "location":["lat":52.72423653147739,"lon":-0.6552093528558713]
     * ]
     * @param array $data Unit of measurement
     * @return Record
     */
    public function createRecord(array $data)
    {
        if (!isset($data['location']) || !is_array($data['location'])) {
            throw new \DomainException("Invalid json data");
        }
        $location = $data['location'];
        if (!isset($location['lon']) || !is_numeric($location['lon'])) {
            throw new \InvalidArgumentException("Invalid json, invalid longitude");
        }

        if (!isset($location['lat']) || !is_numeric($location['lat'])) {
            throw new \InvalidArgumentException("Invalid json, invalid latitude");
        }

        $record = new Record($data);
        $record->setDistance(
            $this->getDistance((float) $location['lat'], (float) $location['lon'])
        );
        return $record;
    }

    /**
     * Calculates the great-circle distance between two points
     * @param float $latitude Latitude of end point
     * @param float $longitude Longitude of end point
     * @param string $unit Unit of measurement
     * @return float Distance between points
     */
    public function getDistance(float $latitude, float $longitude, $unit = null)
    {
        $theta = $longitude - $this->longitude;
        $dist = sin(deg2rad($latitude)) * sin(deg2rad($this->latitude)) +  cos(deg2rad($latitude)) * cos(deg2rad($this->latitude)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $rad = M_PI / 180;
        $miles = $dist * 60 * $rad * 60 * static::NM;
        switch ($unit) {
            case static::KILOMETRES:
                return ($miles * 1.609344);
            case static::NAUTICAL_MILES:
                return ($miles * 0.8684);
            case static::STATUTE_MILES:
            default:
                return $miles;
        }
    }
}
