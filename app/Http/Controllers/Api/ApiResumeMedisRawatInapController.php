<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use DB;
use App\Services\Config;
use App\Services\Condition;
use App\Services\Patient;
use App\Services\Encounter;
use App\Services\Procedure;
use App\Services\Medication;
use App\Services\MedicationRequest;
use App\Services\MedicationDispense;
use App\Services\MedicationStatement;
use App\Services\SeviceRequestServices;
use App\Services\Composition;
use App\Services\QuestionnaireResponse;
use App\Services\Careplan;

use App\Models\RegistrasiPasien;
use App\Models\MappingPasien;
use App\Models\MappingKunjunganInap;
use App\Models\MappingDokterSpesialis;
use App\Models\MappingOrganization;
use App\Models\LogEncounterInap;
use App\Models\BpjsSepManual;
use App\Models\Diagnosis;
use App\Models\KondisiPulang;
use App\Models\LogDiagnosis;
use App\Services\Observasion;
use App\Models\Observation;
use App\Models\LogObservation;
use App\Models\RjSkriningTb;
use App\Models\IgdSkriningTb;
use App\Models\CheckinPoli;
use App\Models\MasterLoinc;
use App\Models\ServiceRequest;
use App\Models\LogServiceRequest;
use App\Models\TrxOrder;
use App\Models\OrderPmr;
use App\Models\Trxpmr;
use App\Models\Mapmr;
use App\Models\MasterMapmrLoinc;
use App\Models\Trpmn;
use App\Models\Trpdn;
use App\Models\Mabar;
use App\Models\MasterKfaObat;
use App\Models\Pasien;
use App\Models\MappingSep;
use App\Models\Sep;
use App\Models\AssessmentAwalMedisRawatInapDiet;
use App\Models\AssessmentAwalMedisRawatInap;
use App\Models\BpjsRencanaKontrol;
use App\Models\SuratPersetujuanInap;
use App\Models\SuratPersetujuanNaikKelas;

use App\Models\RIRencanaRawatPasien;
use App\Models\RIRencanaRawatPasienLog;
use App\Models\RIDiagnosis;
use App\Models\RIDiagnosisLog;

use GuzzleHttp\Client;

class ApiResumeMedisRawatInapController extends Controller
{
    public function __construct()
    {
        $this->patient = new Patient;
        $this->encounter = new Encounter;
        $this->condition = new Condition;
        $this->observasion = new Observasion;
        $this->sevice_request = new SeviceRequestServices;
        $this->procedure = new Procedure;
        $this->medication = new Medication;
        $this->medication_request = new MedicationRequest();
        $this->medication_dispense = new MedicationDispense();
        $this->medication_statement = new MedicationStatement();
        $this->composition = new Composition();
        $this->questionnaire_response = new QuestionnaireResponse();
        $this->careplan = new Careplan();

    }

