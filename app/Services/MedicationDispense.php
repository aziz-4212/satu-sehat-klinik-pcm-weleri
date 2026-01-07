<?php

namespace App\Services;
use App\Services\Config;
class MedicationDispense
{
    public function __construct()
    {
        $this->config = new Config;
        // $this->token = $this->config->auth()->access_token; //jika tidak menggunakan cache
        $this->token = $this->config->getAccessToken(); //jika menggunakan cache
        $this->url = $this->config->url();
        $this->organization_id = $this->config->organization_id()->id;
    }

    public function create_medication_dispense($id_obat_satusehat, $display_obat_satusehat, $id_patient, $id_patient_display, $encounter, $tanggal_peresepan, $id_dockter_satu_sehat, $display_dockter_satu_sehat, $dosis_obat, $start_waktu_pemberian_obat, $end_waktu_pemberian_obat, $jumlah_obat, $durasi_penggunaan, $medication_request_id)
    {
        // dd($id_obat_satusehat.'/////'.$display_obat_satusehat.'/////'.$id_patient.'/////'.$id_patient_display.'/////'.$encounter.'/////'.$tanggal_peresepan.'/////'.$id_dockter_satu_sehat.'/////'.$display_dockter_satu_sehat.'/////'.$dosis_obat.'/////'.$start_waktu_pemberian_obat.'/////'.$end_waktu_pemberian_obat.'/////'.$jumlah_obat.'/////'.$durasi_penggunaan.'/////'.$medication_request_id);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url->base_url.'/MedicationDispense',
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
                    "value": "123456788"
                },
                {
                    "system": "http://sys-ids.kemkes.go.id/prescription-item/'.$this->organization_id.'",
                    "use": "official",
                    "value": "123456788-1"
                }
            ],
            "status": "completed",
            "category": {
                "coding": [
                    {
                        "system": "http://terminology.hl7.org/fhir/CodeSystem/medicationdispense-category",
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
                "reference": "Location/ebdbcbcd-881b-4a42-a1c0-1fa1b9259092",
                "display": "Apotek RSI Kendal"
            },
            "authorizingPrescription": [
                {
                    "reference": "MedicationRequest/'.$medication_request_id.'"
                }
            ],
            "quantity": {
                "system": "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm",
                "code": "TAB",
                "value": 120
            },
            "daysSupply": {
                "value": 30,
                "unit": "Day",
                "system": "http://unitsofmeasure.org",
                "code": "d"
            },
            "whenPrepared": "'.$start_waktu_pemberian_obat.'T10:20:00Z",
            "whenHandedOver": "'.$start_waktu_pemberian_obat.'T12:20:00Z",
            "dosageInstruction": [
                {
                    "sequence": 1,
                    "text": "Diminum Setiap Hari",
                    "timing": {
                        "repeat": {
                            "frequency": 1,
                            "period": 1,
                            "periodUnit": "d"
                        }
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
            ]
            }
            ',
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
}
