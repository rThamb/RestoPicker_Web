<?php

/**
 * User: 1439510 Jacob Brooker
 * Date: 11/7/2016
 * Time: 2:23 PM
 */

namespace App\Repositories;

use App\Restaurant;
use App\Favourite;
use App\User;
use DB;

class RestaurantRepository
{
    
    public function getRestosNear($latitude, $longitude, $radius = 50){

            $distances = Restaurant::select('restaurants.*')
            ->selectRaw('( 6371 * acos( cos( radians(?) ) *
                cos( radians( latitude ) )
                * cos( radians( longitude ) - radians(?))
                + sin( radians(?) ) *
                sin( radians(latitude ) ) )
              ) AS distance', [$latitude, $longitude, $latitude]);

            $restos = DB::table( DB::raw("({$distances->toSql()}) as restodistance") )
            ->mergeBindings($distances->getQuery())
            ->whereRaw("distance < ? ", [$radius])
            ->orderBy('distance')
            ->get();
            
        return $restos;
    }

    
    public function GetGeocodingSearchResults($address) {
        $address = urlencode($address); //Url encode since it was provided by user
        $url = "http://maps.google.com/maps/api/geocode/xml?address={$address}&sensor=false";

        // Retrieve the XML file
        $results = file_get_contents($url);
        $xml = new \DOMDocument();//backslash to indicate global namespace
        $xml->loadXML($results);

        // traverse the DOMDocument or use XPath to find the longitude/latitude pairs
        $xpath = new \DOMXpath($xml);
        $status = $xpath->query("//status")->item(0)->nodeValue;
        if($status === "OK"){
            $lat = $xpath->query("//geometry/location/lat")->item(0)->nodeValue;
            $lng = $xpath->query("//geometry/location/lng")->item(0)->nodeValue;
            $pairs = ['status' => $status, 'latitude' => $lat, 'longitude' => $lng];
        }
        else {
            $pairs = ['status' => $status, 'latitude' => '0.0', 'longitude' => '0.0'];
            // Don't add restaurant?
        }
        
        return $pairs;
    }
    
    public function GetRestaurantRating($restos){
        // TODO
    }
    
}