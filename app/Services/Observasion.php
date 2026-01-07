<?php

namespace App\Services;
use App\Services\Config;
class Observasion
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
            CURLOPT_URL => $this->url->base_url.'/Observation/'.$id,
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

    public function create($mapping_pasien, $diagnosis, $id_practitioner, $date, $nadi) //denyut jantung
    {
        // dd($mapping_pasien." ".$diagnosis." ".$id_practitioner." ".$date." ".$nadi);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url->base_url.'/Observation',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "resourceType": "Observation",
                "status": "final",
                "category": [
                    {
                        "coding": [
                            {
                                "system": "http://terminology.hl7.org/CodeSystem/observation-category",
                                "code": "vital-signs",
                                "display": "Vital Signs"
                            }
                        ]
                    }
                ],
                "code": {
                    "coding": [
                        {
                            "system": "http://loinc.org",
                            "code": "8867-4",
                            "display": "Heart rate"
                        }
                    ]
                },
                "subject": {
                    "reference": "Patient/'.$mapping_pasien->kodesatusehat.'"
                },
                "performer": [
                    {
                        "reference": "Practitioner/'.$id_practitioner.'"
                    }
                ],
                "encounter": {
                    "reference": "Encounter/'.$diagnosis->encounter.'",
                    "display": "Pemeriksaan Fisik Nadi"
                },
                "effectiveDateTime": "'.$date.'T01:10:00+00:00",
                "issued": "'.$date.'T01:10:00+00:00",
                "valueQuantity": {
                    "value": '.$nadi.',
                    "unit": "beats/minute",
                    "system": "http://unitsofmeasure.org",
                    "code": "/min"
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

    public function pernapasan($mapping_pasien, $diagnosis, $id_practitioner, $date, $rr) //pernapasan
    {
        // dd($mapping_pasien." ".$diagnosis." ".$id_practitioner." ".$date." ".$nadi);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url->base_url.'/Observation',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "resourceType": "Observation",
                "status": "final",
                "category": [
                    {
                        "coding": [
                            {
                                "system": "http://terminology.hl7.org/CodeSystem/observation-category",
                                "code": "vital-signs",
                                "display": "Vital Signs"
                            }
                        ]
                    }
                ],
                "code": {
                    "coding": [
                        {
                            "system": "http://loinc.org",
                            "code": "9279-1",
                            "display": "Respiratory rate"
                        }
                    ]
                },
                "subject": {
                    "reference": "Patient/'.$mapping_pasien->kodesatusehat.'"
                },
                "performer": [
                    {
                        "reference": "Practitioner/'.$id_practitioner.'"
                    }
                ],
                "encounter": {
                    "reference": "Encounter/'.$diagnosis->encounter.'",
                    "display": "Pemeriksaan Fisik Pernapasan"
                },
                "effectiveDateTime": "'.$date.'T01:10:00+00:00",
                "issued": "'.$date.'T01:10:00+00:00",
                "valueQuantity": {
                    "value": '.$rr.',
                    "unit": "breaths/min",
                    "system": "http://unitsofmeasure.org",
                    "code": "/min"
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

    public function tekanan_darah_sistole($mapping_pasien, $diagnosis, $id_practitioner, $date, $nilai_sistole) //Tekanan Darah Sistole
    {
        // dd($mapping_pasien." ".$diagnosis." ".$id_practitioner." ".$date." ".$nadi);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url->base_url.'/Observation',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "resourceType": "Observation",
                "status": "final",
                "category": [
                    {
                        "coding": [
                            {
                                "system": "http://terminology.hl7.org/CodeSystem/observation-category",
                                "code": "vital-signs",
                                "display": "Vital Signs"
                            }
                        ]
                    }
                ],
                "code": {
                    "coding": [
                        {
                            "system": "http://loinc.org",
                            "code": "8480-6",
                            "display": "Systolic blood pressure"
                        }
                    ]
                },
                "subject": {
                    "reference": "Patient/'.$mapping_pasien->kodesatusehat.'"
                },
                "performer": [
                    {
                        "reference": "Practitioner/'.$id_practitioner.'"
                    }
                ],
                "encounter": {
                    "reference": "Encounter/'.$diagnosis->encounter.'",
                    "display": "Pemeriksaan Tekanan Darah Sistole"
                },
                "effectiveDateTime": "'.$date.'T01:10:00+00:00",
                "issued": "'.$date.'T01:10:00+00:00",
                "valueQuantity": {
                    "value": '.$nilai_sistole.',
                    "unit": "mm[Hg]",
                    "system": "http://unitsofmeasure.org",
                    "code": "mm[Hg]"
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

    public function tekanan_darah_diastole($mapping_pasien, $diagnosis, $id_practitioner, $date, $nilai_diastole) //Tekanan Darah Diastole
    {
        // dd($mapping_pasien." ".$diagnosis." ".$id_practitioner." ".$date." ".$nadi);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url->base_url.'/Observation',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "resourceType": "Observation",
                "status": "final",
                "category": [
                    {
                        "coding": [
                            {
                                "system": "http://terminology.hl7.org/CodeSystem/observation-category",
                                "code": "vital-signs",
                                "display": "Vital Signs"
                            }
                        ]
                    }
                ],
                "code": {
                    "coding": [
                        {
                            "system": "http://loinc.org",
                            "code": "8462-4",
                            "display": "Diastolic blood pressure"
                        }
                    ]
                },
                "subject": {
                    "reference": "Patient/'.$mapping_pasien->kodesatusehat.'"
                },
                "performer": [
                    {
                        "reference": "Practitioner/'.$id_practitioner.'"
                    }
                ],
                "encounter": {
                    "reference": "Encounter/'.$diagnosis->encounter.'",
                    "display": "Pemeriksaan Tekanan Darah Diastole"
                },
                "effectiveDateTime": "'.$date.'T01:10:00+00:00",
                "issued": "'.$date.'T01:10:00+00:00",
                "valueQuantity": {
                    "value": '.$nilai_diastole.',
                    "unit": "mm[Hg]",
                    "system": "http://unitsofmeasure.org",
                    "code": "mm[Hg]"
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

    public function suhu_tubuh($mapping_pasien, $diagnosis, $id_practitioner, $date, $suhu_tubuh) //Suhu Tubuh
    {
        // dd($mapping_pasien." ".$diagnosis." ".$id_practitioner." ".$date." ".$nadi);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url->base_url.'/Observation',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "resourceType": "Observation",
                "status": "final",
                "category": [
                    {
                        "coding": [
                            {
                                "system": "http://terminology.hl7.org/CodeSystem/observation-category",
                                "code": "vital-signs",
                                "display": "Vital Signs"
                            }
                        ]
                    }
                ],
                "code": {
                    "coding": [
                        {
                            "system": "http://loinc.org",
                            "code": "8310-5",
                            "display": "Body temperature"
                        }
                    ]
                },
                "subject": {
                    "reference": "Patient/'.$mapping_pasien->kodesatusehat.'"
                },
                "performer": [
                    {
                        "reference": "Practitioner/'.$id_practitioner.'"
                    }
                ],
                "encounter": {
                    "reference": "Encounter/'.$diagnosis->encounter.'",
                    "display": "Pemeriksaan Fisik Nadi"
                },
                "effectiveDateTime": "'.$date.'T01:10:00+00:00",
                "issued": "'.$date.'T01:10:00+00:00",
                "valueQuantity": {
                    "value": '.$suhu_tubuh.',
                    "unit": "C",
                    "system": "http://unitsofmeasure.org",
                    "code": "Cel"
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

    public function IGD_sarana_transportasi_kedatangan($id_patient, $id_patient_display, $encounter, $id_practitioner, $date, $time, $system, $code, $display)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url->base_url.'/Observation',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "resourceType": "Observation",
                "status": "final",
                "category": [
                    {
                        "coding": [
                            {
                                "system": "http://terminology.hl7.org/CodeSystem/observation-category",
                                "code": "survey",
                                "display": "Survey"
                            }
                        ]
                    }
                ],
                "code": {
                    "coding": [
                        {
                            "system": "http://loinc.org",
                            "code": "74286-6",
                            "display": "Transport mode to hospital"
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
                "effectiveDateTime": "'.$date.'T'.$time.'+07:00",
                "issued": "'.$date.'T'.$time.'+07:00",
                "performer": [
                    {
                        "reference": "Practitioner/'.$id_practitioner.'"
                    }
                ],
                "valueCodeableConcept": {
                    "coding": [
                        {
                            "system": "'.$system.'",
                            "code": "'.$code.'",
                            "display": "'.$display.'"
                        }
                    ]
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
