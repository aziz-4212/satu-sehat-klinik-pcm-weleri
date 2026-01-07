<?php

namespace App\Services;
use App\Services\Config;
class QuestionnaireResponse
{
    public function __construct()
    {
        $this->config = new Config;
        // $this->token = $this->config->auth()->access_token; //jika tidak menggunakan cache
        $this->token = $this->config->getAccessToken(); //jika menggunakan cache
        $this->url = $this->config->url();
        $this->organization_id = $this->config->organization_id()->id;
    }

    public function create_questionnaire_response($noreg_terakhir, $id_patient, $id_patient_display, $encounter, $tanggal_peresepan, $id_dokter_satu_sehat, $display_dokter_satu_sehat)
    {
        // dd($id_patient.'/////'.$id_patient_display.'/////'.$encounter.'/////'.$tanggal_peresepan.'/////'.$id_dokter_satu_sehat.'/////'.$display_dokter_satu_sehat);

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
                "questionnaire": "https://fhir.kemkes.go.id/Questionnaire/'.$noreg_terakhir.'",
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
                    "reference": "Practitioner/'.$id_dokter_satu_sehat.'",
                    "display": "'.$display_dokter_satu_sehat.'"
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

        return response()->json($response);
    }
}
