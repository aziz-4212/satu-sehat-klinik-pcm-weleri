<?php

namespace App\Services;
use App\Services\Config;
class MedicationStatement
{
    public function __construct()
    {
        $this->config = new Config;
        // $this->token = $this->config->auth()->access_token; //jika tidak menggunakan cache
        $this->token = $this->config->getAccessToken(); //jika menggunakan cache
        $this->url = $this->config->url();
        $this->organization_id = $this->config->organization_id()->id;
    }

    public function create_medication_statement($id_obat_satusehat, $display_obat_satusehat, $id_patient, $id_patient_display, $encounter, $dosis_obat, $start_waktu_pemberian_obat, $end_waktu_pemberian_obat)
    {
        // dd($id_obat_satusehat.'/////'.$display_obat_satusehat.'/////'.$id_patient.'/////'.$id_patient_display.'/////'.$encounter.'/////'.$dosis_obat.'/////'.$start_waktu_pemberian_obat.'/////'.$end_waktu_pemberian_obat);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url->base_url.'/MedicationStatement',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "resourceType": "MedicationStatement",
                "status": "completed",
                "category": {
                    "coding": [
                        {
                            "system": "http://terminology.hl7.org/CodeSystem/medication-statement-category",
                            "code": "outpatient",
                            "display": "Outpatient"
                        }
                    ]
                },
                "medicationReference": {
                    "reference": "Medication/'.$id_obat_satusehat.'",
                    "display": "'.$display_obat_satusehat.'"
                },
                "subject": {
                    "reference": "Patient/'.$id_patient.'",
                    "display": "'.$id_patient_display.'"
                },
                "dosage": [
                    {
                        "text": "'.$dosis_obat.'",
                        "timing": {
                            "repeat": {
                                "frequency": 1,
                                "period": 1,
                                "periodMax": 2,
                                "periodUnit": "d"
                            }
                        }
                    }
                ],
                "effectiveDateTime": "'.$start_waktu_pemberian_obat.'",
                "dateAsserted": "'.$end_waktu_pemberian_obat.'",
                "informationSource": {
                    "reference": "Patient/'.$id_patient.'",
                    "display": "'.$id_patient_display.'"
                },
                "context": {
                    "reference": "Encounter/'.$encounter.'"
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

        return response()->json($response);
    }
}
