<?php

namespace App\Services;
use App\Services\Config;
class SeviceRequestServices
{
    public function __construct()
    {
        $this->config = new Config;
        // $this->token = $this->config->auth()->access_token; //jika tidak menggunakan cache
        $this->token = $this->config->getAccessToken(); //jika menggunakan cache
        $this->url = $this->config->url();
        $this->organization_id = $this->config->organization_id()->id;
    }


    // ============================Rawat Jalan===================================
        public function create_laboratorium($code_loinc, $desc_loinc, $code_text, $id_patient, $encounter, $encounter_display, $occurrenceDateTime, $requester_reference, $requester_display)
        {
            // dd($code_loinc.'/////'.$desc_loinc.'/////'.$code_text.'/////'.$id_patient.'/////'.$encounter.'/////'.$encounter_display.'/////'.$occurrenceDateTime.'/////'.$requester_reference.'/////'.$requester_display);
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $this->url->base_url.'/ServiceRequest',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '{
                    "resourceType": "ServiceRequest",
                    "identifier": [
                        {
                            "system": "http://sys-ids.kemkes.go.id/servicerequest/'.$this->organization_id.'",
                            "value": "00001"
                        }
                    ],
                    "status": "active",
                    "intent": "original-order",
                    "priority": "routine",
                    "category": [
                        {
                            "coding": [
                                {
                                    "system": "http://snomed.info/sct",
                                    "code": "108252007",
                                    "display": "Laboratory procedure"
                                }
                            ]
                        }
                    ],
                    "code": {
                        "coding": [
                            {
                                "system": "http://loinc.org",
                                "code": "'.$code_loinc.'",
                                "display": "'.$desc_loinc.'"
                            }
                        ],
                        "text": "'.$code_text.'"
                    },
                    "subject": {
                        "reference": "Patient/'.$id_patient.'"
                    },
                    "encounter": {
                        "reference": "Encounter/'.$encounter.'",
                        "display": "'.$encounter_display.'"
                    },
                    "occurrenceDateTime": "'.$occurrenceDateTime.'",
                    "authoredOn": "'.$occurrenceDateTime.'",
                    "requester": {
                        "reference": "Practitioner/'.$requester_reference.'",
                        "display": "'.$requester_display.'"
                    },
                    "performer": [
                        {
                            "reference": "Practitioner/'.$requester_reference.'",
                            "display": "'.$requester_display.'"
                        }
                    ],
                    "reasonCode": [
                        {
                            "text": "Pemeriksaan"
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

        public function rj_rawat_inap_internal($noreg, $id_patient, $name_patient, $encounter, $date, $time, $id_practitioner, $name_practitioner, $kode_diagnosa, $deskripsi_diagnosa, $diagnosa_medis, $location_bed, $location_bed_description)
        {
            // dd('noreg = '.$noreg, 'id_patient = '. $id_patient, 'nama pasien = '. $name_patient, 'encounter = '.$encounter, 'tanggal = '.$date, 'waktu = '. $time, 'id_practitioner = '. $id_practitioner, 'name_practitioner = '. $name_practitioner, 'kode_diagnosa = '.$kode_diagnosa, 'deskripsi_diagnosa = '.$deskripsi_diagnosa, 'diagnosa_medis = '.$diagnosa_medis, 'location_bed = '.$location_bed, 'location_bed_description = '.$location_bed_description);

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL =>  $this->url->base_url.'/ServiceRequest',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>'{
                    "resourceType": "ServiceRequest",
                    "identifier": [
                        {
                            "system": "http://sys-ids.kemkes.go.id/servicerequest/'.$this->organization_id.'",
                            "value": "'.$noreg.'"
                        }
                    ],
                    "status": "active",
                    "intent": "order",
                    "priority": "urgent",
                    "category": [
                        {
                            "coding": [
                                {
                                    "system": "http://snomed.info/sct",
                                    "code": "3457005",
                                    "display": "Patient referral"
                                }
                            ]
                        }
                    ],
                    "code": {
                        "coding": [
                            {
                                "system": "http://snomed.info/sct",
                                "code": "737481003",
                                "display": "Inpatient care management"
                            }
                        ],
                        "text": "Rawat inap pasca prosedur caesar emergensi"
                    },
                    "subject": {
                        "reference": "Patient/'.$id_patient.'",
                        "display": "'.$name_patient.'"
                    },
                    "encounter": {
                        "reference": "Encounter/'.$encounter.'"
                    },
                    "occurrenceDateTime": "'.$date.'T'.$time.'+07:00",
                    "requester": {
                        "reference": "Practitioner/'.$id_practitioner.'",
                        "display": "'.$name_practitioner.'"
                    },
                    "performer": [
                        {
                            "reference": "Practitioner/'.$id_practitioner.'",
                            "display": "'.$name_practitioner.'"
                        }
                    ],
                    "reasonCode": [
                        {
                            "coding": [
                                {
                                    "system": "http://hl7.org/fhir/sid/icd-10",
                                    "code": "'.$kode_diagnosa.'",
                                    "display": "'.$deskripsi_diagnosa.'"
                                }
                            ],
                            "text": "'.$diagnosa_medis.'"
                        }
                    ],
                    "locationReference": [
                        {
                            "reference": "Location/'.$location_bed.'",
                            "display": "'.$location_bed_description.'"
                        }
                    ],
                    "patientInstruction": "Pasien mendapatkan perawatan rawat inap"
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
    // ============================Rawat Jalan===================================
}
