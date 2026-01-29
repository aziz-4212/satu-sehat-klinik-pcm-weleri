<?php

namespace App\Services;
use App\Services\Config;
class RawatJalan
{
    public function __construct()
    {
        $this->config = new Config;
        // $this->token = $this->config->auth()->access_token; //jika tidak menggunakan cache
        $this->token = $this->config->getAccessToken(); //jika menggunakan cache
        $this->url = $this->config->url();
        $this->organization_id = $this->config->organization_id()->id;
    }
    // ==================================00. Membuat Struktur Organisasi dan Lokasi==================================
        public function Create_UKP_Kefarmasian_Laboratorium($nama, $telepon, $email, $alamat)
        {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $this->url->base_url.'/Organization',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>'{
                    "resourceType": "Organization",
                    "active": true,
                    "identifier": [
                        {
                            "use": "official",
                            "system": "http://sys-ids.kemkes.go.id/organization/'.$this->organization_id.'",
                            "value": "SS-UKP"
                        }
                    ],
                    "type": [
                        {
                            "coding": [
                                {
                                    "system": "http://terminology.hl7.org/CodeSystem/organization-type",
                                    "code": "team",
                                    "display": "Organizational team"
                                }
                            ]
                        }
                    ],
                    "name": "'.$nama.'",
                    "telecom": [
                        {
                            "system": "phone",
                            "value": "'.$telepon.'",
                            "use": "work"
                        },
                        {
                            "system": "email",
                            "value": "'.$email.'",
                            "use": "work"
                        },
                        {
                            "system": "url",
                            "value": "https://rsikendal.com",
                            "use": "work"
                        }
                    ],
                    "address": [
                        {
                            "use": "work",
                            "type": "both",
                            "line": [
                                "'.$alamat.'"
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
                                        }
                                    ]
                                }
                            ]
                        }
                    ],
                    "partOf": {
                        "reference": "Organization/'.$this->organization_id.'",
                        "display": "KLINIK PRATAMA PCM WELERI"
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

        public function ambil_data_organisasi_rumah_sakit()
        {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $this->url->base_url.'/Organization',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>'{
                    "resourceType": "Organization",
                    "active": true,
                    "identifier": [
                        {
                            "use": "official",
                            "system": "http://sys-ids.kemkes.go.id/organization/'.$this->organization_id.'",
                            "value": "KLINIK PRATAMA PCM WELERI"
                        }
                    ],
                    "type": [
                        {
                            "coding": [
                                {
                                    "system": "http://terminology.hl7.org/CodeSystem/organization-type",
                                    "code": "team",
                                    "display": "Organizational team"
                                }
                            ]
                        }
                    ],
                    "name": "KLINIK PRATAMA PCM WELERI",
                    "telecom": [
                        {
                            "system": "phone",
                            "value": "082324220707",
                            "use": "work"
                        },
                        {
                            "system": "email",
                            "value": "rsimuhammadiyah2kendal@gmail.com",
                            "use": "work"
                        }
                    ],
                    "address": [
                        {
                            "use": "work",
                            "type": "both",
                            "line": [
                                "Paturen I, Pagersari, Patean, Kendal, Jawa Tengah 51364"
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
                                        }
                                    ]
                                }
                            ]
                        }
                    ],
                    "partOf": {
                        "reference": "Organization/'.$this->organization_id.'",
                        "display": "KLINIK PRATAMA PCM WELERI"
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

        public function ambil_data_organisasi_divisi($nama_divisi, $sub_org_id, $sub_org_nama)
        {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api-satusehat.kemkes.go.id/fhir-r4/v1/Organization',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>'{
                    "resourceType": "Organization",
                    "active": true,
                    "identifier": [
                        {
                            "use": "official",
                            "system": "http://sys-ids.kemkes.go.id/organization/'.$this->organization_id.'",
                            "value": "'.$nama_divisi.'"
                        }
                    ],
                    "type": [
                        {
                            "coding": [
                                {
                                    "system": "http://terminology.hl7.org/CodeSystem/organization-type",
                                    "code": "team",
                                    "display": "Organizational team"
                                }
                            ]
                        }
                    ],
                    "name": "'.$nama_divisi.'",
                    "telecom": [
                        {
                            "system": "phone",
                            "value": "082324220707",
                            "use": "work"
                        },
                        {
                            "system": "email",
                            "value": "rsimuhammadiyah2kendal@gmail.com",
                            "use": "work"
                        }
                    ],
                    "address": [
                        {
                            "use": "work",
                            "type": "both",
                            "line": [
                                "Paturen I, Pagersari, Patean, Kendal, Jawa Tengah 51364"
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
                                        }
                                    ]
                                }
                            ]
                        }
                    ],
                    "partOf": {
                        "reference": "Organization/'.$sub_org_id.'",
                        "display": "'.$sub_org_nama.'"
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

        public function ambil_data_lokasi_rumah_sakit($org_id)
        {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api-satusehat.kemkes.go.id/fhir-r4/v1/Location',
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
                            "value": "KLINIK PRATAMA PCM WELERI"
                        }
                    ],
                    "status": "active",
                    "name": "KLINIK PRATAMA PCM WELERI",
                    "description": "KLINIK PRATAMA PCM WELERI",
                    "mode": "instance",
                    "telecom": [
                            {
                                "system": "phone",
                                "value": "082324220707",
                                "use": "work"
                            },
                            {
                                "system": "email",
                                "value": "rsimuhammadiyah2kendal@gmail.com",
                                "use": "work"
                            }
                        ],
                    "physicalType": {
                        "coding": [
                            {
                                "system": "http://terminology.hl7.org/CodeSystem/location-physical-type",
                                "code": "si",
                                "display": "Site"
                            }
                        ]
                    },
                    "position": {
                        "longitude": -7.1009857786029595,
                        "latitude": 110.06651489759302,
                        "altitude": 0
                    },
                    "managingOrganization": {
                        "reference": "Organization/'.$org_id.'"
                    }
                }
                ',
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

        public function ambil_data_lokasi_divisi($nama, $org_id)
        {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api-satusehat.kemkes.go.id/fhir-r4/v1/Location',
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
                            "value": "'.$nama.'"
                        }
                    ],
                    "status": "active",
                    "name": "'.$nama.'",
                    "description": "'.$nama.'",
                    "mode": "instance",
                    "telecom": [
                            {
                                "system": "phone",
                                "value": "082324220707",
                                "use": "work"
                            },
                            {
                                "system": "email",
                                "value": "rsimuhammadiyah2kendal@gmail.com",
                                "use": "work"
                            }
                        ],
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
                        "longitude": -7.1009857786029595,
                        "latitude": 110.06651489759302,
                        "altitude": 0
                    },
                    "managingOrganization": {
                        "reference": "Organization/'.$org_id.'"
                    }
                }
                ',
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
    // ==================================00. Membuat Struktur Organisasi dan Lokasi==================================

    // ==================================01. Mencari Data Pasien dan Nakes==================================
        public function pasien($nik)
        {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $this->url->base_url.'/Patient?identifier=https%3A%2F%2Ffhir.kemkes.go.id%2Fid%2Fnik%7C'.$nik,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
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

        public function karyawan_nik($nik)
        {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $this->url->base_url.'/Practitioner?identifier=https%3A%2F%2Ffhir.kemkes.go.id%2Fid%2Fnik%7C'.$nik,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
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

        public function karyawan_tgl_lahir($nama, $tanggal_lahir, $jenis_kelamin)
        {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $this->url->base_url.'/Practitioner?name='.urlencode($nama).'&birthdate='.$tanggal_lahir.'&gender='.$jenis_kelamin,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
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
    // ==================================01. Mencari Data Pasien dan Nakes==================================

    // ==================================02. Pendaftaran Kunjungan Rawat Jalan==================================
        public function kunjungan_baru($id_patient, $name_patient, $id_practitioner, $name_practitioner, $date)
        {
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
                        "start": "'.$date.'"
                    },
                    "location": [
                        {
                            "location": {
                                "reference": "Location/4d0b8a0d-1327-4122-a716-30483c5ff3e8",
                                "display": "KLINIK PRATAMA PCM WELERI"
                            }
                        }
                    ],
                    "statusHistory": [
                        {
                            "status": "arrived",
                            "period": {
                                "start": "'.$date.'"
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
        public function masuk_ruang($encounter_id, $id_patient, $name_patient, $id_practitioner, $name_practitioner, $datetime, $datetime_end, $id_location, $name_location)
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
                                "reference": "Practitioner/'.$id_practitioner.'",
                                "display": "'.$name_practitioner.'"
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
    // ==================================02. Pendaftaran Kunjungan Rawat Jalan==================================

    // ==================================04. Hasil Pemeriksaan Fisik==================================
        public function tekanan_darah_sistole($id_patient, $id_practitioner, $encounter_id, $date, $sistole)
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
                        "reference": "Patient/'.$id_patient.'"
                    },
                    "performer": [
                        {
                            "reference": "Practitioner/'.$id_practitioner.'"
                        }
                    ],
                    "encounter": {
                        "reference": "Encounter/'.$encounter_id.'",
                        "display": "Pemeriksaan Tekanan Darah Sistole"
                    },
                    "effectiveDateTime": "'.$date.'",
                    "issued": "'.$date.'",
                    "valueQuantity": {
                        "value": '.$sistole.',
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

        public function tekanan_darah_diastole($id_patient, $id_practitioner, $encounter_id, $date, $diastole) //Tekanan Darah Diastole
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
                        "reference": "Patient/'.$id_patient.'"
                    },
                    "performer": [
                        {
                            "reference": "Practitioner/'.$id_practitioner.'"
                        }
                    ],
                    "encounter": {
                        "reference": "Encounter/'.$encounter_id.'",
                        "display": "Pemeriksaan Tekanan Darah Diastole"
                    },
                    "effectiveDateTime": "'.$date.'",
                    "issued": "'.$date.'",
                    "valueQuantity": {
                        "value": '.$diastole.',
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

        public function suhu_tubuh($id_patient, $id_practitioner, $encounter_id, $date, $suhu_tubuh) //Suhu Tubuh
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
                        "reference": "Patient/'.$id_patient.'"
                    },
                    "performer": [
                        {
                            "reference": "Practitioner/'.$id_practitioner.'"
                        }
                    ],
                    "encounter": {
                        "reference": "Encounter/'.$encounter_id.'",
                        "display": "Pemeriksaan Fisik Nadi"
                    },
                    "effectiveDateTime": "'.$date.'",
                    "issued": "'.$date.'",
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

        public function denyut_jantung($id_patient, $id_practitioner, $encounter_id, $date, $denyut_jantung)
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
                        "reference": "Patient/'.$id_patient.'"
                    },
                    "encounter": {
                        "reference": "Encounter/'.$encounter_id.'"
                    },
                    "effectiveDateTime": "'.$date.'",
                    "issued": "'.$date.'",
                    "performer": [
                        {
                            "reference": "Practitioner/'.$id_practitioner.'"
                        }
                    ],
                    "valueQuantity": {
                        "value": '.$denyut_jantung.',
                        "unit": "{beats}/min",
                        "system": "http://unitsofmeasure.org",
                        "code": "{beats}/min"
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
    // ==================================04. Hasil Pemeriksaan Fisik==================================

    // ==================================06. Riwayat Perjalanan Penyakit==================================
        public function riwayat_perjalanan_penyakit($id_patient, $name_patient, $id_practitioner, $encounter_id, $date, $data_riwayat)
        {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $this->url->base_url.'/ClinicalImpression',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>'{
                    "resourceType": "ClinicalImpression",
                    "status": "completed",
                    "code": {
                        "coding": [
                            {
                                "system": "http://snomed.info/sct",
                                "code": "312850006",
                                "display": "History of disorder"
                            }
                        ]
                    },
                    "subject": {
                        "reference": "Patient/'.$id_patient.'",
                        "display": "'.$name_patient.'"
                    },
                    "encounter": {
                        "reference": "Encounter/'.$encounter_id.'"
                    },
                    "effectiveDateTime": "'.$date.'",
                    "date": "'.$date.'",
                    "assessor": {
                        "reference": "Practitioner/'.$id_practitioner.'"
                    },
                    "summary": "'.$data_riwayat.'"
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
    // ==================================06. Riwayat Perjalanan Penyakit==================================

    // =========================10. Pemeriksaan Penunjang=======================
        // =========================Laboratorium=======================
            public function procedure_status_puasa_laboratorium_nominal($Patient_id, $Patient_Name, $Encounter_id, $Practitioner_id, $Practitioner_Name, $start_date, $end_date)
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
                    CURLOPT_POSTFIELDS =>'{
                        "resourceType": "Procedure",
                        "status": "not-done",
                        "category": {
                            "coding": [
                                {
                                    "system": "http://snomed.info/sct",
                                    "code": "103693007",
                                    "display": "Diagnostic procedure"
                                }
                            ],
                            "text": "Prosedur diagnostik"
                        },
                        "code": {
                            "coding": [
                                {
                                    "system": "http://snomed.info/sct",
                                    "code": "792805006",
                                    "display": "Fasting"
                                }
                            ]
                        },
                        "subject": {
                            "reference": "Patient/'.$Patient_id.'",
                            "display": "'.$Patient_Name.'"
                        },
                        "encounter": {
                            "reference": "Encounter/'.$Encounter_id.'"
                        },
                        "performedPeriod": {
                            "start": "'.$start_date.'",
                            "end": "'.$end_date.'"
                        },
                        "performer": [
                            {
                                "actor": {
                                    "reference": "Practitioner/'.$Practitioner_id.'",
                                    "display": "'.$Practitioner_Name.'"
                                }
                            }
                        ],
                        "note": [
                            {
                                "text": "Tidak puasa"
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

            public function service_request_laboratorium_nominal($Noreg, $Patient_id, $Patient_Name, $Encounter_id, $Practitioner_id, $Practitioner_Name, $start_date, $end_date, $kode_loinc, $nama_loinc, $deskripsi_loinc, $Procedure_Id)
            {
                // dd($Noreg, $Patient_id, $Patient_Name, $Encounter_id, $Practitioner_id, $Practitioner_Name, $start_date, $end_date, $kode_loinc, $nama_loinc, $deskripsi_loinc, $Procedure_Id);
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
                    CURLOPT_POSTFIELDS =>'{
                        "resourceType": "ServiceRequest",
                        "identifier": [
                            {
                                "system": "http://sys-ids.kemkes.go.id/servicerequest/'.$this->organization_id.'",
                                "value": "'.$Noreg.'"
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
                                    "code": "'.$kode_loinc.'",
                                    "display": "'.$nama_loinc.'"
                                }
                            ],
                            "text": "'.$deskripsi_loinc.'"
                        },
                        "subject": {
                            "reference": "Patient/'.$Patient_id.'"
                        },
                        "encounter": {
                            "reference": "Encounter/'.$Encounter_id.'"
                        },
                        "occurrenceDateTime": "'.$start_date.'",
                        "authoredOn": "'.$end_date.'",
                        "requester": {
                            "reference": "Practitioner/'.$Practitioner_id.'",
                            "display": "'.$Practitioner_id.'"
                        },
                        "performer": [
                            {
                                "reference": "Practitioner/'.$Practitioner_id.'",
                                "display": "'.$Practitioner_Name.'"
                            }
                        ],
                        "reasonCode": [
                            {
                                "text": "'.$deskripsi_loinc.'"
                            }
                        ],
                        "note": [
                            {
                                "text": "Pasien tidak perlu berpuasa terlebih dahulu"
                            }
                        ],
                        "supportingInfo": [
                            {
                                "reference": "Procedure/'.$Procedure_Id.'"
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

            public function specimen_laboratorium_nominal($Noreg, $Patient_id, $Patient_Name, $Practitioner_id, $Practitioner_Name, $date, $kode_snomed, $nama_snomed, $value, $satuan, $service_request_id)
            {
                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => $this->url->base_url.'/Specimen',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS =>'{
                        "resourceType": "Specimen",
                        "identifier": [
                            {
                                "system": "http://sys-ids.kemkes.go.id/specimen/'.$this->organization_id.'",
                                "value": "'.$Noreg.'",
                                "assigner": {
                                    "reference": "Organization/'.$this->organization_id.'"
                                }
                            }
                        ],
                        "status": "available",
                        "type": {
                            "coding": [
                                {
                                    "system": "http://snomed.info/sct",
                                    "code": "'.$kode_snomed.'",
                                    "display": "'.$nama_snomed.'"
                                }
                            ]
                        },
                        "collection": {
                            "collector": {
                                "reference": "Practitioner/'.$Practitioner_id.'",
                                "display": "'.$Practitioner_Name.'"
                            },
                            "collectedDateTime": "'.$date.'",
                            "quantity": {
                                "value": '.$value.',
                                "code": "'.$satuan.'",
                                "unit": "'.$satuan.'",
                                "system": "http://unitsofmeasure.org"
                            },
                            "method": {
                                "coding": [
                                    {
                                        "system": "http://snomed.info/sct",
                                        "code": "'.$kode_snomed.'",
                                        "display": "'.$nama_snomed.'"
                                    }
                                ]
                            },
                            "bodySite": {
                                "coding": [
                                    {
                                        "system": "http://snomed.info/sct",
                                        "code": "'.$kode_snomed.'",
                                        "display": "'.$nama_snomed.'"
                                    }
                                ]
                            },
                            "fastingStatusCodeableConcept": {
                                "coding": [
                                    {
                                        "system": "http://terminology.hl7.org/CodeSystem/v2-0916",
                                        "code": "NF",
                                        "display": "The patient indicated they did not fast prior to the procedure."
                                    }
                                ]
                            }
                        },
                        "processing": [
                            {
                                "procedure": {
                                    "coding": [
                                        {
                                            "system": "http://snomed.info/sct",
                                            "code": "9265001",
                                            "display": "Specimen processing"
                                        }
                                    ]
                                },
                                "timeDateTime": "'.$date.'"
                            }
                        ],
                        "subject": {
                            "reference": "Patient/'.$Patient_id.'",
                            "display": "'.$Patient_Name.'"
                        },
                        "request": [
                            {
                                "reference": "ServiceRequest/'.$service_request_id.'"
                            }
                        ],
                        "receivedTime": "'.$date.'"
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

            public function observation_laboratorium_nominal($Noreg, $Patient_id, $Encounter_id, $Practitioner_id, $Specimen_Id, $ServiceRequest_Id, $loinc_code, $loinc_name, $request_date, $result_date)
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
                        "identifier": [
                            {
                                "system": "http://sys-ids.kemkes.go.id/observation/'.$this->organization_id.'",
                                "value": "'.$Noreg.'"
                            }
                        ],
                        "status": "final",
                        "category": [
                            {
                                "coding": [
                                    {
                                        "system": "http://terminology.hl7.org/CodeSystem/observation-category",
                                        "code": "laboratory",
                                        "display": "Laboratory"
                                    }
                                ]
                            }
                        ],
                        "code": {
                            "coding": [
                                {
                                    "system": "http://loinc.org",
                                    "code": "'.$loinc_code.'",
                                    "display": "'.$loinc_name.'"
                                }
                            ]
                        },
                        "subject": {
                            "reference": "Patient/'.$Patient_id.'"
                        },
                        "encounter": {
                            "reference": "Encounter/'.$Encounter_id.'"
                        },
                        "effectiveDateTime": "'.$request_date.'",
                        "issued": "'.$result_date.'",
                        "performer": [
                            {
                                "reference": "Practitioner/'.$Practitioner_id.'"
                            },
                            {
                                "reference": "Organization/'.$this->organization_id.'"
                            }
                        ],
                        "specimen": {
                            "reference": "Specimen/'.$Specimen_Id.'"
                        },
                        "basedOn": [
                            {
                                "reference": "ServiceRequest/'.$ServiceRequest_Id.'"
                            }
                        ],
                        "valueCodeableConcept": {
                            "coding": [
                                {
                                    "system": "http://loinc.org",
                                    "code": "'.$loinc_code.'",
                                    "display": "'.$loinc_name.'"
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

            public function diagnostic_report_laboratorium_nominal($Noreg, $Patient_id, $Encounter_id, $Practitioner_id, $Observation_id, $Specimen_Id, $ServiceRequest_id, $kode_loinc, $nama_loinc, $request_date, $result_date)
            {
                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => $this->url->base_url.'/DiagnosticReport',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS =>'{
                        "resourceType": "DiagnosticReport",
                        "identifier": [
                            {
                                "system": "http://sys-ids.kemkes.go.id/diagnostic/'.$this->organization_id.'/lab",
                                "use": "official",
                                "value": "'.$Noreg.'"
                            }
                        ],
                        "status": "final",
                        "category": [
                            {
                                "coding": [
                                    {
                                        "system": "http://terminology.hl7.org/CodeSystem/v2-0074",
                                        "code": "LAB",
                                        "display": "Laboratory"
                                    }
                                ]
                            }
                        ],
                        "code": {
                            "coding": [
                                {
                                    "system": "http://loinc.org",
                                    "code": "'.$kode_loinc.'",
                                    "display": "'.$nama_loinc.'"
                                }
                            ]
                        },
                        "subject": {
                            "reference": "Patient/'.$Patient_id.'"
                        },
                        "encounter": {
                            "reference": "Encounter/'.$Encounter_id.'"
                        },
                        "effectiveDateTime": "'.$request_date.'",
                        "issued": "'.$result_date.'",
                        "performer": [
                            {
                                "reference": "Practitioner/'.$Practitioner_id.'"
                            },
                            {
                                "reference": "Organization/'.$this->organization_id.'"
                            }
                        ],
                        "result": [
                            {
                                "reference": "Observation/'.$Observation_id.'"
                            }
                        ],
                        "specimen": [
                            {
                                "reference": "Specimen/'.$Specimen_Id.'"
                            }
                        ],
                        "basedOn": [
                            {
                                "reference": "ServiceRequest/'.$ServiceRequest_id.'"
                            }
                        ],
                        "conclusionCode": [
                            {
                                "coding": [
                                    {
                                        "system": "http://loinc.org",
                                        "code": "'.$kode_loinc.'",
                                        "display": "'.$nama_loinc.'"
                                    }
                                ]
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
        // =========================Laboratorium=======================
        // =========================radiologi=======================
            public function service_request_radiologi($Noreg, $Patient_id, $Encounter_id, $Practitioner_id, $Practitioner_Name, $Practitioner_id_Radiologi, $Practitioner_Name_Radiologi, $start_date)
            {
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
                    CURLOPT_POSTFIELDS =>'{
                        "resourceType": "ServiceRequest",
                        "identifier": [
                            {
                                "system": "http://sys-ids.kemkes.go.id/servicerequest/'.$this->organization_id.'",
                                "value": "'.$Noreg.'"
                            },
                            {
                                "system": "http://sys-ids.kemkes.go.id/acsn/'.$this->organization_id.'",
                                "value": "'.$Noreg.'",
                                "use": "usual",
                                "type": {
                                    "coding": [
                                        {
                                            "system": "http://terminology.hl7.org/CodeSystem/v2-0203",
                                            "code": "ACSN"
                                        }
                                    ]
                                }
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
                                        "code": "363679005",
                                        "display": "Imaging"
                                    }
                                ]
                            }
                        ],
                        "code": {
                            "coding": [
                                {
                                    "system": "http://loinc.org",
                                    "code": "24648-8",
                                    "display": "XR Chest PA upright"
                                }
                            ],
                            "text": "Pemeriksaan CXR PA"
                        },
                        "orderDetail": [
                            {
                                "coding": [
                                    {
                                        "system": "http://dicom.nema.org/resources/ontology/DCM",
                                        "code": "DX"
                                    }
                                ],
                                "text": "Modality Code: DX"
                            },
                            {
                                "coding": [
                                    {
                                        "system": "http://sys-ids.kemkes.go.id/ae-title",
                                        "display": "XR0001"
                                    }
                                ]
                            }
                        ],
                        "subject": {
                            "reference": "Patient/'.$Patient_id.'"
                        },
                        "encounter": {
                            "reference": "Encounter/'.$Encounter_id.'"
                        },
                        "occurrenceDateTime": "'.$start_date.'",
                        "requester": {
                            "reference": "Practitioner/'.$Practitioner_id.'",
                            "display": "'.$Practitioner_Name.'"
                        },
                        "performer": [
                            {
                                "reference": "Practitioner/'.$Practitioner_id_Radiologi.'",
                                "display": "'.$Practitioner_Name_Radiologi.'"
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

            public function radiologi_imaging_study($Noreg)
            {
                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => $this->url->base_url.'/ImagingStudy?identifier=http://sys-ids.kemkes.go.id/acsn/'.$this->organization_id.'|'.$Noreg,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
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

            public function observation_radiologi($Noreg, $Patient_id, $Patient_name, $Encounter_id, $Practitioner_id, $Practitioner_name, $start_date, $end_date, $keterangan_hasil, $service_request_id, $imaging_study_id)
            {
                // dd($Noreg, $Patient_id, $Patient_name, $Encounter_id, $Practitioner_id, $Practitioner_name, $start_date, $end_date, $keterangan_hasil, $service_request_id, $imaging_study_id);
                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://api-satusehat.kemkes.go.id/fhir-r4/v1/Observation',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS =>'{
                        "resourceType": "Observation",
                        "identifier": [
                            {
                                "system": "http://sys-ids.kemkes.go.id/observation/'.$this->organization_id.'",
                                "value": "'.$Noreg.'"
                            }
                        ],
                        "status": "final",
                        "category": [
                            {
                                "coding": [
                                    {
                                        "system": "http://terminology.hl7.org/CodeSystem/observation-category",
                                        "code": "imaging",
                                        "display": "Imaging"
                                    }
                                ]
                            }
                        ],
                        "code": {
                            "coding": [
                                {
                                    "system": "http://loinc.org",
                                    "code": "24648-8",
                                    "display": "XR Chest PA upright"
                                }
                            ]
                        },
                        "subject": {
                            "reference": "Patient/'.$Patient_id.'",
                            "display": "'.$Patient_name.'"
                        },
                        "encounter": {
                            "reference": "Encounter/'.$Encounter_id.'"
                        },
                        "effectiveDateTime": "'.$start_date.'",
                        "issued": "'.$end_date.'",
                        "performer": [
                            {
                                "reference": "Practitioner/'.$Practitioner_id.'",
                                "display": "'.$Practitioner_name.'"
                            }
                        ],
                        "valueString": "Diafragma kanan setinggi os costae 10 posteriorSinus costophrenicus kanan kiri lancip, Cor tak membesarPulmo tak tampak infiltrat",
                        "basedOn": [
                            {
                                "reference": "ServiceRequest/'.$service_request_id.'"
                            }
                        ],
                        "derivedFrom": [
                            {
                                "reference": "ImagingStudy/'.$imaging_study_id.'"
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

            public function diagnostic_report_radiologi($Noreg, $Patient_id, $Encounter_id, $Practitioner_id, $Practitioner_name, $start_date, $end_date, $keterangan_hasil, $service_request_id, $imaging_study_id, $observation_id)
            {
                // dd($Noreg, $Patient_id, $Encounter_id, $Practitioner_id, $Practitioner_name, $start_date, $end_date, $keterangan_hasil, $service_request_id, $imaging_study_id, $observation_id);
                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://api-satusehat.kemkes.go.id/fhir-r4/v1/DiagnosticReport',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS =>'{
                        "resourceType": "DiagnosticReport",
                        "identifier": [
                            {
                                "system": "http://sys-ids.kemkes.go.id/diagnostic/'.$this->organization_id.'/rad",
                                "use": "official",
                                "value": "'.$Noreg.'"
                            }
                        ],
                        "status": "final",
                        "category": [
                            {
                                "coding": [
                                    {
                                        "system": "http://terminology.hl7.org/CodeSystem/v2-0074",
                                        "code": "RAD",
                                        "display": "Radiology"
                                    }
                                ]
                            }
                        ],
                        "code": {
                            "coding": [
                                {
                                    "system": "http://loinc.org",
                                    "code": "24648-8",
                                    "display": "XR Chest PA upright"
                                }
                            ]
                        },
                        "subject": {
                            "reference": "Patient/'.$Patient_id.'"
                        },
                        "encounter": {
                            "reference": "Encounter/'.$Encounter_id.'"
                        },
                        "effectiveDateTime": "'.$start_date.'",
                        "issued": "'.$end_date.'",
                        "performer": [
                            {
                                "reference": "Practitioner/'.$Practitioner_id.'",
                                "display": "'.$Practitioner_name.'"
                            },
                            {
                                "reference": "Organization/'.$this->organization_id.'"
                            }
                        ],
                        "imagingStudy": [
                            {
                                "reference": "ImagingStudy/'.$imaging_study_id.'"
                            }
                        ],
                        "result": [
                            {
                                "reference": "Observation/'.$observation_id.'"
                            }
                        ],
                        "basedOn": [
                            {
                                "reference": "ServiceRequest/'.$service_request_id.'"
                            }
                        ],
                        "conclusion": "Nefrolithiasis kiri"
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
        // =========================Radiologi=======================
    // =========================10. Pemeriksaan Penunjang=======================

    // ==================================12. Diagnosis==================================
        public function diagnosis_primer($kode_diagnosa, $deskripsi_diagnosa, $id_patient, $name_patient, $encounter_id)
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
                        "reference": "Encounter/'.$encounter_id.'",
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
    // ==================================12. Diagnosis==================================

    // ===========14. Tindakan Konseling========
        public function tindakan_konseling_service_request($rekam_id, $kode_diagnosa, $deskripsi_diagnosa, $id_patient, $encounter_id, $practitioner_id, $practitioner_name, $date)
        {
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
                CURLOPT_POSTFIELDS =>'{
                    "resourceType": "ServiceRequest",
                    "identifier": [
                        {
                            "system": "http://sys-ids.kemkes.go.id/servicerequest/'.$this->organization_id.'",
                            "value": "'.$rekam_id.'"
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
                                    "code": "409063005",
                                    "display": "Counseling"
                                }
                            ]
                        }
                    ],
                    "code": {
                        "coding": [
                            {
                                "system": "http://hl7.org/fhir/sid/icd-9-cm",
                                "code": "94.4",
                                "display": "Other psychotherapy and counselling"
                            },
                            {
                                "system": "http://terminology.kemkes.go.id/CodeSystem/kptl",
                                "code": "12017.PC013",
                                "display": "Konseling Individu"
                            }
                        ]
                    },
                    "subject": {
                        "reference": "Patient/'.$id_patient.'"
                    },
                    "encounter": {
                        "reference": "Encounter/'.$encounter_id.'"
                    },
                    "occurrenceDateTime": "'.$date.'",
                    "authoredOn": "'.$date.'",
                    "requester": {
                        "reference": "Practitioner/'.$practitioner_id.'",
                        "display": "'.$practitioner_name.'"
                    },
                    "performer": [
                        {
                            "reference": "Practitioner/'.$practitioner_id.'",
                            "display": "'.$practitioner_name.'"
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
                            ]
                        }
                    ],
                    "note": [
                        {
                            "text": "Pasien melakukan konseling terkait masalah penyakitnya"
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

        public function tindakan_konseling_procedure($service_request_id, $kode_diagnosa, $deskripsi_diagnosa, $id_patient, $name_patient, $encounter_id, $practitioner_id, $practitioner_name, $date)
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
                CURLOPT_POSTFIELDS =>'{
                    "resourceType": "Procedure",
                    "basedOn": [
                        {
                            "reference": "ServiceRequest/'.$service_request_id.'"
                        }
                    ],
                    "status": "completed",
                    "category": {
                        "coding": [
                            {
                                "system": "http://snomed.info/sct",
                                "code": "409063005",
                                "display": "Counseling"
                            }
                        ]
                    },
                    "code": {
                        "coding": [
                            {
                                "system": "http://hl7.org/fhir/sid/icd-9-cm",
                                "code": "94.4",
                                "display": "Other psychotherapy and counselling"
                            },
                            {
                                "system": "http://snomed.info/sct",
                                "code": "445142003",
                                "display": "Counseling about disease"
                            }
                        ]
                    },
                    "subject": {
                        "reference": "Patient/'.$id_patient.'",
                        "display": "'.$name_patient.'"
                    },
                    "encounter": {
                        "reference": "Encounter/'.$encounter_id.'"
                    },
                    "performedPeriod": {
                        "start": "'.$date.'",
                        "end": "'.$date.'"
                    },
                    "performer": [
                        {
                            "actor": {
                                "reference": "Practitioner/'.$practitioner_id.'",
                                "display": "'.$practitioner_name.'"
                            }
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
                            ]
                        }
                    ],
                    "note": [
                        {
                            "text": "Konseling keresahan pasien"
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
    // ===========End 14. Tindakan Konseling====

    // ===========15. Tata Laksana========
        // ===========peresepan obat========
            public function kfa_api($search = '', $page = 1, $size = 100)
            {
                $url = 'https://api-satusehat.kemkes.go.id/kfa-v2/products/all?page=' . $page . '&size=' . $size . '&product_type=farmasi';
                if (!empty($search)) {
                    $url .= '&keyword=' . urlencode($search);
                }
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
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

            public function create_medication($kode_obat_rs, $kode_kfa, $deskripsi_kfa, $kode_obat, $display_obat)
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
                            "value": "'.$kode_obat_rs.'"
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
                return $response;
            }

            public function create_medication_request($id_obat_satusehat, $display_obat_satusehat, $id_patient, $id_patient_display, $encounter, $tanggal_peresepan, $id_dockter_satu_sehat, $display_dockter_satu_sehat, $dosis_obat, $start_waktu_pemberian_obat, $end_waktu_pemberian_obat, $jumlah_obat, $durasi_penggunaan)
            {
                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => $this->url->base_url.'/MedicationRequest',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS =>'{
                        "resourceType": "MedicationRequest",
                        "identifier": [
                            {
                                "system": "http://sys-ids.kemkes.go.id/prescription/'.$this->organization_id.'",
                                "use": "official",
                                "value": "123456788"
                            },
                            {
                                "system": "http://sys-ids.kemkes.go.id/prescription-item/'.$this->organization_id.'",
                                "use": "official",
                                "value": "123456788-1"
                            }
                        ],
                        "status": "completed",
                        "intent": "order",
                        "category": [
                            {
                                "coding": [
                                    {
                                        "system": "http://terminology.hl7.org/CodeSystem/medicationrequest-category",
                                        "code": "outpatient",
                                        "display": "Outpatient"
                                    }
                                ]
                            }
                        ],
                        "priority": "routine",
                        "medicationReference": {
                            "reference": "Medication/'.$id_obat_satusehat.'",
                            "display": "'.$display_obat_satusehat.'"
                        },
                        "subject": {
                            "reference": "Patient/'.$id_patient.'",
                            "display": "'.$id_patient_display.'"
                        },
                        "encounter": {
                            "reference": "Encounter/'.$encounter.'"
                        },
                        "authoredOn": "'.$tanggal_peresepan.'",
                        "requester": {
                            "reference": "Practitioner/'.$id_dockter_satu_sehat.'",
                                    "display": "'.$display_dockter_satu_sehat.'"
                        },
                        "courseOfTherapyType": {
                            "coding": [
                                {
                                    "system": "http://terminology.hl7.org/CodeSystem/medicationrequest-course-of-therapy",
                                    "code": "continuous",
                                    "display": "Continuing long term therapy"
                                }
                            ]
                        },
                        "dosageInstruction": [
                            {
                                "sequence": 1,
                                "text": "'.$dosis_obat.'",
                                "additionalInstruction": [
                                    {
                                        "text": "Diminum setiap hari"
                                    }
                                ],
                                "patientInstruction": "'.$dosis_obat.'",
                                "timing": {
                                    "repeat": {
                                        "frequency": 1,
                                        "period": 1,
                                        "periodUnit": "d"
                                    }
                                },
                                "route": {
                                    "coding": [
                                        {
                                            "system": "http://www.whocc.no/atc",
                                            "code": "O",
                                            "display": "Oral"
                                        }
                                    ]
                                },
                                "doseAndRate": [
                                    {
                                        "type": {
                                            "coding": [
                                                {
                                                    "system": "http://terminology.hl7.org/CodeSystem/dose-rate-type",
                                                    "code": "ordered",
                                                    "display": "Ordered"
                                                }
                                            ]
                                        },
                                        "doseQuantity": {
                                            "value": 4,
                                            "unit": "TAB",
                                            "system": "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm",
                                            "code": "TAB"
                                        }
                                    }
                                ]
                            }
                        ],
                        "dispenseRequest": {
                            "dispenseInterval": {
                                "value": 1,
                                "unit": "days",
                                "system": "http://unitsofmeasure.org",
                                "code": "d"
                            },
                            "validityPeriod": {
                                "start": "'.$start_waktu_pemberian_obat.'",
                                "end": "'.$end_waktu_pemberian_obat.'"
                            },
                            "numberOfRepeatsAllowed": 0,
                            "quantity": {
                                "value": '.$jumlah_obat.',
                                "unit": "TAB",
                                "system": "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm",
                                "code": "TAB"
                            },
                            "expectedSupplyDuration": {
                                "value": '.$durasi_penggunaan.',
                                "unit": "days",
                                "system": "http://unitsofmeasure.org",
                                "code": "d"
                            },
                            "performer": {
                                "reference": "Organization/'.$this->organization_id.'"
                            }
                        }
                    }',
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json',
                        'Authorization: Bearer '.$this->token
                    ),
                ));
                $response = curl_exec($curl);
                // dd($response);
                curl_close($curl);
                $response = json_decode($response);
                return $response;

                return response()->json($response);
            }
        // ===========peresepan obat========
        // ===========Pengkajian resep========
            public function create_questionnaire_response($kode_quesnionnaire,$id_patient, $id_patient_display, $encounter, $tanggal_peresepan, $id_apoteker, $display_apoteker)
            {
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $this->url->base_url.'/QuestionnaireResponse',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS =>'{
                        "resourceType": "QuestionnaireResponse",
                        "questionnaire": "https://fhir.kemkes.go.id/Questionnaire/'.$kode_quesnionnaire.'",
                        "status": "completed",
                        "subject": {
                            "reference": "Patient/'.$id_patient.'",
                            "display": "'.$id_patient_display.'"
                        },
                        "encounter": {
                            "reference": "Encounter/'.$encounter.'"
                        },
                        "authored": "'.$tanggal_peresepan.'",
                        "author": {
                            "reference": "Practitioner/'.$id_apoteker.'",
                            "display": "'.$display_apoteker.'"
                        },
                        "source": {
                            "reference": "Patient/'.$id_patient.'"
                        },
                        "item": [
                            {
                                "linkId": "1",
                                "text": "Persyaratan Administrasi",
                                "item": [
                                    {
                                        "linkId": "1.1",
                                        "text": "Apakah nama, umur, jenis kelamin, berat badan dan tinggi badan pasien sudah sesuai?",
                                        "answer": [
                                            {
                                                "valueCoding": {
                                                    "system": "http://terminology.kemkes.go.id/CodeSystem/clinical-term",
                                                    "code": "OV000052",
                                                    "display": "Sesuai"
                                                }
                                            }
                                        ]
                                    },
                                    {
                                        "linkId": "1.2",
                                        "text": "Apakah nama, nomor ijin, alamat dan paraf dokter sudah sesuai?",
                                        "answer": [
                                            {
                                                "valueCoding": {
                                                    "system": "http://terminology.kemkes.go.id/CodeSystem/clinical-term",
                                                    "code": "OV000052",
                                                    "display": "Sesuai"
                                                }
                                            }
                                        ]
                                    },
                                    {
                                        "linkId": "1.3",
                                        "text": "Apakah tanggal resep sudah sesuai?",
                                        "answer": [
                                            {
                                                "valueCoding": {
                                                    "system": "http://terminology.kemkes.go.id/CodeSystem/clinical-term",
                                                    "code": "OV000052",
                                                    "display": "Sesuai"
                                                }
                                            }
                                        ]
                                    },
                                    {
                                        "linkId": "1.4",
                                        "text": "Apakah ruangan/unit asal resep sudah sesuai?",
                                        "answer": [
                                            {
                                                "valueCoding": {
                                                    "system": "http://terminology.kemkes.go.id/CodeSystem/clinical-term",
                                                    "code": "OV000052",
                                                    "display": "Sesuai"
                                                }
                                            }
                                        ]
                                    },
                                    {
                                        "linkId": "2",
                                        "text": "Persyaratan Farmasetik",
                                        "item": [
                                            {
                                                "linkId": "2.1",
                                                "text": "Apakah nama obat, bentuk dan kekuatan sediaan sudah sesuai?",
                                                "answer": [
                                                    {
                                                        "valueCoding": {
                                                            "system": "http://terminology.kemkes.go.id/CodeSystem/clinical-term",
                                                            "code": "OV000052",
                                                            "display": "Sesuai"
                                                        }
                                                    }
                                                ]
                                            },
                                            {
                                                "linkId": "2.2",
                                                "text": "Apakah dosis dan jumlah obat sudah sesuai?",
                                                "answer": [
                                                    {
                                                        "valueCoding": {
                                                            "system": "http://terminology.kemkes.go.id/CodeSystem/clinical-term",
                                                            "code": "OV000052",
                                                            "display": "Sesuai"
                                                        }
                                                    }
                                                ]
                                            },
                                            {
                                                "linkId": "2.3",
                                                "text": "Apakah stabilitas obat sudah sesuai?",
                                                "answer": [
                                                    {
                                                        "valueCoding": {
                                                            "system": "http://terminology.kemkes.go.id/CodeSystem/clinical-term",
                                                            "code": "OV000052",
                                                            "display": "Sesuai"
                                                        }
                                                    }
                                                ]
                                            },
                                            {
                                                "linkId": "2.4",
                                                "text": "Apakah aturan dan cara penggunaan obat sudah sesuai?",
                                                "answer": [
                                                    {
                                                        "valueCoding": {
                                                            "system": "http://terminology.kemkes.go.id/CodeSystem/clinical-term",
                                                            "code": "OV000052",
                                                            "display": "Sesuai"
                                                        }
                                                    }
                                                ]
                                            }
                                        ]
                                    },
                                    {
                                        "linkId": "3",
                                        "text": "Persyaratan Klinis",
                                        "item": [
                                            {
                                                "linkId": "3.1",
                                                "text": "Apakah ketepatan indikasi, dosis, dan waktu penggunaan obat sudah sesuai?",
                                                "answer": [
                                                    {
                                                        "valueCoding": {
                                                            "system": "http://terminology.kemkes.go.id/CodeSystem/clinical-term",
                                                            "code": "OV000052",
                                                            "display": "Sesuai"
                                                        }
                                                    }
                                                ]
                                            },
                                            {
                                                "linkId": "3.2",
                                                "text": "Apakah terdapat duplikasi pengobatan?",
                                                "answer": [
                                                    {
                                                        "valueBoolean": false
                                                    }
                                                ]
                                            },
                                            {
                                                "linkId": "3.3",
                                                "text": "Apakah terdapat alergi dan reaksi obat yang tidak dikehendaki (ROTD)?",
                                                "answer": [
                                                    {
                                                        "valueBoolean": false
                                                    }
                                                ]
                                            },
                                            {
                                                "linkId": "3.4",
                                                "text": "Apakah terdapat kontraindikasi pengobatan?",
                                                "answer": [
                                                    {
                                                        "valueBoolean": false
                                                    }
                                                ]
                                            },
                                            {
                                                "linkId": "3.5",
                                                "text": "Apakah terdapat dampak interaksi obat?",
                                                "answer": [
                                                    {
                                                        "valueBoolean": false
                                                    }
                                                ]
                                            }
                                        ]
                                    }
                                ]
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
        // ===========End Pengkajian resep========
        // ===========Pengeluaran Obat========
            public function create_medication_dispense_obat($kode_barang_obat, $kode_oabat_kfa, $deskripsi_obat_kfa)
            {
                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://api-satusehat.kemkes.go.id/fhir-r4/v1/Medication',
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
                                "https://fhir.kemkes.go.id/r4/StructureDefinition/Medication"
                            ]
                        },
                        "identifier": [
                            {
                                "system": "http://sys-ids.kemkes.go.id/medication/'.$this->organization_id.'",
                                "use": "official",
                                "value": "'.$kode_barang_obat.'"
                            }
                        ],
                        "code": {
                            "coding": [
                                {
                                    "system": "http://sys-ids.kemkes.go.id/kfa",
                                "code": "'.$kode_oabat_kfa.'",
                                "display": "'.$deskripsi_obat_kfa.'"
                                }
                            ]
                        },
                        "status": "active",
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
                return $response;
            }

            public function create_medication_dispense($nomer_resep, $medication_id, $display_obat_satusehat, $id_patient, $id_patient_display, $encounter, $id_dockter_satu_sehat, $display_dockter_satu_sehat, $medication_request_id, $start_waktu_pemberian_obat, $end_waktu_pemberian_obat)
            {
                // dd($nomer_resep, $medication_id, $display_obat_satusehat, $id_patient, $id_patient_display, $encounter, $id_dockter_satu_sehat, $display_dockter_satu_sehat, $medication_request_id, $start_waktu_pemberian_obat, $end_waktu_pemberian_obat);
                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://api-satusehat.kemkes.go.id/fhir-r4/v1/MedicationDispense',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS =>'{
                        "resourceType": "MedicationDispense",
                        "identifier": [
                            {
                                "system": "http://sys-ids.kemkes.go.id/prescription/'.$this->organization_id.'",
                                "use": "official",
                                "value": "'.$nomer_resep.'"
                            }
                        ],
                        "status": "completed",
                        "category": {
                            "coding": [
                                {
                                    "system": "http://terminology.hl7.org/fhir/CodeSystem/medicationdispense-category",
                                    "code": "community",
                                    "display": "Community"
                                }
                            ]
                        },
                        "medicationReference": {
                            "reference": "Medication/'.$medication_id.'",
                            "display": "'.$display_obat_satusehat.'"
                        },
                        "subject": {
                            "reference": "Patient/'.$id_patient.'",
                            "display": "'.$id_patient_display.'"
                        },
                        "context": {
                            "reference": "Encounter/'.$encounter.'"
                        },
                        "performer": [
                            {
                                "actor": {
                                    "reference": "Practitioner/'.$id_dockter_satu_sehat.'",
                                    "display": "'.$display_dockter_satu_sehat.'"
                                }
                            }
                        ],
                        "location": {
                            "reference": "Location/12778eaf-f276-4982-aa9f-85bae68d942c",
                            "display": "FARMASI - RAWAT JALAN"
                        },
                        "authorizingPrescription": [
                            {
                                "reference": "MedicationRequest/'.$medication_request_id.'"
                            }
                        ],

                        "whenPrepared": "'.$start_waktu_pemberian_obat.'",
                        "whenHandedOver": "'.$end_waktu_pemberian_obat.'"

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
        // ===========Pengeluaran Obat========
    // ===========End 15. Tata Laksana====

    // ===========18. Kondisi Saat Meninggalkan Fasyankes=======
        public function kondisi_saat_meninggalkan_fasyankes($id_patient, $name_patient, $encounter_id)
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
                                    "code": "problem-list-item",
                                    "display": "Problem List Item"
                                }
                            ]
                        }
                    ],
                    "code": {
                        "coding": [
                            {
                                "system": "http://snomed.info/sct",
                                "code": "359746009",
                                "display": "Patients condition stable"
                            }
                        ]
                    },
                    "subject": {
                        "reference": "Patient/'.$id_patient.'",
                        "display": "'.$name_patient.'"
                    },
                    "encounter": {
                        "reference": "Encounter/'.$encounter_id.'"
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
    // ===========End 18. Kondisi Saat Meninggalkan Fasyankes====

}
