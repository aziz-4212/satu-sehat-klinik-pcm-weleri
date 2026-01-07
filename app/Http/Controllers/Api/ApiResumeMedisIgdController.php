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
use App\Models\MappingKunjunganIgd;
use App\Models\MappingDokterSpesialis;
use App\Models\MappingOrganization;
use App\Models\LogEncounterIgd;
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
use App\Models\MedicationRequestModel;
use App\Models\LogMedicationRequest;
use App\Models\Mabar;
use App\Models\PractitionerModel;
use App\Models\MasterKfaObat;
use App\Models\Pasien;
use App\Models\LogMedicationDispense;
use App\Models\MedicationDispenseModel;
use App\Models\MappingSep;
use App\Models\CompositionModel;
use App\Models\LogComposition;
use App\Models\AssessmentAwalMedisRawatInapDiet;
use App\Models\AssessmentAwalMedisRawatInap;
use App\Models\LogMedicationStatement;
use App\Models\MedicationStatementModel;
use App\Models\LogQuestionnaireResponse;
use App\Models\QuestionnaireResponseModel;
use App\Models\CareplanRencanaRawatPasienModel;
use App\Models\LogCareplanRencanaRawatPasien;
use App\Models\BpjsRencanaKontrol;
use App\Models\ProcedureEdukasiNutrisi;
use App\Models\LogProcedureEdukasiNutrisi;
use App\Models\RawatJalanKeInap;
use App\Models\RjRawatInapInternal;
use App\Models\LogRjRawatInapInternal;
use App\Models\MappingKunjunganInap;
use App\Models\LogEncounterIgdInap;
use App\Models\SuratPersetujuanInap;
use App\Models\SuratPersetujuanNaikKelas;
use App\Models\RegRwi;
use App\Models\IGDSaranaTransportasiKedatangan;
use App\Models\IGDSaranaTransportasiKedatanganLog;
use App\Models\IGDDiagnosisAwalMasuk;
use App\Models\IGDDiagnosisAwalMasukLog;
use App\Models\AssessmentMedisIgd;
use App\Models\Sep;
use App\Models\Snomed;

use GuzzleHttp\Client;

