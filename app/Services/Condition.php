<?php

namespace App\Services;
use App\Services\Config;
class Condition
{
    public function __construct()
    {
        $this->config = new Config;
        // $this->token = $this->config->auth()->access_token; //jika tidak menggunakan cache
        $this->token = $this->config->getAccessToken(); //jika menggunakan cache
        $this->url = $this->config->url();
    }

    public function search_by_id($id)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url->base_url.'/Condition/'.$id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer svEeMy6tc8nCgYiHUIaGpDLKSGYP'
            ),
        ));

        $response = curl_exec($curl);
        $response = json_decode($response);
        curl_close($curl);
        return $response;
    }

    public function create_diagnosis($kode_diagnosa, $deskripsi_diagnosa, $mapping_pasien, $mapping_kunjungan_poli)
    {
        $kode_diagnosa = 'R50.9';
        $deskripsi_diagnosa = 'Fever, unspecified';
        $mapping_pasien_kodesatusehat = 'P02162554687';
        $mapping_pasien_namasatusehat = 'IKKE FRANDITA ELISIA';
        $mapping_kunjungan_poli_encounter = 'ed73f579-fce0-456b-8f30-f9af17be308a';
        // dd($kode_diagnosa, $deskripsi_diagnosa, $mapping_pasien_kodesatusehat, $mapping_pasien_namasatusehat, $mapping_kunjungan_poli_encounter);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url->base_url.'/Condition',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "resourceType": "Condition",
                "clinicalStatus": {
                    "coding": [
                        {
                            "system": "http://terminology.hl7.org/CodeSystem/condition-clinical",
                            "code": "active",
                            "display": "Active"
                        }
                    ]
                },
                "category": [
                    {
                        "coding": [
                            {
                            "system": "http://terminology.hl7.org/CodeSystem/condition-category",
                            "code": "encounter-diagnosis",
                            "display": "Encounter Diagnosis"
                            }
                        ]
                    }
                ],
                "code": {
                    "coding": [
                        {
                            "system": "http://hl7.org/fhir/sid/icd-10",
                            "code": "'.$kode_diagnosa.'",
                            "display": "'.$deskripsi_diagnosa.'"
                        }
                    ]
                },
                "subject": {
                    "reference": "Patient/'.$mapping_pasien_kodesatusehat.'",
                    "display": "'.$mapping_pasien_namasatusehat.'"
                },
                "encounter": {
                    "reference": "Encounter/'.$mapping_kunjungan_poli_encounter.'",
                    "display": "Kunjungan Rawat Jalan"
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

    public function create_meninggalkan_faskes($mapping_pasien, $diagnosis)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url->base_url.'/Condition',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "resourceType": "Condition",
                "clinicalStatus": {
                    "coding": [
                        {
                            "system": "http://terminology.hl7.org/CodeSystem/condition-clinical",
                            "code": "active",
                            "display": "Active"
                        }
                    ]
                },
                "category": [
                    {
                        "coding": [
                            {
                                "system": "http://terminology.hl7.org/CodeSystem/condition-category",
                                "code": "encounter-diagnosis",
                                "display": "Encounter Diagnosis"
                            }
                        ]
                    }
                ],
                "code": {
                    "coding": [
                        {
                            "system": "http://snomed.info/sct",
                            "code": "359746009",
                            "display": "Patient\'s condition stable"
                        }
                    ]
                },
                "subject": {
                    "reference": "Patient/'.$mapping_pasien->kodesatusehat.'",
                    "display": "'.$mapping_pasien->namasatusehat.'"
                },
                "encounter": {
                    "reference": "Encounter/'.$diagnosis->encounter.'",
                    "display": "Kunjungan Rawat Jalan"
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

    public function RI_diagnosis($kode_diagnosa, $deskripsi_diagnosa, $id_patient, $name_patient, $encounter, $date, $time)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url->base_url.'/Condition',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "resourceType": "Condition",
                "clinicalStatus": {
                    "coding": [
                        {
                            "system": "http://terminology.hl7.org/CodeSystem/condition-clinical",
                            "code": "active",
                            "display": "Active"
                        }
                    ]
                },
                "category": [
                    {
                        "coding": [
                            {
                                "system": "http://terminology.hl7.org/CodeSystem/condition-category",
                                "code": "encounter-diagnosis",
                                "display": "Encounter Diagnosis"
                            }
                        ]
                    }
                ],
                "code": {
                    "coding": [
                        {
                            "system": "http://hl7.org/fhir/sid/icd-10",
                            "code": "'.$kode_diagnosa.'",
                            "display": "'.$deskripsi_diagnosa.'"
                        }
                    ]
                },
                "subject": {
                    "reference": "Patient/'.$id_patient.'",
                    "display": "'.$name_patient.'"
                },
                "encounter": {
                    "reference": "Encounter/'.$encounter.'"
                },
                "onsetDateTime": "'.$date.'T'.$time.'+07:00",
                "recordedDate": "'.$date.'T'.$time.'+07:00",
                "note": [
                    {
                        "text": "'.$deskripsi_diagnosa.'"
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

    public function IGD_diagnosis_awal_masuk($kode_diagnosa, $deskripsi_diagnosa, $kode_snomed, $deskripsi_snomed, $id_patient, $name_patient, $encounter, $date, $time)
    {
        // dd($kode_diagnosa, $deskripsi_diagnosa, $kode_snomed, $deskripsi_snomed, $id_patient, $name_patient, $encounter, $date, $time);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url->base_url.'/Condition',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "resourceType": "Condition",
                "clinicalStatus": {
                    "coding": [
                        {
                            "system": "http://terminology.hl7.org/CodeSystem/condition-clinical",
                            "code": "active",
                            "display": "Active"
                        }
                    ]
                },
                "category": [
                    {
                        "coding": [
                            {
                                "system": "http://terminology.hl7.org/CodeSystem/condition-category",
                                "code": "encounter-diagnosis",
                                "display": "Encounter Diagnosis"
                            }
                        ]
                    }
                ],
                "code": {
                    "coding": [
                        {
                            "system": "http://hl7.org/fhir/sid/icd-10",
                            "code": "'.$kode_diagnosa.'",
                            "display": "'.$deskripsi_diagnosa.'"
                        },
                        {
                            "system": "http://snomed.info/sct",
                            "code": "'.$kode_snomed.'",
                            "display": "'.$deskripsi_snomed.'"
                        }
                    ],
                    "text": "'.$deskripsi_diagnosa.'"
                },
                "subject": {
                    "reference": "Patient/'.$id_patient.'",
                    "display": "'.$name_patient.'"
                },
                "encounter": {
                    "reference": "Encounter/'.$encounter.'"
                },
                "onsetDateTime": "'.$date.'T'.$time.'+07:00",
                "recordedDate": "'.$date.'T'.$time.'+07:00",
                "stage": [
                    {
                        "assessment": [
                            {
                                "reference": "ClinicalImpression/{{Rasional_Klinis}}"
                            }
                        ]
                    }
                ]
            }',
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
    }
}
