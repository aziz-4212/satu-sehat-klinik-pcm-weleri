<?php

namespace App\Services;
use App\Services\Config;
class Location
{
    public function __construct()
    {
        $this->config = new Config;
        // $this->token = $this->config->auth()->access_token; //jika tidak menggunakan cache
        $this->token = $this->config->getAccessToken(); //jika menggunakan cache
        $this->url = $this->config->url();
        $this->organization_id = $this->config->organization_id()->id;
    }

    public function search_part_of($part_of)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url->base_url.'/Location?organization='.$part_of,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$this->token
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $response = json_decode($response);
        return $response;
    }

    public function create($mapping_location)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url->base_url.'/Location',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "resourceType": "Location",
                "identifier": [
                    {
                        "system": "http://sys-ids.kemkes.go.id/location/'.$this->organization_id.'",
                        "value": "'.$mapping_location->koders.'"
                    }
                ],
                "status": "active",
                "name": "'.$mapping_location->namasatusehat.'",
                "description": "'.$mapping_location->deskripsi.'",
                "mode": "instance",
                "telecom": [
                    {
                        "system": "phone",
                        "value": "(0294) 641870",
                        "use": "work"
                    },
                    {
                        "system": "fax",
                        "value": "0294-644150",
                        "use": "work"
                    },
                    {
                        "system": "email",
                        "value": "rsimuhammadiyah2kendal@gmail.com"
                    },
                    {
                        "system": "url",
                        "value": "https://www.rsikendal.com/",
                        "use": "work"
                    }
                ],
                "address": {
                    "use": "work",
                    "line": [
                        "Desa Pagersari,Patean,Kendal"
                    ],
                    "city": "Kendal",
                    "postalCode": "51364",
                    "country": "ID",
                    "extension": [
                        {
                            "url": "https://fhir.kemkes.go.id/r4/StructureDefinition/administrativeCode",
                            "extension": [
                                {
                                    "url": "province",
                                    "valueCode": "33"
                                },
                                {
                                    "url": "city",
                                    "valueCode": "3324"
                                },
                                {
                                    "url": "district",
                                    "valueCode": "332404"
                                },
                                {
                                    "url": "village",
                                    "valueCode": "3324042005"
                                },
                                {
                                    "url": "rt",
                                    "valueCode": "1"
                                },
                                {
                                    "url": "rw",
                                    "valueCode": "1"
                                }
                            ]
                        }
                    ]
                },
                "physicalType": {
                    "coding": [
                        {
                            "system": "http://terminology.hl7.org/CodeSystem/location-physical-type",
                            "code": "ro",
                            "display": "Room"
                        }
                    ]
                },
                "position": {
                    "longitude": -6.968222882227007,
                    "latitude": 110.09124337838192,
                    "altitude": 0
                },
                "managingOrganization": {
                    "reference": "Organization/'.$this->organization_id.'"
                }
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer '.$this->token
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $response = json_decode($response);
        return $response;
    }
}
