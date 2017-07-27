#!/usr/bin/env php
<?php

// Usage: ./customers.php [OPTION]... FILE
//
// Options:
//   -f    File with customer records (json)
//   -a    Base latitude
//   -o    Base longitude
//   -d    Max distance from base point to get results
//
// Example: ./customers.php -f=customer_data.json

include_once 'vendor/autoload.php';

use \JsonStreamingParser\Listener\GeoJsonListener;
use \Challenge\RecordsBuilder;
use \Challenge\RecordsHeap;
use \Challenge\Record;

$options = getopt("o::a::f::d::");

$file = $options["f"] ?? __DIR__.'/test/customer_data.json';
$limit = $options["d"] ? (integer) $options["d"] : 40;
$latitude = $options["a"] ? (float) $options["a"] : 52.951458;
$longitude = $options["o"] ? (float) $options["o"] : -1.142332;
if (!file_exists($file) || !is_readable($file)) {
    throw new \InvalidArgumentException(
        "Selected file does not exists or is not readable"
    );
}

$builder = new RecordsBuilder($latitude, $longitude);
$customers = new RecordsHeap();

$stream = fopen($file, 'r');
$listener = new GeoJsonListener(function ($item) use ($builder, $customers, $limit) {
    if (is_null($item)) {
        return;
    }
    $record = $builder->createRecord($item);
    if ($record->getDistance() < $limit) {
        $customers->insert($record);
    }
});

try {
    $parser = new \JsonStreamingParser\Parser($stream, $listener);
    $parser->parse();
} finally {
    fclose($stream);
}

foreach ($customers as $customer) {
    echo json_encode($customer)."\n";
}
?>
