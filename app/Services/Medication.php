<?php

namespace App\Services;
use App\Services\Config;
class Medication
{
    public function __construct()
    {
        $this->config = new Config;
        // $this->token = $this->config->auth()->access_token; //jika tidak menggunakan cache
        $this->token = $this->config->getAccessToken(); //jika menggunakan cache
        $this->url = $this->config->url();
        $this->organization_id = $this->config->organization_id()->id;
    }

    public function search_by_id($id_medication)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url->base_url.'/Medication/'.$id_medication,
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

    public function create_medication($kode_kfa, $deskripsi_kfa, $kode_obat, $display_obat)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $this->url->base_url.'/Medication',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
        "resourceType": "Medication",
        "meta": {
            "profile": [
                "https://fhir.kemkes.go.d/r4/StructureDefinition/Medication"
            ]
        },
        "identifier": [
            {
                "system": "http://sys-ids.kemkes.go.id/medication/'.$this->organization_id.'",
                "use": "official",
                "value": "123456789"
            }
        ],
        "code": {
            "coding": [
                {
                    "system": "http://sys-ids.kemkes.go.id/kfa",
                    "code": "'.$kode_kfa.'",
                    "display": "'.$deskripsi_kfa.'"
                }
            ]
        },
        "status": "active",
        "manufacturer": {
            "reference": "Organization/'.$this->organization_id.'"
        },
        "form": {
            "coding": [
                {
                    "system": "http://terminology.kemkes.go.id/CodeSystem/medication-form",
                    "code": "'.$kode_obat.'",
                    "display": "'.$display_obat.'"
                }
            ]
        },
        "extension": [
            {
                "url": "https://fhir.kemkes.go.id/r4/StructureDefinition/MedicationType",
                "valueCodeableConcept": {
                    "coding": [
                        {
                            "system": "http://terminology.kemkes.go.id/CodeSystem/medication-type",
                            "code": "NC",
                            "display": "Non-compound"
                        }
                    ]
                }
            }
        ]
        }',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Bearer '.$this->token
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $response = json_decode($response);
        // dd($response);
        return $response;

    }
}
