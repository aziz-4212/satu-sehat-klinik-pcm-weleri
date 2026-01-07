<?php

namespace App\Services;
use App\Services\Config;
class Procedure
{
    public function __construct()
    {
        $this->config = new Config;
        // $this->token = $this->config->auth()->access_token; //jika tidak menggunakan cache
        $this->token = $this->config->getAccessToken(); //jika menggunakan cache
        $this->url = $this->config->url();
    }

    public function create_procedure($id_patient, $id_patient_display, $encounter, $practitioner, $practitioner_display)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url->base_url.'/Procedure',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                "resourceType": "Procedure",
                "status": "completed",
                "category": {
                    "coding": [
                        {
                            "system": "http://snomed.info/sct",
                            "code": "103693007",
                            "display": "Diagnostic procedure"
                        }
                    ],
                    "text": "Diagnostic procedure"
                },
                "code": {
                    "coding": [
                        {
                            "system": "http://hl7.org/fhir/sid/icd-9-cm",
                            "code": "87.44",
                            "display": "Routine chest x-ray, so described"
                        }
                    ]
                },
                "subject": {
                    "reference": "Patient/'.$id_patient.'",
                    "display": "'.$id_patient_display.'"
                },
                "encounter": {
                    "reference": "Encounter/'.$encounter.'",
                    "display": "Tindakan Rontgen Thorax"
                },
                "performedPeriod": {
                    "start": "2022-06-14T13:31:00+01:00",
                    "end": "2022-06-14T14:27:00+01:00"
                },
                "performer": [
                    {
                        "actor": {
                            "reference": "Practitioner/'.$practitioner.'",
                            "display": "'.$practitioner_display.'"
                        }
                    }
                ],
                "reasonCode": [
                    {
                        "coding": [
                            {
                                "system": "http://hl7.org/fhir/sid/icd-10",
                                "code": "A15.0",
                                "display": "Tuberculosis of lung, confirmed by sputum microscopy with or without culture"
                            }
                        ]
                    }
                ],
                "bodySite": [
                    {
                        "coding": [
                            {
                                "system": "http://snomed.info/sct",
                                "code": "302551006",
                                "display": "Entire Thorax"
                            }
                        ]
                    }
                ],
                "note": [
                    {
                        "text": "Rontgen thorax melihat perluasan infiltrat dan kavitas."
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
        return $response;
        dd($response);

        return response()->json($response);
    }

    public function create_procedure_edukasi_nutrisi($id_patient, $id_patient_display, $encounter, $practitioner, $practitioner_display, $tanggal_start, $tanggal_end)
    {
        // dd($id_patient, $id_patient_display, $encounter, $practitioner, $practitioner_display, $tanggal_start, $tanggal_end);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url->base_url.'/Procedure',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "resourceType": "Procedure",
                "status": "completed",
                "category": {
                    "coding": [
                        {
                            "system": "http://snomed.info/sct",
                            "code": "409073007",
                            "display": "Education"
                        }
                    ]
                },
                "code": {
                    "coding": [
                        {
                            "system": "http://snomed.info/sct",
                            "code": "61310001",
                            "display": "Nutrition education"
                        }
                    ]
                },
                "subject": {
                    "reference": "Patient/'.$id_patient.'",
                    "display": "'.$id_patient_display.'"
                },
                "encounter": {
                    "reference": "Encounter/'.$encounter.'"
                },
                "performedPeriod": {
                    "start": "'.$tanggal_start.'",
                    "end": "'.$tanggal_end.'"
                },
                "performer": [
                    {
                        "actor": {
                            "reference": "Practitioner/'.$practitioner.'",
                            "display": "'.$practitioner_display.'"
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
        return $response;
    }
}
