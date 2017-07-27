#!/usr/bin/env node

var LatLon = require('./node_modules/geodesy/latlon-spherical.js');

// Latitude: -85 to +85
// Longitude: -180 to +180
function Records(lat1 = 52.951458, lon1 = -1.142332) {
    return {
        data: [],
        basePoint: LatLon(lat1, lon1),
        random: function (min, max) {
            return (Math.random() * Math.abs(max - min)) + min
        },
        addRandom: function (minLat = -85, maxLat = 85, minLon = -180, maxLon = 180) {
            var record = {
                name: "random_name",
                location: {
                  lat: this.random(minLat, maxLat),
                  lon: this.random(minLon, maxLon)
                }
            }
            var ll = LatLon(record.location.lat, record.location.lon);
            record.dist = this.basePoint.distanceTo(ll)/1000;
            this.data.push(record)
        }
    }
}
var records = new Records()
for (var i = 0; i <20; i++) {
    records.addRandom(52, 53, -2, -1)
}

var fs = require('fs');
fs.writeFile('./test/customer_data.json', JSON.stringify(records.data, null, 4), (err) => {
    if (err) {
        console.error(err);
        return;
    };
    console.log("File has been created");
});

console.log(records.data)
// var wStream = fs.createWriteStream('./test/customer_data.txt', {flags: 'a'})
// records.data.map(function(line) {
//     wStream.write(JSON.stringify(line) + "\n")
// });
// wStream.end()
