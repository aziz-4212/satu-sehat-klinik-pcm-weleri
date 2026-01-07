<?php

namespace App\Services;
use App\Services\Config;
class MedicationRequest
{
    public function __construct()
    {
        $this->config = new Config;
        // $this->token = $this->config->auth()->access_token; //jika tidak menggunakan cache
        $this->token = $this->config->getAccessToken(); //jika menggunakan cache
        $this->url = $this->config->url();
        $this->organization_id = $this->config->organization_id()->id;
    }

    public function create_medication_request($id_obat_satusehat, $display_obat_satusehat, $id_patient, $id_patient_display, $encounter, $tanggal_peresepan, $id_dockter_satu_sehat, $display_dockter_satu_sehat, $dosis_obat, $start_waktu_pemberian_obat, $end_waktu_pemberian_obat, $jumlah_obat, $durasi_penggunaan, $kode_diagnosa, $deskripsi_diagnosa)
    {
        // dd($id_obat_satusehat.'/////'.$display_obat_satusehat.'/////'.$id_patient.'/////'.$id_patient_display.'/////'.$encounter.'/////'.$tanggal_peresepan.'/////'.$id_dockter_satu_sehat.'/////'.$display_dockter_satu_sehat.'/////'.$dosis_obat.'/////'.$start_waktu_pemberian_obat.'/////'.$end_waktu_pemberian_obat.'/////'.$jumlah_obat.'/////'.$durasi_penggunaan);
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
}