class ApiResumeMedisIgdController extends Controller
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

    // ===============================02. Pendafataran Kunjungan IGD===============================
        // ===============================Pembuatan Kunjungan===============================
            public function encounter_masuk_kunjungan_igd_store($noreg_terakhir = null){
                set_time_limit((int) 0);
                if ($noreg_terakhir == null) {
                    $mapping_kunjungan_igd = MappingKunjunganIgd::orderBy('noreg', 'desc')->pluck('noreg')->first();
                    $log_encounter_igd = LogEncounterIgd::orderBy('noreg', 'desc')->pluck('noreg')->first();
                    if ($mapping_kunjungan_igd > $log_encounter_igd) {
                        $registrasi_pasien_terakhir = $mapping_kunjungan_igd;
                    }elseif ($mapping_kunjungan_igd < $log_encounter_igd) {
                        $registrasi_pasien_terakhir = $log_encounter_igd;
                    }elseif ($mapping_kunjungan_igd == $log_encounter_igd) {
                        $registrasi_pasien_terakhir = $mapping_kunjungan_igd;
                    }

                    $registrasi_pasien_terakhir = $registrasi_pasien_terakhir+1;
                    $noreg_tanggal_depan = (substr($registrasi_pasien_terakhir, 0, -4)+1)."0000";
                    $registrasi_pasien_tanggal_terakhir = RegistrasiPasien::where('NOREG', "<",$noreg_tanggal_depan)->orderBy('noreg', 'desc')->pluck('noreg')->first();
                    if ($registrasi_pasien_terakhir > $registrasi_pasien_tanggal_terakhir) {
                        $registrasi_pasien_terakhir = $noreg_tanggal_depan+1;
                    }

                    $batas_pengiriman = Carbon::now()->setTimezone('Asia/Jakarta')->format('ymd')."0000";
                    if ($registrasi_pasien_terakhir > $batas_pengiriman) {
                        return response()->json([
                            'noreg' => $registrasi_pasien_terakhir,
                            'message' => "Noreg Belum Terdaftar",
                            'nama schedule' => 'pendaftaran pendataan pasien IGD'
                        ], 200);
                    }
                    $registrasi_pasien = RegistrasiPasien::where('NOREG', $registrasi_pasien_terakhir)
                                    ->whereHas('Registrasi_Dokter', function ($query) {
                                        $query->where('BAGREGDR', 'like', '95%');
                                        // $query->where('BAGREGDR', 'like', '91%')->orWhere('BAGREGDR', 'like', '93%')->orWhere('BAGREGDR', 'like', '95%');
                                    })->orderBy('NOREG')
                                    ->whereHas('Pasien', function ($query) {
                                        $query->where('NOKTP', '!=', null);
                                        $query->where('NOKTP', 'not like', '0%');
                                    })
                                    ->first();
                }else {
                    $registrasi_pasien_terakhir = (Integer)$noreg_terakhir;
                    $registrasi_pasien = RegistrasiPasien::where('NOREG', $registrasi_pasien_terakhir)
                                    ->orderBy('NOREG')
                                    ->whereHas('Pasien', function ($query) {
                                        $query->where('NOKTP', '!=', null);
                                        $query->where('NOKTP', 'not like', '0%');
                                    })
                                    ->first();
                }

                if ($registrasi_pasien == null) {
                    $log_encounter_igd = new LogEncounterIgd();
                    $log_encounter_igd->noreg = $registrasi_pasien_terakhir;
                    $log_encounter_igd->ket_log = "noreg bukan pasien igd";
                    $log_encounter_igd->save();

                    return response()->json([
                        'noreg' => $registrasi_pasien_terakhir,
                        'message' => "noreg bukan pasien igd",
                        'nama schedule' => 'pendaftaran pendataan pasien IGD'
                    ], 200);
                }

                $now = Carbon::now()->setTimezone('Asia/Jakarta');
                $now->format('Y-m-d H:i:s.v');
                $registrasi_pasien = RegistrasiPasien::where('NOREG', $registrasi_pasien_terakhir)->first();
                $mapping_dokter_spesialis = MappingDokterSpesialis::where('kodepelayanan', $registrasi_pasien->Registrasi_Dokter->KODEDOKTER)->first();
                $practitioner = PractitionerModel::where('nama', 'like', trim(strtok($registrasi_pasien->Registrasi_Dokter->dokter->NAMADOKTER, ',')))->first();
                if ($mapping_dokter_spesialis == null && $practitioner == null) {
                    $log_encounter_igd = new LogEncounterIgd();
                    $log_encounter_igd->noreg = $registrasi_pasien_terakhir;
                    $log_encounter_igd->ket_log = 'Dokter untuk Pasien '.$registrasi_pasien->Pasien->NAMAPASIEN.' Tidak Ditemukan Di Mapping Dokter Spesialis';
                    $log_encounter_igd->save();
                    return response()->json([
                        'noreg' => $registrasi_pasien_terakhir,
                        'message' => 'Dokter untuk Pasien '.$registrasi_pasien->Pasien->NAMAPASIEN.' Tidak Ditemukan Di Mapping Dokter Spesialis',
                        'nama schedule' => 'pendaftaran pendataan pasien IGD'
                    ], 200);
                }elseif ($mapping_dokter_spesialis != null) {
                    $id_practitioner = $mapping_dokter_spesialis->satusehat;
                    $name_practitioner = trim($mapping_dokter_spesialis->nama);
                }elseif ($practitioner != null) {
                    $id_practitioner = $practitioner->id_practitioner;
                    $name_practitioner = trim($practitioner->nama);
                }
                $mapping_organization = MappingOrganization::where('koders', '9510')->first();
                // ============================== cek format NIK ==============================
                    $pattern = '/^\d{16}$/';

                    if (!preg_match($pattern, $registrasi_pasien->Pasien->NOKTP)) {
                        $log_encounter_igd = new LogEncounterIgd();
                        $log_encounter_igd->noreg = $registrasi_pasien_terakhir;
                        $log_encounter_igd->ket_log = 'NIK Tidak Valid';
                        $log_encounter_igd->save();

                        return response()->json([
                            'noreg' => $registrasi_pasien_terakhir,
                            'message' => 'NIK Tidak Valid',
                            'nama schedule' => 'pendaftaran pendataan pasien IGD'
                        ], 200);
                    }
                // ============================== cek format NIK ==============================
                $data = $this->patient->search_nik($registrasi_pasien->Pasien->NOKTP);
                if ($data->total != 0) {
                    $cek_mapping_pasien = MappingPasien::where('nik', $registrasi_pasien->Pasien->NOKTP)->first();
                    if ($cek_mapping_pasien == null) {
                        $mapping_pasien = new MappingPasien();
                        $mapping_pasien->norm = $registrasi_pasien->Pasien->NOPASIEN;
                        $mapping_pasien->nik = $registrasi_pasien->Pasien->NOKTP;
                        $mapping_pasien->nama = $registrasi_pasien->Pasien->NAMAPASIEN;
                        $mapping_pasien->namasatusehat = $data->entry[0]->resource->name[0]->text;
                        $mapping_pasien->kodesatusehat = $data->entry[0]->resource->id;
                        $mapping_pasien->save();
                    }
                }else {
                    $log_encounter_igd = new LogEncounterIgd();
                    $log_encounter_igd->noreg = $registrasi_pasien_terakhir;
                    $log_encounter_igd->ket_log = 'NIK '.$registrasi_pasien->Pasien->NAMAPASIEN.' Tidak Ditemukan';
                    $log_encounter_igd->save();
                    return response()->json([
                        'noreg' => $registrasi_pasien_terakhir,
                        'message' => 'NIK '.$registrasi_pasien->Pasien->NAMAPASIEN.' Tidak Ditemukan',
                        'nama schedule' => 'pendaftaran pendataan pasien IGD'
                    ], 200);
                }
                // ===================================Encounter==============================================
                    try {
                        $noreg = $registrasi_pasien_terakhir;
                        $id_patient = $data->entry[0]->resource->id;
                        $name_patient = $data->entry[0]->resource->name[0]->text;
                        $id_practitioner = $id_practitioner;
                        $name_practitioner = trim($name_practitioner);
                        $date = Carbon::parse($registrasi_pasien->TGLREG)->format('Y-m-d');
                        $time = Carbon::parse($registrasi_pasien->JAMREG)->format('H:i:s');
                        $id_location = $mapping_organization->location->kodesatusehat;
                        $name_location = $mapping_organization->location->deskripsi;

                        $encounter = $this->encounter->create_masuk_kunjungan_igd($noreg, $id_patient, $name_patient, $id_practitioner, $name_practitioner, $date, $time, $id_location, $name_location);
                        $mapping_kunjungan_igd = new MappingKunjunganIgd();
                        $mapping_kunjungan_igd->noreg = $registrasi_pasien_terakhir;
                        $mapping_kunjungan_igd->encounter = $encounter->id;
                        $mapping_kunjungan_igd->tanggal = $now;
                        $mapping_kunjungan_igd->save();
                    } catch (\Throwable $th) {
                        $log_encounter_igd = new LogEncounterIgd();
                        $log_encounter_igd->noreg = $registrasi_pasien_terakhir;
                        $log_encounter_igd->ket_log = $th->getMessage();
                        $log_encounter_igd->save();
                    }
                // ===================================End Encounter==============================================
                return response()->json([
                    'noreg' => $registrasi_pasien_terakhir,
                    'message' => 'Data Berhasil Disimpan',
                    'nama schedule' => 'pendaftaran pendataan pasien IGD'
                ], 200);
            }
        // ===============================End Pembuatan Kunjungan===============================
    // ===============================End 02. Pendafataran Kunjungan IGD===============================

    // ===============================03. Data Triase dan Gawat darurat===============================
        // ===============================Sarana Transportasi Kedatangan===============================
            public function observation_sarana_transportasi_kedatangan(Request $request){
                set_time_limit((int) 0);
                $igd_sarana_transportasi_kedatangan = IGDSaranaTransportasiKedatangan::orderBy('noreg', 'desc')->pluck('noreg')->first();
                $igd_sarana_transportasi_kedatangan_log = IGDSaranaTransportasiKedatanganLog::orderBy('noreg', 'desc')->pluck('noreg')->first();
                if ($igd_sarana_transportasi_kedatangan > $igd_sarana_transportasi_kedatangan_log) {
                    $noreg_terakhir = $igd_sarana_transportasi_kedatangan;
                }elseif ($igd_sarana_transportasi_kedatangan < $igd_sarana_transportasi_kedatangan_log) {
                    $noreg_terakhir = $igd_sarana_transportasi_kedatangan_log;
                }elseif ($igd_sarana_transportasi_kedatangan == $igd_sarana_transportasi_kedatangan_log) {
                    $noreg_terakhir = $igd_sarana_transportasi_kedatangan;
                }

                $noreg_terakhir = $noreg_terakhir+1;
                $noreg_tanggal_depan = (substr($noreg_terakhir, 0, -4)+1)."0000";
                $data_tanggal_terakhir = MappingKunjunganIgd::where('noreg', "<",$noreg_tanggal_depan)->orderBy('noreg', 'desc')->pluck('noreg')->first();
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
                        'nama schedule' => 'IGD Sarana Transportasi Kedatangan'
                    ], 200);
                }

                if (substr($noreg_terakhir, 2, 4) == '1232') {
                    $noreg_terakhir += 100000000;
                    $noreg_terakhir = substr_replace($noreg_terakhir, '0101', 2, 4);
                }

                $data_terbesar = MappingKunjunganIgd::orderBy('noreg', 'desc')->pluck('noreg')->first();
                if ($noreg_terakhir > $data_terbesar) {
                    return response()->json([
                        'noreg' => $noreg_terakhir,
                        'message' => "Noreg Belum Terdaftar",
                        'nama schedule' => 'IGD Sarana Transportasi Kedatangan'
                    ], 200);
                }
                // $noreg_terakhir = "2401020190";
                // if ($noreg_terakhir >= 2401020189) {
                //     return response()->json([
                //         'noreg' => $noreg_terakhir,
                //         'message' => "ujicoba sudah selesai bos",
                //         'nama schedule' => 'IGD Sarana Transportasi Kedatangan'
                //     ], 200);
                // }
                // dd($noreg_terakhir);
                $mapping_kunjungan_igd = MappingKunjunganIgd::where('noreg', $noreg_terakhir)->first();
                if ($mapping_kunjungan_igd == null) {
                    $igd_sarana_transportasi_kedatangan_log = new IGDSaranaTransportasiKedatanganLog();
                    $igd_sarana_transportasi_kedatangan_log->noreg = $noreg_terakhir;
                    $igd_sarana_transportasi_kedatangan_log->ket_log = 'Mapping kunjungan IGD tidak ditemukan';
                    $igd_sarana_transportasi_kedatangan_log->save();
                    return response()->json([
                        'noreg' => $noreg_terakhir,
                        'message' => "Mapping kunjungan IGD tidak ditemukan",
                        'nama schedule' => 'IGD Sarana Transportasi Kedatangan'
                    ], 200);
                }

                $registrasi_pasien = RegistrasiPasien::where('NOREG', $noreg_terakhir)->first();
                if ($registrasi_pasien == null) {
                    $igd_sarana_transportasi_kedatangan_log = new IGDSaranaTransportasiKedatanganLog();
                    $igd_sarana_transportasi_kedatangan_log->noreg = $noreg_terakhir;
                    $igd_sarana_transportasi_kedatangan_log->ket_log = 'Noreg Belum Terdaftar';
                    $igd_sarana_transportasi_kedatangan_log->save();
                    return response()->json([
                        'noreg' => $noreg_terakhir,
                        'message' => "Noreg Belum Terdaftar",
                        'nama schedule' => 'IGD Sarana Transportasi Kedatangan'
                    ], 200);
                }
                $mapping_pasien = MappingPasien::where('norm', $registrasi_pasien->NOPASIEN)->first();
                if ($mapping_pasien == null) {
                    $igd_sarana_transportasi_kedatangan_log = new IGDSaranaTransportasiKedatanganLog();
                    $igd_sarana_transportasi_kedatangan_log->noreg = $noreg_terakhir;
                    $igd_sarana_transportasi_kedatangan_log->ket_log = "Pasien Tidak Ditemukan Di Mapping Pasien";
                    $igd_sarana_transportasi_kedatangan_log->save();
                    return response()->json([
                        'noreg' => $noreg_terakhir,
                        'message' => "Pasien Tidak Ditemukan Di Mapping Pasien",
                        'nama schedule' => 'IGD Sarana Transportasi Kedatangan'
                    ], 200);
                }

                $mapping_dokter_spesialis = MappingDokterSpesialis::where('kodepelayanan', $registrasi_pasien->Registrasi_Dokter->KODEDOKTER)->first();
                $practitioner = PractitionerModel::where('nama', 'like', trim(strtok($registrasi_pasien->Registrasi_Dokter->dokter->NAMADOKTER, ',')))->first();
                if ($mapping_dokter_spesialis == null && $practitioner == null) {
                    $igd_sarana_transportasi_kedatangan_log = new IGDSaranaTransportasiKedatanganLog();
                    $igd_sarana_transportasi_kedatangan_log->noreg = $noreg_terakhir;
                    $igd_sarana_transportasi_kedatangan_log->ket_log = "Dokter untuk Pasien ".$registrasi_pasien->Pasien->NAMAPASIEN." Tidak Ditemukan Di Mapping Dokter Spesialis";
                    $igd_sarana_transportasi_kedatangan_log->save();
                    return response()->json([
                        'noreg' => $noreg_terakhir,
                        'message' => "Dokter untuk Pasien ".$registrasi_pasien->Pasien->NAMAPASIEN." Tidak Ditemukan Di Mapping Dokter Spesialis",
                        'nama schedule' => 'IGD Sarana Transportasi Kedatangan'
                    ], 200);
                }elseif ($mapping_dokter_spesialis != null) {
                    $id_practitioner = $mapping_dokter_spesialis->satusehat;
                }elseif ($practitioner != null) {
                    $id_practitioner = $practitioner->id_practitioner;
                }

                $assessment_medis_igd = AssessmentMedisIgd::where('noreg', $noreg_terakhir)->select('transportasi_yang_digunakan_kursi_roda', 'transportasi_yang_digunakan_brankar', 'transportasi_yang_digunakan_ambulance', 'transportasi_yang_digunakan_kendaraan_lain')->first();
                if ($assessment_medis_igd == null) {
                    $igd_sarana_transportasi_kedatangan_log = new IGDSaranaTransportasiKedatanganLog();
                    $igd_sarana_transportasi_kedatangan_log->noreg = $noreg_terakhir;
                    $igd_sarana_transportasi_kedatangan_log->ket_log = "Data Assessment Medis IGD Tidak Ditemukan";
                    $igd_sarana_transportasi_kedatangan_log->save();
                    return response()->json([
                        'noreg' => $noreg_terakhir,
                        'message' => "Data Assessment Medis IGD Tidak Ditemukan",
                        'nama schedule' => 'IGD Sarana Transportasi Kedatangan'
                    ], 200);
                }
                $id_patient = $mapping_pasien->kodesatusehat;
                $id_patient_display = $mapping_pasien->namasatusehat;
                $encounter = $mapping_kunjungan_igd->encounter;
                $date = Carbon::now()->setTimezone('Asia/Jakarta')->format('Y-m-d');
                $time = Carbon::now()->setTimezone('Asia/Jakarta')->format('H:i:s');

                if ($assessment_medis_igd->transportasi_yang_digunakan_kursi_roda) {
                    $system = "http://loinc.org";
                    $code = "LA46-8";
                    $display = "Other";
                }elseif ($assessment_medis_igd->transportasi_yang_digunakan_brankar) {
                    $system = "http://loinc.org";
                    $code = "LA46-8";
                    $display = "Other";
                }elseif ($assessment_medis_igd->transportasi_yang_digunakan_ambulance) {
                    $system = "http://loinc.org";
                    $code = "LA9315-8";
                    $display = "Ground ambulance";
                }elseif ($assessment_medis_igd->transportasi_yang_digunakan_kendaraan_lain) {
                    $system = "http://loinc.org";
                    $code = "LA46-8";
                    $display = "Other";
                }
                try {
                    $data = $this->observasion->IGD_sarana_transportasi_kedatangan($id_patient, $id_patient_display, $encounter, $id_practitioner, $date, $time, $system, $code, $display);
                    $igd_sarana_transportasi_kedatangan = new IGDSaranaTransportasiKedatangan();
                    $igd_sarana_transportasi_kedatangan->encounter = (String)$encounter;
                    $igd_sarana_transportasi_kedatangan->noreg = (String)$noreg_terakhir;
                    $igd_sarana_transportasi_kedatangan->id_observation = (String)$data->id;
                    $igd_sarana_transportasi_kedatangan->save();
                } catch (\Throwable $th) {
                    $igd_sarana_transportasi_kedatangan_log = new IGDSaranaTransportasiKedatanganLog();
                    $igd_sarana_transportasi_kedatangan_log->noreg = $noreg_terakhir;
                    $igd_sarana_transportasi_kedatangan_log->ket_log = 'duplicate';
                    $igd_sarana_transportasi_kedatangan_log->save();
                    return response()->json([
                        'noreg' => $noreg_terakhir,
                        'message' => "duplicate",
                        'nama schedule' => 'IGD Sarana Transportasi Kedatangan'
                    ], 200);
                }
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => 'Data Berhasil Disimpan',
                    'nama schedule' => 'IGD Sarana Transportasi Kedatangan'
                ], 200);
            }
        // ===============================End Sarana Transportasi Kedatangan===============================
    // ===============================End 03. Data Triase dan Gawat darurat===============================

    // ===============================14. Diagnosis===============================
        public function diagnosis_awal_masuk(Request $request){
            set_time_limit((int) 0);
            $diagnosis_awal_masuk = IGDDiagnosisAwalMasuk::orderBy('noreg', 'desc')->pluck('noreg')->first();
            $diagnosis_awal_masuk_log = IGDDiagnosisAwalMasukLog::orderBy('noreg', 'desc')->pluck('noreg')->first();
            if ($diagnosis_awal_masuk > $diagnosis_awal_masuk_log) {
                $noreg_terakhir = $diagnosis_awal_masuk;
            }elseif ($diagnosis_awal_masuk < $diagnosis_awal_masuk_log) {
                $noreg_terakhir = $diagnosis_awal_masuk_log;
            }elseif ($diagnosis_awal_masuk == $diagnosis_awal_masuk_log) {
                $noreg_terakhir = $diagnosis_awal_masuk;
            }

            $noreg_terakhir = $noreg_terakhir+1;
            $noreg_tanggal_depan = (substr($noreg_terakhir, 0, -4)+1)."0000";
            $data_tanggal_terakhir = MappingKunjunganIgd::where('noreg', "<",$noreg_tanggal_depan)->orderBy('noreg', 'desc')->pluck('noreg')->first();
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
                    'nama schedule' => 'IGD Diagnosis Awal Masuk'
                ], 200);
            }

            if (substr($noreg_terakhir, 2, 4) == '1232') {
                $noreg_terakhir += 100000000;
                $noreg_terakhir = substr_replace($noreg_terakhir, '0101', 2, 4);
            }

            $data_terbesar = MappingKunjunganIgd::orderBy('noreg', 'desc')->pluck('noreg')->first();
            if ($noreg_terakhir > $data_terbesar) {
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "Noreg Belum Terdaftar",
                    'nama schedule' => 'IGD Diagnosis Awal Masuk'
                ], 200);
            }

            $noreg_terakhir = '2401010024';

            $mapping_kunjungan_igd = MappingKunjunganIgd::where('noreg', $noreg_terakhir)->first();
            if ($mapping_kunjungan_igd == null) {
                $diagnosis_awal_masuk_log = new IGDDiagnosisAwalMasukLog();
                $diagnosis_awal_masuk_log->noreg = $noreg_terakhir;
                $diagnosis_awal_masuk_log->ket_log = 'Mapping kunjungan IGD tidak ditemukan';
                $diagnosis_awal_masuk_log->save();
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "Mapping kunjungan IGD tidak ditemukan",
                    'nama schedule' => 'IGD Diagnosis Awal Masuk'
                ], 200);
            }

            $registrasi_pasien = RegistrasiPasien::where('NOREG', $noreg_terakhir)->first();
            if ($registrasi_pasien == null) {
                $diagnosis_awal_masuk_log = new IGDDiagnosisAwalMasukLog();
                $diagnosis_awal_masuk_log->noreg = $noreg_terakhir;
                $diagnosis_awal_masuk_log->ket_log = 'Noreg Belum Terdaftar';
                $diagnosis_awal_masuk_log->save();
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "Noreg Belum Terdaftar",
                    'nama schedule' => 'IGD Diagnosis Awal Masuk'
                ], 200);
            }
            $mapping_pasien = MappingPasien::where('norm', $registrasi_pasien->NOPASIEN)->first();
            if ($mapping_pasien == null) {
                $diagnosis_awal_masuk_log = new IGDDiagnosisAwalMasukLog();
                $diagnosis_awal_masuk_log->noreg = $noreg_terakhir;
                $diagnosis_awal_masuk_log->ket_log = "Pasien Tidak Ditemukan Di Mapping Pasien";
                $diagnosis_awal_masuk_log->save();
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "Pasien Tidak Ditemukan Di Mapping Pasien",
                    'nama schedule' => 'IGD Diagnosis Awal Masuk'
                ], 200);
            }

            $mapping_dokter_spesialis = MappingDokterSpesialis::where('kodepelayanan', $registrasi_pasien->Registrasi_Dokter->KODEDOKTER)->first();
            if ($mapping_dokter_spesialis == null) {
                $diagnosis_awal_masuk_log = new IGDDiagnosisAwalMasukLog();
                $diagnosis_awal_masuk_log->noreg = $noreg_terakhir;
                $diagnosis_awal_masuk_log->ket_log = "Dokter untuk Pasien ".$registrasi_pasien->Pasien->NAMAPASIEN." Tidak Ditemukan Di Mapping Dokter Spesialis";
                $diagnosis_awal_masuk_log->save();
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "Dokter untuk Pasien ".$registrasi_pasien->Pasien->NAMAPASIEN." Tidak Ditemukan Di Mapping Dokter Spesialis",
                    'nama schedule' => 'IGD Diagnosis Awal Masuk'
                ], 200);
            }

            $sep = Sep::where('tanggal_sep', Carbon::parse($registrasi_pasien->TGLREG)->format('Y-m-d'))
                ->where('no_rm', $registrasi_pasien->Pasien->NOPASIEN)
                ->where('no_kartu', $registrasi_pasien->Pasien->NOKARTU)
                ->select('no_sep')
                ->first();
            if ($sep == null){
                $diagnosis_awal_masuk_log = new IGDDiagnosisAwalMasukLog();
                $diagnosis_awal_masuk_log->noreg = $noreg_terakhir;
                $diagnosis_awal_masuk_log->ket_log = 'Pasien Tidak Memiliki SEP';
                $diagnosis_awal_masuk_log->save();
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "Pasien Tidak Memiliki SEP",
                    'nama schedule' => 'IGD Diagnosis Awal Masuk'
                ], 200);
            }
            $nomer_sep = $sep->no_sep;

            try {
                $apiUrl = "http://10.10.6.13:10000/api/sep-new/{$nomer_sep}";
                $client = new Client();
                $response = $client->get($apiUrl);
                $apiData = $response->getBody()->getContents();
                $dataArray = json_decode($apiData, true);
                if ($dataArray['response'] == null) {
                    $diagnosis_awal_masuk_log = new IGDDiagnosisAwalMasukLog();
                    $diagnosis_awal_masuk_log->noreg = $noreg_terakhir;
                    $diagnosis_awal_masuk_log->ket_log = 'Sep Tidak Ditemukan';
                    $diagnosis_awal_masuk_log->save();
                    return response()->json([
                        'noreg' => $noreg_terakhir,
                        'message' => "Sep Tidak Ditemukan",
                        'nama schedule' => 'IGD Diagnosis Awal Masuk'
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
                    'nama schedule' => 'IGD Diagnosis Awal Masuk'
                ], 200);
            }

            // ==========================ambil snomed============================
                $keyword = $deskripsi_diagnosa;
                $snomedTerms = Snomed::select('id', 'term')->get();

                $closestMatch = null;
                $shortestDistance = -1;

                foreach ($snomedTerms as $term) {
                    $distance = levenshtein($keyword, $term->term);
                    if ($shortestDistance == -1 || $distance < $shortestDistance) {
                        $closestMatch = $term;
                        $shortestDistance = $distance;
                    }
                }

                if ($closestMatch->term == $keyword) {
                    $kode_snomed = $closestMatch->id;
                    $deskripsi_snomed = $closestMatch->term;
                }else {
                    $diagnosis_awal_masuk_log = new IGDDiagnosisAwalMasukLog();
                    $diagnosis_awal_masuk_log->noreg = $noreg_terakhir;
                    $diagnosis_awal_masuk_log->ket_log = "snomed tidak ditemukan = ".$closestMatch->term." - ".$keyword;
                    $diagnosis_awal_masuk_log->save();
                    return response()->json([
                        'noreg' => $noreg_terakhir,
                        'message' => "snomed tidak ditemukan = ".$closestMatch->term." - ".$keyword,
                        'nama schedule' => 'IGD Diagnosis Awal Masuk'
                    ], 200);
                }
            // ==========================End ambil snomed============================


            $kode_diagnosa = $kode_diagnosa;
            $deskripsi_diagnosa = $deskripsi_diagnosa;
            $id_patient = $mapping_pasien->kodesatusehat;
            $name_patient = $mapping_pasien->namasatusehat;
            $encounter = $mapping_kunjungan_igd->encounter;
            $date = Carbon::now()->setTimezone('Asia/Jakarta')->format('Y-m-d');
            $time = Carbon::now()->setTimezone('Asia/Jakarta')->format('H:i:s');

            // try {
                $data = $this->condition->IGD_diagnosis_awal_masuk($kode_diagnosa, $deskripsi_diagnosa, $kode_snomed, $deskripsi_snomed, $id_patient, $name_patient, $encounter, $date, $time);
                dd($data);
                $diagnosis_awal_masuk = new IGDDiagnosisAwalMasuk();
                $diagnosis_awal_masuk->encounter = (String)$encounter;
                $diagnosis_awal_masuk->noreg = (String)$noreg_terakhir;
                $diagnosis_awal_masuk->kode_icd = (String)$kode_diagnosa;
                $diagnosis_awal_masuk->nama_icd = (String)$deskripsi_diagnosa;
                $diagnosis_awal_masuk->id_diagnosa = (String)$data->id;
                $diagnosis_awal_masuk->save();
            // } catch (\Throwable $th) {
            //     $diagnosis_awal_masuk_log = new IGDDiagnosisAwalMasukLog();
            //     $diagnosis_awal_masuk_log->noreg = $noreg_terakhir;
            //     $diagnosis_awal_masuk_log->ket_log = 'duplicate';
            //     $diagnosis_awal_masuk_log->save();
            //     return response()->json([
            //         'noreg' => $noreg_terakhir,
            //         'message' => "duplicate",
            //         'nama schedule' => 'IGD Diagnosis Awal Masuk'
            //     ], 200);
            // }
            return response()->json([
                'noreg' => $noreg_terakhir,
                'message' => 'Data Berhasil Disimpan',
                'nama schedule' => 'IGD Diagnosis Awal Masuk'
            ], 200);
        }
    // ===============================End 14. Diagnosis===========================
}
