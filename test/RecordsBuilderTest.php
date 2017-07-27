<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Error;
use Challenge\RecordsBuilder;

class RecordsBuilderTest extends TestCase
{
    /**
     * @var float
     * 52.951458, -1.142332
     */
    private $latitude = 52.951458;

    /**
     * @var float
     */
    private $longitude = -1.142332;

    /**
     * Distances calculated at: http://www.movable-type.co.uk/scripts/latlong.html
     */
    public function validRecordProvider()
    {
        return array_map(function($item) {
            return [$item, $item['dist']];
        }, json_decode(file_get_contents(__DIR__.'/customer_data.json'), true));
    }

    /**
     * @dataProvider validRecordProvider
     */
    public function testDistanceCalculator($json, $distance)
    {
        $builder = new RecordsBuilder($this->latitude, $this->longitude);
        $record = $builder->createRecord($json);
        // Movable Scripts rounding to 4 significant figures reflects the approx. 0.3% accuracy of the spherical model
        $this->assertEquals($distance, $record->getDistance(), "Distance mismatch", $distance * 0.3);
    }

    public function invalidRecordProvider()
    {
        return array_map(function($item) {
            return [['location' => $item]];
        }, [
            ['lat' => 'lon'],
            ['lon' => $this->longitude],
            ['lat' => $this->latitude],
            ['lat' => null, 'lon' => $this->longitude],
            ['lat' => $this->latitude, 'lon' => null],
            ['lat' => false, 'lon' => $this->longitude],
            ['lat' => $this->latitude, 'lon' => false],
            ['lat' => 'a', 'lon' => $this->longitude],
            ['lat' => $this->latitude, 'lon' => 'b'],
            ['lat' => new \stdClass, 'lon' => $this->longitude],
            ['lat' => $this->latitude, 'lon' => new \stdClass],
            ['lat' => [], 'lon' => $this->longitude],
            ['lat' => $this->latitude, 'lon' => []],
            ['lat' => [$this->latitude], 'lon' => $this->longitude],
            ['lat' => $this->latitude, 'lon' => [$this->longitude]],
        ]);
    }

    /**
     * @dataProvider invalidRecordProvider
     * @expectedException InvalidArgumentException
     */
    public function testCreateInvalidRecords($record)
    {
        $builder = new RecordsBuilder($this->latitude, $this->longitude);
        $builder->createRecord($record);
    }

    /**
     * @expectedException TypeError
     */
    public function testInvalidJson()
    {
        $builder = new RecordsBuilder($this->latitude, $this->longitude);
        $builder->createRecord('invalid');
    }
}
