<?php

namespace App\Services;
use App\Services\Config;
class Encounter
{
    public function __construct()
    {
        $this->config = new Config;
        // $this->token = $this->config->auth()->access_token; //jika tidak menggunakan cache
        $this->token = $this->config->getAccessToken(); //jika menggunakan cache
        $this->url = $this->config->url();
        $this->organization_id = $this->config->organization_id()->id;
    }

    public function create($id_patient, $name_patient, $id_practitioner, $name_practitioner, $date, $id_location, $name_location)
    {
        // dd($id_patient, $name_patient, $id_practitioner, $name_practitioner, $date, $id_location, $name_location);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url->base_url.'/Encounter',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "resourceType": "Encounter",
                "status": "arrived",
                "class": {
                    "system": "http://terminology.hl7.org/CodeSystem/v3-ActCode",
                    "code": "AMB",
                    "display": "ambulatory"
                },
                "subject": {
                    "reference": "Patient/'.$id_patient.'",
                    "display": "'.$name_patient.'"
                },
                "participant": [
                    {
                        "type": [
                            {
                                "coding": [
                                    {
                                        "system": "http://terminology.hl7.org/CodeSystem/v3-ParticipationType",
                                        "code": "ATND",
                                        "display": "attender"
                                    }
                                ]
                            }
                        ],
                        "individual": {
                            "reference": "Practitioner/'.$id_practitioner.'",
                            "display": "'.$name_practitioner.'"
                        }
                    }
                ],
                "period": {
                    "start": "'.$date.'T01:00:00+00:00"
                },
                "location": [
                    {
                        "location": {
                            "reference": "Location/'.$id_location.'",
                            "display": "'.$name_location.'"
                        }
                    }
                ],
                "statusHistory": [
                    {
                        "status": "arrived",
                        "period": {
                            "start": "'.$date.'T01:10:00+00:00"
                        }
                    }
                ],
                "serviceProvider": {
                    "reference": "Organization/'.$this->organization_id.'"
                },
                "identifier": [
                    {
                        "system": "http://sys-ids.kemkes.go.id/encounter/'.$this->organization_id.'",
                        "value": "'.$this->organization_id.'"
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

    public function ri_masuk_ruang($encounter_id, $id_patient, $name_patient, $id_practitioner, $name_practitioner, $datetime, $datetime_end, $id_location, $name_location)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url->base_url.'/Encounter/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS =>'{
                "resourceType": "Encounter",
                "id": "'.$encounter_id.'",
                "identifier": [
                    {
                        "system": "http://sys-ids.kemkes.go.id/encounter/'.$this->organization_id.'",
                        "value": "'.$this->organization_id.'"
                    }
                ],
                "status": "in-progress",
                "class": {
                    "system": "http://terminology.hl7.org/CodeSystem/v3-ActCode",
                    "code": "AMB",
                    "display": "ambulatory"
                },
                "subject": {
                    "reference": "Patient/'.$id_patient.'",
                    "display": "'.$name_patient.'"
                },
                "participant": [
                    {
                        "type": [
                            {
                                "coding": [
                                    {
                                        "system": "http://terminology.hl7.org/CodeSystem/v3-ParticipationType",
                                        "code": "ATND",
                                        "display": "attender"
                                    }
                                ]
                            }
                        ],
                        "individual": {
                            "reference": "Practitioner/10013576199",
                            "display": "Gusti Reka Kusuma"
                        }
                    }
                ],
                "period": {
                    "start": "'.$datetime.'"
                },
                "location": [
                    {
                        "location": {
                            "reference": "Location/'.$id_location.'",
                            "display": "'.$name_location.'"
                        },
                        "period": {
                            "start": "'.$datetime.'"
                        },
                        "extension": [
                            {
                                "url": "https://fhir.kemkes.go.id/r4/StructureDefinition/ServiceClass",
                                "extension": [
                                    {
                                        "url": "value",
                                        "valueCodeableConcept": {
                                            "coding": [
                                                {
                                                    "system": "http://terminology.kemkes.go.id/CodeSystem/locationServiceClass-Outpatient",
                                                    "code": "reguler",
                                                    "display": "Kelas Reguler"
                                                }
                                            ]
                                        }
                                    },
                                    {
                                        "url": "upgradeClassIndicator",
                                        "valueCodeableConcept": {
                                            "coding": [
                                                {
                                                    "system": "http://terminology.kemkes.go.id/CodeSystem/locationUpgradeClass",
                                                    "code": "kelas-tetap",
                                                    "display": "Kelas Tetap Perawatan"
                                                }
                                            ]
                                        }
                                    }
                                ]
                            }
                        ]
                    }
                ],
                "statusHistory": [
                    {
                        "status": "arrived",
                        "period": {
                            "start": "'.$datetime.'",
                            "end": "'.$datetime_end.'"
                        }
                    },
                    {
                        "status": "in-progress",
                        "period": {
                            "start": "'.$datetime_end.'"
                        }
                    }
                ],
                "serviceProvider": {
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

    public function create_masuk_kunjungan_rawat_inap($noreg, $id_patient, $name_patient, $id_practitioner, $name_practitioner, $date, $time, $id_location, $name_location, $kelas, $id_service_request)
    {
        // dd($noreg, $id_patient, $name_patient, $id_practitioner, $name_practitioner, $date, $time, $id_location, $name_location, $kelas);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url->base_url.'/Encounter',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "resourceType": "Encounter",
                "identifier": [
                    {
                        "system": "http://sys-ids.kemkes.go.id/encounter/'.$this->organization_id.'",
                        "value": "'.$noreg.'"
                    }
                ],
                "status": "in-progress",
                "class": {
                    "system": "http://terminology.hl7.org/CodeSystem/v3-ActCode",
                    "code": "IMP",
                    "display": "inpatient encounter"
                },
                "subject": {
                    "reference": "Patient/'.$id_patient.'",
                    "display": "'.$name_patient.'"
                },
                "participant": [
                    {
                        "type": [
                            {
                                "coding": [
                                    {
                                        "system": "http://terminology.hl7.org/CodeSystem/v3-ParticipationType",
                                        "code": "ATND",
                                        "display": "attender"
                                    }
                                ]
                            }
                        ],
                        "individual": {
                            "reference": "Practitioner/10013576199",
                            "display": "Gusti Reka Kusuma"
                        }
                    }
                ],
                "period": {
                    "start": "'.$date.'T'.$time.'+07:00"
                },
                "location": [
                    {
                        "location": {
                            "reference": "Location/'.$id_location.'",
                            "display": "'.$name_location.'"
                        },
                        "extension": [
                            {
                                "url": "https://fhir.kemkes.go.id/r4/StructureDefinition/ServiceClass",
                                "extension": [
                                    {
                                        "url": "value",
                                        "valueCodeableConcept": {
                                            "coding": [
                                                {
                                                    "system": "http://terminology.kemkes.go.id/CodeSystem/locationServiceClass-Inpatient",
                                                    "code": "'.$kelas.'",
                                                    "display": "Kelas '.$kelas.'"
                                                }
                                            ]
                                        }
                                    },
                                    {
                                        "url": "upgradeClassIndicator",
                                        "valueCodeableConcept": {
                                            "coding": [
                                                {
                                                    "system": "http://terminology.kemkes.go.id/CodeSystem/locationUpgradeClass",
                                                    "code": "kelas-tetap",
                                                    "display": "Kelas Tetap Perawatan"
                                                }
                                            ]
                                        }
                                    }
                                ]
                            }
                        ]
                    }
                ],
                "statusHistory": [
                    {
                        "status": "in-progress",
                        "period": {
                            "start": "'.$date.'T'.$time.'+07:00"
                        }
                    }
                ],
                "serviceProvider": {
                    "reference": "Organization/'.$this->organization_id.'"
                },
                "basedOn": [
                    {
                        "reference": "ServiceRequest/'.$id_service_request.'"
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

    public function create_masuk_kunjungan_igd($noreg, $id_patient, $name_patient, $id_practitioner, $name_practitioner, $date, $time, $id_location, $name_location)
    {
        // dd($noreg, $id_patient, $name_patient, $id_practitioner, $name_practitioner, $date, $time, $id_location, $name_location);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url->base_url.'/Encounter',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "resourceType": "Encounter",
                "identifier": [
                    {
                        "system": "http://sys-ids.kemkes.go.id/encounter/'.$this->organization_id.'",
                        "value": "'.$noreg.'"
                    }
                ],
                "status": "arrived",
                "class": {
                    "system": "http://terminology.hl7.org/CodeSystem/v3-ActCode",
                    "code": "EMER",
                    "display": "emergency"
                },
                "subject": {
                    "reference": "Patient/'.$id_patient.'",
                    "display": "'.$name_patient.'"
                },
                "participant": [
                    {
                        "type": [
                            {
                                "coding": [
                                    {
                                        "system": "http://terminology.hl7.org/CodeSystem/v3-ParticipationType",
                                        "code": "ATND",
                                        "display": "attender"
                                    }
                                ]
                            }
                        ],
                        "individual": {
                            "reference": "Practitioner/10013576199",
                            "display": "Gusti Reka Kusuma"
                        }
                    }
                ],
                "period": {
                    "start": "'.$date.'T'.$time.'+07:00"
                },
                "location": [
                    {
                        "location": {
                            "reference": "Location/'.$id_location.'",
                            "display": "'.$name_location.'"
                        },
                        "period": {
                            "start": "'.$date.'T'.$time.'+07:00"
                        },
                        "extension": [
                            {
                                "url": "https://fhir.kemkes.go.id/r4/StructureDefinition/ServiceClass",
                                "extension": [
                                    {
                                        "url": "value",
                                        "valueCodeableConcept": {
                                            "coding": [
                                                {
                                                    "system": "http://terminology.kemkes.go.id/CodeSystem/locationServiceClass-Outpatient",
                                                    "code": "reguler",
                                                    "display": "Kelas Reguler"
                                                }
                                            ]
                                        }
                                    },
                                    {
                                        "url": "upgradeClassIndicator",
                                        "valueCodeableConcept": {
                                            "coding": [
                                                {
                                                    "system": "http://terminology.kemkes.go.id/CodeSystem/locationUpgradeClass",
                                                    "code": "kelas-tetap",
                                                    "display": "Kelas Tetap Perawatan"
                                                }
                                            ]
                                        }
                                    }
                                ]
                            }
                        ]
                    }
                ],
                "statusHistory": [
                    {
                        "status": "arrived",
                        "period": {
                            "start": "'.$date.'T'.$time.'+07:00"
                        }
                    }
                ],
                "serviceProvider": {
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
