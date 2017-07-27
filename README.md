## Description

We have some customer records in a text file (customer_data.json, attached) one
customer per line, JSON-encoded. We want to invite any customer within 40 miles of our
Nottingham office (GPS coordinates 52.951458, -1.142332) to some food and drinks on
us. Write a program that will read the full list of customers and output the names and user
ids of matching customers (within 40 miles), sorted by distance (ascending). You can use
the first formula from this Wikipedia article (https://en.wikipedia.org/wiki/Greatcircle_distance)
to calculate distance, don’t forget, you’ll need to convert degrees to
radians. Your program should be fully tested too.

## Install

```
git clone git@github.com:gallna/ai-challenge.git
composer install
```

## Usage

Example: `./customers.php -f=customer_data.json -d=40 -a=52.951458, -o="-1.142332"`

```
./customers.php [OPTION]... FILE

Options:
  -f    File with customer records (json)
  -a    Base latitude
  -o    Base longitude
  -d    Limit, number of miles within base point to filter result
```

## Expected record format

```json
{
 "name":"Lawrence Reeves",
 "location":{"lat":52.72423653147739,"lon":-0.6552093528558713}
}
```
