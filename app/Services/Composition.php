<?php

namespace App\Services;
use App\Services\Config;
class Composition
{
    public function __construct()
    {
        $this->config = new Config;
        // $this->token = $this->config->auth()->access_token; //jika tidak menggunakan cache
        $this->token = $this->config->getAccessToken(); //jika menggunakan cache
        $this->url = $this->config->url();
        $this->organization_id = $this->config->organization_id()->id;
    }

    public function create_composition($id_patient, $id_patient_display, $encounter, $tanggal, $id_dockter_satu_sehat, $display_dockter_satu_sehat, $keterangan_diet)
    {
        // dd($id_patient.'/////'.$id_patient_display.'/////'.$encounter.'/////'.$tanggal.'/////'.$id_dockter_satu_sehat.'/////'.$display_dockter_satu_sehat.'/////'.$keterangan_diet);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url->base_url.'/Composition',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "resourceType": "Composition",
                "identifier": {
                    "system": "http://sys-ids.kemkes.go.id/composition/'.$this->organization_id.'",
                    "value": "P20240001"
                },
                "status": "final",
                "type": {
                    "coding": [
                        {
                            "system": "http://loinc.org",
                            "code": "18842-5",
                            "display": "Discharge summary"
                        }
                    ]
                },
                "category": [
                    {
                        "coding": [
                            {
                                "system": "http://loinc.org",
                                "code": "LP173421-1",
                                "display": "Report"
                            }
                        ]
                    }
                ],
                "subject": {
                    "reference": "Patient/'.$id_patient.'",
                    "display": "'.$id_patient_display.'"
                },
                "encounter": {
                    "reference": "Encounter/'.$encounter.'",
                    "display": "Pemeriksaan"
                },
                "date": "'.$tanggal.'",
                "author": [
                    {
                        "reference": "Practitioner/'.$id_dockter_satu_sehat.'",
                        "display": "'.$display_dockter_satu_sehat.'"
                    }
                ],
                "title": "Resume Medis Rawat Jalan",
                "custodian": {
                    "reference": "Organization/'.$this->organization_id.'"
                },
                "section": [
                    {
                        "code": {
                            "coding": [
                                {
                                    "system": "http://loinc.org",
                                    "code": "42344-2",
                                    "display": "Discharge diet (narrative)"
                                }
                            ]
                        },
                        "text": {
                            "status": "additional",
                            "div": "Rekomendasi diet '.$keterangan_diet.'"
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

        return response()->json($response);
    }
}
