<?php

namespace App\Services;
use App\Services\Config;
class Careplan
{
    public function __construct()
    {
        $this->config = new Config;
        // $this->token = $this->config->auth()->access_token; //jika tidak menggunakan cache
        $this->token = $this->config->getAccessToken(); //jika menggunakan cache
        $this->url = $this->config->url();
        $this->organization_id = $this->config->organization_id()->id;
    }

    public function create_careplane_rencana_rawat_pasien($id_patient, $id_patient_display, $encounter, $tanggal, $id_dokter_satu_sehat, $display_dokter_satu_sehat)
    {
        // dd($id_patient, $id_patient_display, $encounter, $tanggal, $id_dokter_satu_sehat, $display_dokter_satu_sehat);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url->base_url.'/CarePlan',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "resourceType": "CarePlan",
                "status": "active",
                "intent": "plan",
                "title": "Rencana Rawat Pasien",
                "description": "Rencana Rawat Pasien",
                "category": [
                    {
                        "coding": [
                            {
                                "system": "http://snomed.info/sct",
                                "code": "736271009",
                                "display": "Outpatient care plan"
                            }
                        ]
                    }
                ],
                "subject": {
                    "reference": "Patient/'.$id_patient.'",
                    "display": "'.$id_patient_display.'"
                },
                "encounter": {
                    "reference": "Encounter/'.$encounter.'"
                },
                "created": "'.$tanggal.'",
                "author": {
                    "reference": "Practitioner/'.$id_dokter_satu_sehat.'",
                    "display": "'.$display_dokter_satu_sehat.'"
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

    public function RI_rencana_rawat_pasien($deskripsi, $id_patient, $id_patient_display, $encounter, $id_dokter_satu_sehat, $date, $time){
        // dd($deskripsi, $id_patient, $id_patient_display, $encounter, $id_dokter_satu_sehat, $date, $time);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url->base_url.'/CarePlan',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "resourceType": "CarePlan",
                "title": "Rencana Rawat Pasien",
                "status": "active",
                "category": [
                    {
                        "coding": [
                            {
                                "system": "http://snomed.info/sct",
                                "code": "736353004",
                                "display": " Inpatient care plan"
                            }
                        ]
                    }
                ],
                "intent": "plan",
                "description": "'.$deskripsi.'",
                "subject": {
                    "reference": "Patient/'.$id_patient.'",
                    "display": "'.$id_patient_display.'"
                },
                "encounter": {
                    "reference": "Encounter/'.$encounter.'"
                },
                "created": "'.$date.'T'.$time.'+07:00",
                "author": {
                    "reference": "Practitioner/'.$id_dokter_satu_sehat.'"
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