    // ===============================02. Rencana Rawat Pasien===============================
        // ===============================CarePlan - Rencana Rawat Pasien===============================
            public function careplan_rencana_rawat_pasien(Request $request){
                set_time_limit((int) 0);
                $ri_rencana_rawat_pasien = RIRencanaRawatPasien::orderBy('noreg', 'desc')->pluck('noreg')->first();
                $ri_rencana_rawat_pasien_log = RIRencanaRawatPasienLog::orderBy('noreg', 'desc')->pluck('noreg')->first();
                if ($ri_rencana_rawat_pasien > $ri_rencana_rawat_pasien_log) {
                    $noreg_terakhir = $ri_rencana_rawat_pasien;
                }elseif ($ri_rencana_rawat_pasien < $ri_rencana_rawat_pasien_log) {
                    $noreg_terakhir = $ri_rencana_rawat_pasien_log;
                }elseif ($ri_rencana_rawat_pasien == $ri_rencana_rawat_pasien_log) {
                    $noreg_terakhir = $ri_rencana_rawat_pasien;
                }

                $noreg_terakhir = $noreg_terakhir+1;
                $noreg_tanggal_depan = (substr($noreg_terakhir, 0, -4)+1)."0000";
                $data_tanggal_terakhir = MappingKunjunganInap::where('noreg', "<",$noreg_tanggal_depan)->orderBy('noreg', 'desc')->pluck('noreg')->first();
                if ($noreg_terakhir > $data_tanggal_terakhir) {
                    $noreg_terakhir = $noreg_tanggal_depan+1;
                }

                //berhentikan sebelum noreg hari sekarang
                $now = Carbon::now()->setTimezone('Asia/Jakarta')->format('ymd');
                $noreg_batas = (Integer)($now . '0000');
                if ($noreg_terakhir > $noreg_batas) {
                    return response()->json([
                        'noreg' => $noreg_terakhir,
                        'message' => "Noreg Dalam Pelayanan",
                        'nama schedule' => 'RI Rencana Rawat Pasien Log'
                    ], 200);
                }

                if (substr($noreg_terakhir, 2, 4) == '1232') {
                    $noreg_terakhir += 100000000;
                    $noreg_terakhir = substr_replace($noreg_terakhir, '0101', 2, 4);
                }

                $data_terbesar = MappingKunjunganInap::orderBy('noreg', 'desc')->pluck('noreg')->first();
                if ($noreg_terakhir > $data_terbesar) {
                    return response()->json([
                        'noreg' => $noreg_terakhir,
                        'message' => "Noreg Belum Terdaftar",
                        'nama schedule' => 'RI Rencana Rawat Pasien Log'
                    ], 200);
                }
                $mapping_kunjungan_inap = MappingKunjunganInap::where('noreg', $noreg_terakhir)->first();
                if ($mapping_kunjungan_inap == null) {
                    $ri_rencana_rawat_pasien_log = new RIRencanaRawatPasienLog();
                    $ri_rencana_rawat_pasien_log->noreg = $noreg_terakhir;
                    $ri_rencana_rawat_pasien_log->ket_log = 'Mapping kunjungan inap tidak ditemukan';
                    $ri_rencana_rawat_pasien_log->save();
                    return response()->json([
                        'noreg' => $noreg_terakhir,
                        'message' => "Mapping kunjungan inap tidak ditemukan",
                        'nama schedule' => 'RI Rencana Rawat Pasien Log'
                    ], 200);
                }
                $registrasi_pasien = RegistrasiPasien::where('NOREG', $noreg_terakhir)->first();
                if ($registrasi_pasien == null) {
                    $ri_rencana_rawat_pasien_log = new RIRencanaRawatPasienLog();
                    $ri_rencana_rawat_pasien_log->noreg = $noreg_terakhir;
                    $ri_rencana_rawat_pasien_log->ket_log = 'Noreg Belum Terdaftar';
                    $ri_rencana_rawat_pasien_log->save();
                    return response()->json([
                        'noreg' => $noreg_terakhir,
                        'message' => "Noreg Belum Terdaftar",
                        'nama schedule' => 'RI Rencana Rawat Pasien Log'
                    ], 200);
                }
                $mapping_pasien = MappingPasien::where('norm', $registrasi_pasien->NOPASIEN)->first();
                if ($mapping_pasien == null) {
                    $ri_rencana_rawat_pasien_log = new RIRencanaRawatPasienLog();
                    $ri_rencana_rawat_pasien_log->noreg = $noreg_terakhir;
                    $ri_rencana_rawat_pasien_log->ket_log = "Pasien Tidak Ditemukan Di Mapping Pasien";
                    $ri_rencana_rawat_pasien_log->save();
                    return response()->json([
                        'noreg' => $noreg_terakhir,
                        'message' => "Pasien Tidak Ditemukan Di Mapping Pasien",
                        'nama schedule' => 'RI Rencana Rawat Pasien Log'
                    ], 200);
                }

                $mapping_dokter_spesialis = MappingDokterSpesialis::where('kodepelayanan', $registrasi_pasien->Registrasi_Dokter->KODEDOKTER)->first();
                if ($mapping_dokter_spesialis == null) {
                    $ri_rencana_rawat_pasien_log = new RIRencanaRawatPasienLog();
                    $ri_rencana_rawat_pasien_log->noreg = $noreg_terakhir;
                    $ri_rencana_rawat_pasien_log->ket_log = "Dokter untuk Pasien ".$registrasi_pasien->Pasien->NAMAPASIEN." Tidak Ditemukan Di Mapping Dokter Spesialis";
                    $ri_rencana_rawat_pasien_log->save();
                    return response()->json([
                        'noreg' => $noreg_terakhir,
                        'message' => "Dokter untuk Pasien ".$registrasi_pasien->Pasien->NAMAPASIEN." Tidak Ditemukan Di Mapping Dokter Spesialis",
                        'nama schedule' => 'RI Rencana Rawat Pasien Log'
                    ], 200);
                }
                $id_patient = $mapping_pasien->kodesatusehat;
                $id_patient_display = $mapping_pasien->namasatusehat;
                $encounter = $mapping_kunjungan_inap->encounter;
                $date = Carbon::now()->setTimezone('Asia/Jakarta')->format('Y-m-d');
                $time = Carbon::now()->setTimezone('Asia/Jakarta')->format('H:i:s');
                $id_dokter_satu_sehat = $mapping_dokter_spesialis->satusehat;
                $display_dokter_satu_sehat = $mapping_dokter_spesialis->nama;
                $deskripsi = "Rencana Rawat Pasien";

                try {
                    $careplan = $this->careplan->RI_rencana_rawat_pasien($deskripsi, $id_patient, $id_patient_display, $encounter, $id_dokter_satu_sehat, $date, $time);
                    $ri_rencana_rawat_pasien = new RIRencanaRawatPasien();
                    $ri_rencana_rawat_pasien->encounter = $encounter;
                    $ri_rencana_rawat_pasien->noreg = $noreg_terakhir;
                    $ri_rencana_rawat_pasien->id_careplan = $careplan->id;
                    $ri_rencana_rawat_pasien->save();
                } catch (\Throwable $th) {
                    $ri_rencana_rawat_pasien_log = new RIRencanaRawatPasienLog();
                    $ri_rencana_rawat_pasien_log->noreg = $noreg_terakhir;
                    $ri_rencana_rawat_pasien_log->ket_log = 'duplicate';
                    $ri_rencana_rawat_pasien_log->save();
                    return response()->json([
                        'noreg' => $noreg_terakhir,
                        'message' => "duplicate",
                        'nama schedule' => 'RI Rencana Rawat Pasien Log'
                    ], 200);
                }

                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => 'Semua Data sukses dikirim',
                    'nama schedule' => 'RI Rencana Rawat Pasien Log'
                ], 200);
            }
        // ===============================End CarePlan - Rencana Rawat Pasien===============================
    // ===============================End 02. Rencana Rawat Pasien===============================

    // ===============================07. Diagnosis===============================
        public function diagnosis(Request $request){
            set_time_limit((int) 0);
            $ri_diagnosis = RIDiagnosis::orderBy('noreg', 'desc')->pluck('noreg')->first();
            $ri_diagnosis_log = RIDiagnosisLog::orderBy('noreg', 'desc')->pluck('noreg')->first();
            if ($ri_diagnosis > $ri_diagnosis_log) {
                $noreg_terakhir = $ri_diagnosis;
            }elseif ($ri_diagnosis < $ri_diagnosis_log) {
                $noreg_terakhir = $ri_diagnosis_log;
            }elseif ($ri_diagnosis == $ri_diagnosis_log) {
                $noreg_terakhir = $ri_diagnosis;
            }

            $noreg_terakhir = $noreg_terakhir+1;
            $noreg_tanggal_depan = (substr($noreg_terakhir, 0, -4)+1)."0000";
            $data_tanggal_terakhir = MappingKunjunganInap::where('noreg', "<",$noreg_tanggal_depan)->orderBy('noreg', 'desc')->pluck('noreg')->first();
            if ($noreg_terakhir > $data_tanggal_terakhir) {
                $noreg_terakhir = $noreg_tanggal_depan+1;
            }

            //berhentikan sebelum noreg hari sekarang
            $now = Carbon::now()->setTimezone('Asia/Jakarta')->format('ymd');
            $noreg_batas = (Integer)($now . '0000');
            if ($noreg_terakhir > $noreg_batas) {
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "Noreg Dalam Pelayanan",
                    'nama schedule' => 'RI Diagnosis'
                ], 200);
            }

            if (substr($noreg_terakhir, 2, 4) == '1232') {
                $noreg_terakhir += 100000000;
                $noreg_terakhir = substr_replace($noreg_terakhir, '0101', 2, 4);
            }

            $data_terbesar = MappingKunjunganInap::orderBy('noreg', 'desc')->pluck('noreg')->first();
            if ($noreg_terakhir > $data_terbesar) {
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "Noreg Belum Terdaftar",
                    'nama schedule' => 'RI Diagnosis'
                ], 200);
            }
            $mapping_kunjungan_inap = MappingKunjunganInap::where('noreg', $noreg_terakhir)->first();
            if ($mapping_kunjungan_inap == null) {
                $ri_diagnosis_log = new RIDiagnosisLog();
                $ri_diagnosis_log->noreg = $noreg_terakhir;
                $ri_diagnosis_log->ket_log = 'Mapping kunjungan inap tidak ditemukan';
                $ri_diagnosis_log->save();
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "Mapping kunjungan inap tidak ditemukan",
                    'nama schedule' => 'RI Diagnosis'
                ], 200);
            }
            $registrasi_pasien = RegistrasiPasien::where('NOREG', $noreg_terakhir)->first();
            if ($registrasi_pasien == null) {
                $ri_diagnosis_log = new RIDiagnosisLog();
                $ri_diagnosis_log->noreg = $noreg_terakhir;
                $ri_diagnosis_log->ket_log = 'Noreg Belum Terdaftar';
                $ri_diagnosis_log->save();
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "Noreg Belum Terdaftar",
                    'nama schedule' => 'RI Diagnosis'
                ], 200);
            }
            $mapping_pasien = MappingPasien::where('norm', $registrasi_pasien->NOPASIEN)->first();
            if ($mapping_pasien == null) {
                $ri_diagnosis_log = new RIDiagnosisLog();
                $ri_diagnosis_log->noreg = $noreg_terakhir;
                $ri_diagnosis_log->ket_log = "Pasien Tidak Ditemukan Di Mapping Pasien";
                $ri_diagnosis_log->save();
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "Pasien Tidak Ditemukan Di Mapping Pasien",
                    'nama schedule' => 'RI Diagnosis'
                ], 200);
            }

            $mapping_dokter_spesialis = MappingDokterSpesialis::where('kodepelayanan', $registrasi_pasien->Registrasi_Dokter->KODEDOKTER)->first();
            if ($mapping_dokter_spesialis == null) {
                $ri_diagnosis_log = new RIDiagnosisLog();
                $ri_diagnosis_log->noreg = $noreg_terakhir;
                $ri_diagnosis_log->ket_log = "Dokter untuk Pasien ".$registrasi_pasien->Pasien->NAMAPASIEN." Tidak Ditemukan Di Mapping Dokter Spesialis";
                $ri_diagnosis_log->save();
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "Dokter untuk Pasien ".$registrasi_pasien->Pasien->NAMAPASIEN." Tidak Ditemukan Di Mapping Dokter Spesialis",
                    'nama schedule' => 'RI Diagnosis'
                ], 200);
            }

            $mapping_pasien = MappingPasien::where('norm', $registrasi_pasien->NOPASIEN)->first();

            $sep = Sep::where('tanggal_sep', Carbon::parse($registrasi_pasien->TGLREG)->format('Y-m-d'))
                ->where('no_rm', $registrasi_pasien->Pasien->NOPASIEN)
                ->where('no_kartu', $registrasi_pasien->Pasien->NOKARTU)
                // ->where('jenis_rawat', 'Rawat Jalan')
                ->select('no_sep')
                ->first();
            if ($sep == null){
                $ri_diagnosis_log = new RIDiagnosisLog();
                $ri_diagnosis_log->noreg = $noreg_terakhir;
                $ri_diagnosis_log->ket_log = 'Pasien Tidak Memiliki SEP';
                $ri_diagnosis_log->save();
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "Pasien Tidak Memiliki SEP",
                    'nama schedule' => 'RI diagnosis'
                ], 200);
            }
            $nomer_sep = $sep->no_sep;
            try {
                // ====================pengambilan sep lama====================
                    // $nosep = CheckinPoli::where('noreg', $noreg_terakhir)->first();
                    // $mappingsep = MappingSep::where('NOREG', $noreg_terakhir)->first();

                    // if ($nosep == null && $mappingsep == null){
                    //     $ri_diagnosis_log = new RIDiagnosisLog();
                    //     $ri_diagnosis_log->noreg = $noreg_terakhir;
                    //     $ri_diagnosis_log->ket_log = 'Pasien Tidak Memiliki SEP';
                    //     $ri_diagnosis_log->save();
                    //     return response()->json([
                    //         'noreg' => $noreg_terakhir,
                    //         'message' => "Pasien Tidak Memiliki SEP",
                    //         'nama schedule' => 'RI diagnosis'
                    //     ], 200);
                    // }

                    // if ($nosep != null && $nosep->nosep != null) {
                    //     $nomer_sep = $nosep->nosep;
                    // }elseif ($mappingsep != null && $mappingsep->SEP != null) {
                    //     $nomer_sep = $mappingsep->SEP;
                    // }else {
                    //     $ri_diagnosis_log = new RIDiagnosis();
                    //     $ri_diagnosis_log->noreg = $noreg_terakhir;
                    //     $ri_diagnosis_log->ket_log = 'Pasien Tidak Memiliki SEP';
                    //     $ri_diagnosis_log->save();
                    //     return response()->json([
                    //         'noreg' => $noreg_terakhir,
                    //         'message' => "Pasien Tidak Memiliki SEP",
                    //         'nama schedule' => 'RI diagnosis'
                    //     ], 200);
                    // }
                // ====================End pengambilan sep lama================

                $apiUrl = "http://10.10.6.13:10000/api/sep-new/{$nomer_sep}";
                $client = new Client();
                $response = $client->get($apiUrl);
                $apiData = $response->getBody()->getContents();
                $dataArray = json_decode($apiData, true);
                if ($dataArray['response'] == null) {
                    $ri_diagnosis_log = new LogDiagnosis();
                    $ri_diagnosis_log->noreg = $noreg_terakhir;
                    $ri_diagnosis_log->ket_log = 'Sep Tidak Ditemukan';
                    $ri_diagnosis_log->save();
                    return response()->json([
                        'noreg' => $noreg_terakhir,
                        'message' => "Sep Tidak Ditemukan",
                        'nama schedule' => 'RI diagnosis'
                    ], 200);
                }
                $diagnosa = explode(' - ', $dataArray['response']['diagnosa']);
                $kode_diagnosa = $diagnosa[0];
                $deskripsi_diagnosa = $diagnosa[1];
            } catch (\Throwable $th) {
                $dataArray = null;
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "gagal mengambil sep",
                    'nama schedule' => 'RI diagnosis'
                ], 200);
            }

            $kode_diagnosa = $kode_diagnosa;
            $deskripsi_diagnosa = $deskripsi_diagnosa;
            $id_patient = $mapping_pasien->kodesatusehat;
            $name_patient = $mapping_pasien->namasatusehat;
            $encounter = $mapping_kunjungan_inap->encounter;
            $date = Carbon::now()->setTimezone('Asia/Jakarta')->format('Y-m-d');
            $time = Carbon::now()->setTimezone('Asia/Jakarta')->format('H:i:s');

            if ($dataArray != null) {
                try {
                    $data = $this->condition->RI_diagnosis($kode_diagnosa, $deskripsi_diagnosa, $id_patient, $name_patient, $encounter, $date, $time);
                    $ri_diagnosis = new RIDiagnosis();
                    $ri_diagnosis->encounter = (String)$encounter;
                    $ri_diagnosis->noreg = (String)$noreg_terakhir;
                    $ri_diagnosis->kode_icd = (String)$kode_diagnosa;
                    $ri_diagnosis->nama_icd = (String)$deskripsi_diagnosa;
                    $ri_diagnosis->id_diagnosa = (String)$data->id;
                    $ri_diagnosis->save();
                } catch (\Throwable $th) {
                    $ri_diagnosis_log = new RIDiagnosisLog();
                    $ri_diagnosis_log->noreg = $noreg_terakhir;
                    $ri_diagnosis_log->ket_log = 'duplicate';
                    $ri_diagnosis_log->save();
                    return response()->json([
                        'noreg' => $noreg_terakhir,
                        'message' => "duplicate",
                        'nama schedule' => 'RI diagnosis'
                    ], 200);
                }
            }
            return response()->json([
                'noreg' => $noreg_terakhir,
                'message' => 'Data Berhasil Disimpan',
                'nama schedule' => 'RI diagnosis'
            ], 200);
        }
    // ===============================End 07. Diagnosis===========================
}
