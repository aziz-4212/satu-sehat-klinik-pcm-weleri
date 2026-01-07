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
use App\Models\MedicationRequestModel;
use App\Models\LogMedicationRequest;
use App\Models\Mabar;
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
use App\Models\SuratPersetujuanInap;
use App\Models\SuratPersetujuanNaikKelas;
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

    // ===============================Pendafataran Pasien===============================
        public function pendaftaran_pendataan_pasien_store(){
            set_time_limit((int) 0);
            // $mapping_kunjungan_inap = MappingKunjunganInap::orderBy('noreg', 'desc')->pluck('noreg')->first();
            // $log_encounter_inap = LogEncounterInap::orderBy('noreg', 'desc')->pluck('noreg')->first();
            // if ($mapping_kunjungan_inap > $log_encounter_inap) {
            //     $registrasi_pasien_terakhir = $mapping_kunjungan_inap;
            // }elseif ($mapping_kunjungan_inap < $log_encounter_inap) {
            //     $registrasi_pasien_terakhir = $log_encounter_inap;
            // }elseif ($mapping_kunjungan_inap == $log_encounter_inap) {
            //     $registrasi_pasien_terakhir = $mapping_kunjungan_inap;
            // }

            // $registrasi_pasien_terakhir = $registrasi_pasien_terakhir+1;
            // $noreg_tanggal_depan = (substr($registrasi_pasien_terakhir, 0, -4)+1)."0000";
            // $registrasi_pasien_tanggal_terakhir = RegistrasiPasien::where('NOREG', "<",$noreg_tanggal_depan)->orderBy('noreg', 'desc')->pluck('noreg')->first();
            // if ($registrasi_pasien_terakhir > $registrasi_pasien_tanggal_terakhir) {
            //     $registrasi_pasien_terakhir = $noreg_tanggal_depan+1;
            // }

            // $batas_pengiriman = Carbon::now()->setTimezone('Asia/Jakarta')->format('ymd')."0000";
            // if ($registrasi_pasien_terakhir > $batas_pengiriman) {
            //     return response()->json([
            //         'noreg' => $registrasi_pasien_terakhir,
            //         'message' => "Noreg Belum Terdaftar",
            //         'nama schedule' => 'pendaftaran pendataan pasien Inap'
            //     ], 200);
            // }
            $registrasi_pasien_terakhir = '2402200504';
            $registrasi_pasien = RegistrasiPasien::where('NOREG', $registrasi_pasien_terakhir)
                                ->whereHas('Registrasi_Dokter', function ($query) {
                                    $query->where('BAGREGDR', 'like', '93%');
                                    // $query->where('BAGREGDR', 'like', '91%')->orWhere('BAGREGDR', 'like', '93%')->orWhere('BAGREGDR', 'like', '95%');
                                })->orderBy('NOREG')
                                ->whereHas('Pasien', function ($query) {
                                    $query->where('NOKTP', '!=', null);
                                    $query->where('NOKTP', 'not like', '0%');
                                })
                                ->first();
            if ($registrasi_pasien == null) {
                $log_encounter_inap = new LogEncounterInap();
                $log_encounter_inap->noreg = $registrasi_pasien_terakhir;
                $log_encounter_inap->ket_log = "noreg bukan pasien rawat inap";
                $log_encounter_inap->save();

                return response()->json([
                    'noreg' => $registrasi_pasien_terakhir,
                    'message' => "noreg bukan pasien rawat inap",
                    'nama schedule' => 'pendaftaran pendataan pasien Inap'
                ], 200);
            }

            $now = Carbon::now()->setTimezone('Asia/Jakarta');
            $now->format('Y-m-d H:i:s.v');
            $registrasi_pasien = RegistrasiPasien::where('NOREG', $registrasi_pasien_terakhir)->first();
            $mapping_dokter_spesialis = MappingDokterSpesialis::where('kodepelayanan', $registrasi_pasien->Registrasi_Dokter->KODEDOKTER)->first();
            if ($mapping_dokter_spesialis == null) {
                $log_encounter_inap = new LogEncounterInap();
                $log_encounter_inap->noreg = $registrasi_pasien_terakhir;
                $log_encounter_inap->ket_log = 'Dokter untuk Pasien '.$registrasi_pasien->Pasien->NAMAPASIEN.' Tidak Ditemukan Di Mapping Dokter Spesialis';
                $log_encounter_inap->save();
                return response()->json([
                    'noreg' => $registrasi_pasien_terakhir,
                    'message' => 'Dokter untuk Pasien '.$registrasi_pasien->Pasien->NAMAPASIEN.' Tidak Ditemukan Di Mapping Dokter Spesialis',
                    'nama schedule' => 'pendaftaran pendataan pasien Inap'
                ], 200);
            }
            $mapping_organization = MappingOrganization::where('koders', $registrasi_pasien->Registrasi_Dokter->BAGREGDR)->first();
            // ============================== cek format NIK ==============================
                $pattern = '/^\d{16}$/';

                if (!preg_match($pattern, $registrasi_pasien->Pasien->NOKTP)) {
                    $log_encounter_inap = new LogEncounterInap();
                    $log_encounter_inap->noreg = $registrasi_pasien_terakhir;
                    $log_encounter_inap->ket_log = 'NIK Tidak Valid';
                    $log_encounter_inap->save();

                    return response()->json([
                        'noreg' => $registrasi_pasien_terakhir,
                        'message' => 'NIK Tidak Valid',
                        'nama schedule' => 'pendaftaran pendataan pasien Inap'
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
                $log_encounter_inap = new LogEncounterInap();
                $log_encounter_inap->noreg = $registrasi_pasien_terakhir;
                $log_encounter_inap->ket_log = 'NIK '.$registrasi_pasien->Pasien->NAMAPASIEN.' Tidak Ditemukan';
                $log_encounter_inap->save();
                return response()->json([
                    'noreg' => $registrasi_pasien_terakhir,
                    'message' => 'NIK '.$registrasi_pasien->Pasien->NAMAPASIEN.' Tidak Ditemukan',
                    'nama schedule' => 'pendaftaran pendataan pasien Inap'
                ], 200);
            }

            // =================================kelas=========================================

            $surat_persetujuan_naik_kelas = SuratPersetujuanNaikKelas::where('noreg', $registrasi_pasien_terakhir)->first();
            $surat_persetujuan_inap = SuratPersetujuanInap::where('noreg', $registrasi_pasien_terakhir)->first();
            if ($surat_persetujuan_naik_kelas != null) {
                if ($surat_persetujuan_naik_kelas->naik_ke_kelas = 'diatas kelas 1') {
                    $kelas = 'vip';
                }else {
                    $kelas = $surat_persetujuan_naik_kelas->naik_ke_kelas;
                }
            }elseif ($surat_persetujuan_inap != null) {
                $kelas = $surat_persetujuan_inap->kelas_ruang_inap;
            }else {
                $kelas = '2';
            }
            // =================================End kelas=========================================
            // ===================================Encounter==============================================
                try {
                    $dateArray = explode(' ', $registrasi_pasien->TGLREG);
                    $dateValue = $dateArray[0];

                    $id_patient = $data->entry[0]->resource->id;
                    $name_patient = $data->entry[0]->resource->name[0]->text;
                    $id_practitioner = $mapping_dokter_spesialis->satusehat;
                    $name_practitioner = trim($mapping_dokter_spesialis->nama);
                    $date = $dateValue;
                    $id_location = $mapping_organization->location->kodesatusehat;
                    $name_location = $mapping_organization->location->deskripsi;
                    $kelas = $kelas;

                    $encounter = $this->encounter->create_masuk_kunjungan_rawat_inap($id_patient, $name_patient, $id_practitioner, $name_practitioner, $date, $id_location, $name_location, $kelas);
                    $mapping_kunjungan_inap = new MappingKunjunganInap();
                    $mapping_kunjungan_inap->noreg = $registrasi_pasien_terakhir;
                    $mapping_kunjungan_inap->encounter = $encounter->id;
                    $mapping_kunjungan_inap->tanggal = $now;
                    $mapping_kunjungan_inap->save();
                } catch (\Throwable $th) {
                    $log_encounter_inap = new LogEncounterInap();
                    $log_encounter_inap->noreg = $registrasi_pasien_terakhir;
                    $log_encounter_inap->ket_log = $th->getMessage();
                    $log_encounter_inap->save();
                }
            // ===================================End Encounter==============================================

            
            return response()->json([
                'noreg' => $registrasi_pasien_terakhir,
                'message' => 'Data Berhasil Disimpan',
                'nama schedule' => 'pendaftaran pendataan pasien Inap'
            ], 200);
        }
    // ===============================End Pendafataran Pasien===============================

    // ===============================Anamnesis Keluhan Utama================================
        public function keluhan_utama_index(Request $request){
            if (isset($request->tanggal)) {
                $yesterdayFormatted = Carbon::parse($request->tanggal)->format('ymd');

                $diagnosis = Diagnosis::where('noreg', 'like', $yesterdayFormatted.'%')->pluck('noreg');
                $log_diagnosis = LogDiagnosis::where('noreg', 'like', $yesterdayFormatted.'%')->pluck('noreg');
                $mapping_kunjungan_inap = MappingKunjunganInap::whereNotIn('noreg', $diagnosis)
                                            ->whereNotIn('noreg', $log_diagnosis)
                                            ->where('noreg', 'like', $yesterdayFormatted.'%')
                                            ->paginate(300)->appends(request()->query());
                // $mapping_kunjungan_inap = MappingKunjunganInap::paginate(25);
            }else {
                $mapping_kunjungan_inap = null;
            }
            // $cek_cekin = DB::connection('sqlsrv2')
            // ->table('_chekinpoli')
            // ->where('noreg', '2402210138')
            // ->first();
            // dd($cek_cekin);
            return view('resume-medis-rawat-jalan.anamnesis-keluhan-utama.index', compact('mapping_kunjungan_poli'));
        }

        public function keluhan_utama_store(){
            set_time_limit((int) 900000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000);
            $diagnosis = Diagnosis::orderBy('noreg', 'desc')->pluck('noreg')->first();
            $log_diagnosis = LogDiagnosis::orderBy('noreg', 'desc')->pluck('noreg')->first();
            if ($diagnosis > $log_diagnosis) {
                $noreg_terakhir = $diagnosis;
            }elseif ($diagnosis < $log_diagnosis) {
                $noreg_terakhir = $log_diagnosis;
            }elseif ($diagnosis == $log_diagnosis) {
                $noreg_terakhir = $diagnosis;
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
                    'nama schedule' => 'keluhan utama'
                ], 200);
            }

            $data_terbesar = MappingKunjunganInap::orderBy('noreg', 'desc')->pluck('noreg')->first();
            if ($noreg_terakhir > $data_terbesar) {
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "Noreg Belum Terdaftar",
                    'nama schedule' => 'keluhan utama'
                ], 200);
            }

            $mapping_kunjungan_inap = MappingKunjunganInap::where('noreg', $noreg_terakhir)->first();
            // dd($mapping_kunjungan_inap);


            // if ($request->noreg != null) {
            //     foreach ($request->noreg as $item){

                    try {
                        $bpjs_sep_manual = BpjsSepManual::where('noreg', $noreg_terakhir)->first();
                        $registrasi_pasien = RegistrasiPasien::where('NOREG', $noreg_terakhir)->first();
                        $mapping_kunjungan_inap = MappingKunjunganInap::where('noreg', $noreg_terakhir)->first();
                        $mapping_pasien = MappingPasien::where('norm', $registrasi_pasien->NOPASIEN)->first();
                        $nosep = CheckinPoli::where('noreg', $noreg_terakhir)->first();

                        $apiUrl = "http://10.10.6.14:10000/api/sep-new/{$nosep->nosep}";
                        $client = new Client();
                        $response = $client->get($apiUrl);
                        $apiData = $response->getBody()->getContents();
                        $dataArray = json_decode($apiData, true);
                    } catch (\Throwable $th) {
                        $dataArray = null;
                        $log_diagnosis = new LogDiagnosis();
                        $log_diagnosis->noreg = $noreg_terakhir;
                        $log_diagnosis->ket_log = 'Sep Tidak Ditemukan';
                        $log_diagnosis->save();
                        return response()->json([
                            'noreg' => $noreg_terakhir,
                            'message' => "Sep Tidak Ditemukan",
                            'nama schedule' => 'keluhan utama'
                        ], 200);
                    }

                    if ($dataArray != null) {
                        try {
                            $diagnosa = explode(' - ', $dataArray['response']['diagnosa']);
                            $kode_diagnosa = $diagnosa[0];
                            $deskripsi_diagnosa = $diagnosa[1];
                            $data = $this->condition->create_diagnosis($kode_diagnosa, $deskripsi_diagnosa, $mapping_pasien, $mapping_kunjungan_inap);
                            $diagnosis = new Diagnosis();
                            $diagnosis->encounter = (String)$mapping_kunjungan_inap->encounter;
                            $diagnosis->noreg = (String)$noreg_terakhir;
                            $diagnosis->kode_icd = (String)$kode_diagnosa;
                            $diagnosis->nama_icd = (String)$deskripsi_diagnosa;
                            $diagnosis->id_diagnosa = (String)$data->id;
                            $diagnosis->save();
                        } catch (\Throwable $th) {
                            $log_diagnosis = new LogDiagnosis();
                            $log_diagnosis->noreg = $noreg_terakhir;
                            $log_diagnosis->ket_log = 'duplicate';
                            $log_diagnosis->save();
                            return response()->json([
                                'noreg' => $noreg_terakhir,
                                'message' => "duplicate",
                                'nama schedule' => 'keluhan utama'
                            ], 200);
                        }
                    }
                // }
                // return redirect()->back()->with('success', 'Data Berhasil Disimpan');
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => 'Data Berhasil Disimpan',
                    'nama schedule' => 'keluhan utama'
                ], 200);
            // }else {
            //     return redirect()->back()->with('error', 'Anda Belum Memilih Data');
            // }
        }
    // ===============================End Anamnesis Keluhan Utama================================

    // ===============================Anamnesis Riwayat Alergi================================
        public function riwayat_alergi_index(Request $request){
            // if (isset($request->tanggal)) {
            //     $yesterdayFormatted = Carbon::parse($request->tanggal)->format('ymd');

            //     $diagnosis = Diagnosis::where('noreg', 'like', $yesterdayFormatted.'%')->pluck('noreg');
            //     $log_diagnosis = LogDiagnosis::where('noreg', 'like', $yesterdayFormatted.'%')->pluck('noreg');
            //     $mapping_kunjungan_inap = MappingKunjunganInap::whereNotIn('noreg', $diagnosis)
            //                                 ->whereNotIn('noreg', $log_diagnosis)
            //                                 ->where('noreg', 'like', $yesterdayFormatted.'%')
            //                                 ->paginate(100)->appends(request()->query());
            //     // $mapping_kunjungan_inap = MappingKunjunganInap::paginate(25);
            // }else {
            //     $mapping_kunjungan_inap = null;
            // }
            return view('resume-medis-rawat-jalan.anamnesis-riwayat-alergi.index');
        }
    // ===============================End Anamnesis Riwayat Alergi================================

    // ===============================Hasil Pemeriksaan Fisik================================
        public function hasil_pemeriksaan_fisik_index(Request $request){
            if (isset($request->tanggal)) {
                $yesterdayFormatted = Carbon::parse($request->tanggal)->format('ymd');
            }else {
                $yesterdayFormatted = Carbon::now()->setTimezone('Asia/Jakarta')->format('ymd');
            }
            $observation = Observation::where('noreg', 'like', $yesterdayFormatted.'%')->pluck('noreg');
            $log_observation = LogObservation::where('noreg', 'like', $yesterdayFormatted.'%')->pluck('noreg');
            $diagnosis = Diagnosis::whereNotIn('noreg', $observation)
                                        ->whereNotIn('noreg', $log_observation)
                                        ->where('noreg', 'like', $yesterdayFormatted.'%')
                                        ->paginate(300)->appends(request()->query());
            return view('resume-medis-rawat-jalan.hasil-pemeriksaan-fisik.index', compact('diagnosis'));
        }

        public function hasil_pemeriksaan_fisik_store(Request $request){
            set_time_limit((int) 900000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000);
            $observation = Observation::orderBy('noreg', 'desc')->pluck('noreg')->first();
            $log_observation = LogObservation::orderBy('noreg', 'desc')->pluck('noreg')->first();
            if ($observation > $log_observation) {
                $noreg_terakhir = $observation;
            }elseif ($observation < $log_observation) {
                $noreg_terakhir = $log_observation;
            }elseif ($observation == $log_observation) {
                $noreg_terakhir = $observation;
            }

            $noreg_terakhir = $noreg_terakhir+1;
            $noreg_tanggal_depan = (substr($noreg_terakhir, 0, -4)+1)."0000";
            $data_tanggal_terakhir = Diagnosis::where('noreg', "<", $noreg_tanggal_depan)->orderBy('noreg', 'desc')->pluck('noreg')->first();
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
                    'nama schedule' => 'hasil pemeriksaan fisik'
                ], 200);
            }

            $data_terbesar = Diagnosis::orderBy('noreg', 'desc')->pluck('noreg')->first();
            if ($noreg_terakhir > $data_terbesar) {
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "Noreg Belum Terdaftar",
                    'nama schedule' => 'hasil pemeriksaan fisik'
                ], 200);
            }

            $diagnosis = Diagnosis::where('noreg', $noreg_terakhir)->first();
            if ($diagnosis == null) {
                $log_observation = new LogObservation();
                $log_observation->noreg = $noreg_terakhir;
                $log_observation->ket_log = "Noreg Tidak mempunyai diagnosis";
                $log_observation->save();
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "Noreg Tidak mempunyai diagnosis",
                    'nama schedule' => 'hasil pemeriksaan fisik'
                ], 200);
            }
            // if ($request->noreg != null) {
            //     foreach ($request->noreg as $item){
                    $rj_skrining_tb = RjSkriningTb::where('noreg', $noreg_terakhir)->first();
                    // $igd_skrining_tb = IgdSkriningTb::where('noreg', "2401020380")->first();

                    if ($rj_skrining_tb != null) {
                        $nadi = $rj_skrining_tb->nadi;
                        $rr = $rj_skrining_tb->rr;

                        //tensi
                        if ($rj_skrining_tb->tensi != null) {
                            try {
                                $tensi_array = explode("/", $rj_skrining_tb->tensi);
                                $nilai_sistole = $tensi_array[0];
                                $nilai_diastole = $tensi_array[1];
                            } catch (\Throwable $th) {
                                $nilai_sistole = null;
                                $nilai_diastole = null;
                            }
                        }else {
                            $nilai_sistole = null;
                            $nilai_diastole = null;
                        }
                        $suhu_tubuh = $rj_skrining_tb->suhu;
                    } else {
                        $nadi = null;
                        $rr = null;
                        $nilai_sistole = null;
                        $nilai_diastole = null;
                        $suhu_tubuh = null;

                        $log_observation = new LogObservation();
                        $log_observation->noreg = $noreg_terakhir;
                        $log_observation->ket_log = "Skrining Tidak Ditemukan";
                        $log_observation->save();

                    }

                    if ($nadi == null && $rr == null && $suhu_tubuh == null && $nilai_sistole == null && $nilai_diastole == null) {
                        $log_observation = new LogObservation();
                        $log_observation->noreg = $noreg_terakhir;
                        $log_observation->ket_log = "Skrining Tidak Ditemukan";
                        $log_observation->save();

                        return response()->json([
                            'noreg' => $noreg_terakhir,
                            'message' => "Skrining Tidak Ditemukan",
                            'nama schedule' => 'hasil pemeriksaan fisik'
                        ], 200);
                    }

                    // dd('nadi '.$nadi.' rr '.$rr.' nilai_sistole '.$nilai_sistole.' nilai_diastole '.$nilai_diastole.' suhu_tubuh '.$suhu_tubuh);
                    $registrasi_pasien = RegistrasiPasien::where('NOREG', $noreg_terakhir)->first();
                    $mapping_pasien = MappingPasien::where('norm', $registrasi_pasien->NOPASIEN)->first();
                    $diagnosis = Diagnosis::where('noreg', $noreg_terakhir)->first();

                    $mapping_dokter_spesialis = MappingDokterSpesialis::where('kodepelayanan', $registrasi_pasien->Registrasi_Dokter->KODEDOKTER)->first();
                    if ($mapping_dokter_spesialis == null) {
                        // return redirect()->back()->with('error', 'Dokter untuk Pasien '.$registrasi_pasien->Pasien->NAMAPASIEN.' Tidak Ditemukan Di Mapping Dokter Spesialis');
                        return response()->json([
                            'noreg' => $noreg_terakhir,
                            'message' => "Dokter untuk Pasien '.$registrasi_pasien->Pasien->NAMAPASIEN.' Tidak Ditemukan Di Mapping Dokter Spesialis",
                            'nama schedule' => 'hasil pemeriksaan fisik'
                        ], 200);
                    }

                    $id_practitioner = $mapping_dokter_spesialis->satusehat;
                    $dateArray = explode(' ', $registrasi_pasien->TGLREG);
                    $dateValue = $dateArray[0];
                    $date = $dateValue;

                    $observation = new Observation();
                    $observation->encounter = $diagnosis->encounter;
                    $observation->noreg = $noreg_terakhir;
                    if ($nadi != null) {
                        try {
                            $data_nadi = $this->observasion->create($mapping_pasien, $diagnosis, $id_practitioner, $date, $nadi);
                            // $nadi_id = $data_nadi->id;
                            $observation->id_observation = $data_nadi->id;
                            $observation->code_loinc = $data_nadi->code->coding[0]->code;
                            $observation->desc_loinc = $data_nadi->code->coding[0]->display;
                            $observation->unit = $data_nadi->valueQuantity->unit;
                            $observation->value = $data_nadi->valueQuantity->value;
                        } catch (\Throwable $th) {
                            $log_observation = new LogObservation();
                            $log_observation->noreg = $noreg_terakhir;
                            $log_observation->ket_log = $th;
                            $log_observation->proses = 'nadi';
                            $log_observation->save();
                        }
                    }

                    if ($rr != null) {
                        try {
                            $data_rr = $this->observasion->pernapasan($mapping_pasien, $diagnosis, $id_practitioner, $date, $rr);
                            $observation->rr_id_observation = $data_rr->id;
                            $observation->rr_code_loinc = $data_rr->code->coding[0]->code;
                            $observation->rr_desc_loinc = $data_rr->code->coding[0]->display;
                            $observation->rr_unit = $data_rr->valueQuantity->unit;
                            $observation->rr_value = $data_rr->valueQuantity->value;
                        } catch (\Throwable $th) {
                            $log_observation = new LogObservation();
                            $log_observation->noreg = $noreg_terakhir;
                            $log_observation->ket_log = $th;
                            $log_observation->proses = 'rr';
                            $log_observation->save();
                        }
                    }

                    if ($nilai_sistole != null) {
                        try {
                            $data_tekanan_darah_sistole = $this->observasion->tekanan_darah_sistole($mapping_pasien, $diagnosis, $id_practitioner, $date, $nilai_sistole);
                            $observation->tekanan_darah_sistole_id_observation = $data_tekanan_darah_sistole->id;
                            $observation->tekanan_darah_sistole_code_loinc = $data_tekanan_darah_sistole->code->coding[0]->code;
                            $observation->tekanan_darah_sistole_desc_loinc = $data_tekanan_darah_sistole->code->coding[0]->display;
                            $observation->tekanan_darah_sistole_unit = $data_tekanan_darah_sistole->valueQuantity->unit;
                            $observation->tekanan_darah_sistole_value = $data_tekanan_darah_sistole->valueQuantity->value;
                        } catch (\Throwable $th) {
                            $log_observation = new LogObservation();
                            $log_observation->noreg = $noreg_terakhir;
                            $log_observation->ket_log = $th;
                            $log_observation->proses = 'tekanan_darah_sistole';
                            $log_observation->save();
                        }
                    }

                    if ($nilai_diastole != null) {
                        try {
                            $data_tekanan_darah_diastole = $this->observasion->tekanan_darah_diastole($mapping_pasien, $diagnosis, $id_practitioner, $date, $nilai_diastole);
                            $observation->tekanan_darah_diastole_id_observation = $data_tekanan_darah_diastole->id;
                            $observation->tekanan_darah_diastole_code_loinc = $data_tekanan_darah_diastole->code->coding[0]->code;
                            $observation->tekanan_darah_diastole_desc_loinc = $data_tekanan_darah_diastole->code->coding[0]->display;
                            $observation->tekanan_darah_diastole_unit = $data_tekanan_darah_diastole->valueQuantity->unit;
                            $observation->tekanan_darah_diastole_value = $data_tekanan_darah_diastole->valueQuantity->value;
                        } catch (\Throwable $th) {
                            $log_observation = new LogObservation();
                            $log_observation->noreg = $noreg_terakhir;
                            $log_observation->ket_log = $th;
                            $log_observation->proses = 'tekanan_darah_diastole';
                            $log_observation->save();
                        }
                    }

                    if ($suhu_tubuh != null) {
                        try {
                            // Memeriksa apakah koma ada dalam string
                            if (strpos($suhu_tubuh, ',') !== false) {
                                // Jika koma ada, kita memisahkan string menjadi bagian sebelum dan setelah koma
                                $parts = explode(",", $suhu_tubuh);
                                // Mengambil bagian pertama (nilai sebelum koma)
                                $suhu_tubuh = $parts[0];
                            } else {
                                // Jika tidak ada koma, nilai baru adalah nilai asli
                                $suhu_tubuh = $suhu_tubuh;
                            }
                            $data_suhu = $this->observasion->suhu_tubuh($mapping_pasien, $diagnosis, $id_practitioner, $date, $suhu_tubuh);
                            $observation->suhu_id_observation = $data_suhu->id;
                            $observation->suhu_code_loinc = $data_suhu->code->coding[0]->code;
                            $observation->suhu_desc_loinc = $data_suhu->code->coding[0]->display;
                            $observation->suhu_unit = $data_suhu->valueQuantity->unit;
                            $observation->suhu_value = $data_suhu->valueQuantity->value;
                        } catch (\Throwable $th) {
                            $log_observation = new LogObservation();
                            $log_observation->noreg = $noreg_terakhir;
                            $log_observation->ket_log = $th;
                            $log_observation->proses = 'suhu';
                            $log_observation->save();
                        }
                    }

                    if ($observation->id_observation != null && $observation->rr_id_observation != null && $observation->tekanan_darah_sistole_id_observation != null && $observation->tekanan_darah_diastole_id_observation != null && $observation->suhu_id_observation != null) {
                        $observation->tanggal = $date;
                        $observation->save();
                    }

                        // $data_pernapasan = $this->observasion->pernapasan($mapping_pasien, $diagnosis, $id_practitioner, $date, $rr);
                        // $pernapasan_id = $data_pernapasan->id;

                        // $data_tekanan_darah_sistole = $this->observasion->tekanan_darah_sistole($mapping_pasien, $diagnosis, $id_practitioner, $date, $nilai_sistole);
                        // $tekanan_darah_sistole_id = $data_tekanan_darah_sistole->id;

                        // $data_tekanan_darah_diastole = $this->observasion->tekanan_darah_diastole($mapping_pasien, $diagnosis, $id_practitioner, $date, $nilai_diastole);
                        // $tekanan_darah_diastole_id = $data_tekanan_darah_diastole->id;

                        // $data_suhu = $this->observasion->suhu_tubuh($mapping_pasien, $diagnosis, $id_practitioner, $date, $suhu_tubuh);
                        // $suhu_id = $data_suhu->id;

                        // $observation = new Observation();
                        // $observation->encounter = $diagnosis->encounter;
                        // $observation->noreg = $item;
                        // $observation->id_observation = $data->id;
                        // $observation->code_loinc = $data->code->coding[0]->code;
                        // $observation->desc_loinc = $data->code->coding[0]->display;
                        // $observation->unit = $data->valueQuantity->unit;
                        // $observation->value = $data->valueQuantity->value;
                        // $observation->tanggal = $date;
                        // $observation->save();
                // }
            //     return redirect()->back()->with('success', 'Data Berhasil Disimpan');
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "Data Berhasil Disimpan",
                    'nama schedule' => 'hasil pemeriksaan fisik'
                ], 200);
            // }else {
            //     return redirect()->back()->with('error', 'Anda Belum Memilih Data');
            // }
        }
    // ===============================End Hasil Pemeriksaan Fisik============================

    // +++++++++++++++++++++++++++++++++++Rujukan Laboratoriom++++++++++++++++++++++++++++
        // ===================================Request Service=========================================
            public function permintaan_pemeriksaan_penunjang_laboratorium_index(Request $request){
                // dd("halo");
                if (isset($request->tanggal)) {
                    $yesterdayFormatted = Carbon::parse($request->tanggal)->format('ymd');
                }else {
                    $yesterdayFormatted = Carbon::now()->setTimezone('Asia/Jakarta')->format('ymd');
                }
                $service_request = ServiceRequest::where('noreg', 'like', $yesterdayFormatted.'%')->pluck('noreg');
                $log_service_request = LogServiceRequest::where('noreg', 'like', $yesterdayFormatted.'%')->pluck('noreg');
                $observation = Observation::whereNotIn('noreg', $service_request)
                                            ->whereNotIn('noreg', $log_service_request)
                                            ->where('noreg', 'like', $yesterdayFormatted.'%')
                                            ->paginate(10)->appends(request()->query());
                return view('resume-medis-rawat-jalan.laboratorium.request-service.index', compact('observation'));
            }

            public function permintaan_pemeriksaan_penunjang_laboratorium_store(Request $request){
                set_time_limit((int) 0);

                $service_request = ServiceRequest::orderBy('noreg', 'desc')->pluck('noreg')->first();
                $log_service_request = LogServiceRequest::orderBy('noreg', 'desc')->pluck('noreg')->first();
                if ($service_request > $log_service_request) {
                    $noreg_terakhir = $service_request;
                }elseif ($service_request < $log_service_request) {
                    $noreg_terakhir = $log_service_request;
                }elseif ($service_request == $log_service_request) {
                    $noreg_terakhir = $service_request;
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
                        'nama schedule' => 'Service Request'
                    ], 200);
                }

                $data_terbesar = MappingKunjunganInap::orderBy('noreg', 'desc')->pluck('noreg')->first();
                if ($noreg_terakhir > $data_terbesar) {
                    return response()->json([
                        'noreg' => $noreg_terakhir,
                        'message' => "Noreg Belum Terdaftar",
                        'nama schedule' => 'Service Request'
                    ], 200);
                }

                // $noreg_terakhir = '2401010001';

                $mapping_kunjungan_inap = MappingKunjunganInap::where('noreg', $noreg_terakhir)->first();
                if ($mapping_kunjungan_inap == null) {
                    $log_service_request = new LogServiceRequest();
                    $log_service_request->noreg = $noreg_terakhir;
                    $log_service_request->ket_log = 'Mapping kunjungan poli tidak ditemukan';
                    $log_service_request->jenis_service = 'Pengiriman Data Pemeriksaan Penunjang Laboratorium';
                    $log_service_request->save();
                    return response()->json([
                        'noreg' => $noreg_terakhir,
                        'message' => "Mapping kunjungan poli tidak ditemukan",
                        'nama schedule' => 'Service Request'
                    ], 200);
                }
                $registrasi_pasien = RegistrasiPasien::where('NOREG', $noreg_terakhir)->first();
                if ($registrasi_pasien == null) {
                    $log_service_request = new LogServiceRequest();
                    $log_service_request->noreg = $noreg_terakhir;
                    $log_service_request->ket_log = 'Noreg Belum Terdaftar';
                    $log_service_request->jenis_service = 'Pengiriman Data Pemeriksaan Penunjang Laboratorium';
                    $log_service_request->save();
                    return response()->json([
                        'noreg' => $noreg_terakhir,
                        'message' => "Noreg Belum Terdaftar",
                        'nama schedule' => 'Service Request'
                    ], 200);
                }
                $mapping_pasien = MappingPasien::where('norm', $registrasi_pasien->NOPASIEN)->first();
                if ($mapping_pasien == null) {
                    $log_service_request = new LogServiceRequest();
                    $log_service_request->noreg = $noreg_terakhir;
                    $log_service_request->ket_log = "Pasien Tidak Ditemukan Di Mapping Pasien";
                    $log_service_request->jenis_service = 'Pengiriman Data Pemeriksaan Penunjang Laboratorium';
                    $log_service_request->save();
                    return response()->json([
                        'noreg' => $noreg_terakhir,
                        'message' => "Pasien Tidak Ditemukan Di Mapping Pasien",
                        'nama schedule' => 'Service Request'
                    ], 200);
                }

                $mapping_dokter_spesialis = MappingDokterSpesialis::where('kodepelayanan', $registrasi_pasien->Registrasi_Dokter->KODEDOKTER)->first();
                if ($mapping_dokter_spesialis == null) {
                    $log_service_request = new LogServiceRequest();
                    $log_service_request->noreg = $noreg_terakhir;
                    $log_service_request->ket_log = "Dokter untuk Pasien ".$registrasi_pasien->Pasien->NAMAPASIEN." Tidak Ditemukan Di Mapping Dokter Spesialis";
                    $log_service_request->jenis_service = 'Pengiriman Data Pemeriksaan Penunjang Laboratorium';
                    $log_service_request->save();
                    return response()->json([
                        'noreg' => $noreg_terakhir,
                        'message' => "Dokter untuk Pasien ".$registrasi_pasien->Pasien->NAMAPASIEN." Tidak Ditemukan Di Mapping Dokter Spesialis",
                        'nama schedule' => 'Service Request'
                    ], 200);
                }

                $trxpmr = Trxpmr::where('NOREG', (String)$noreg_terakhir)->get();
                if ($trxpmr == null || $trxpmr->isEmpty()) {
                    $log_service_request = new LogServiceRequest();
                    $log_service_request->noreg = $noreg_terakhir;
                    $log_service_request->ket_log = "Pasien Tidak mempunyai order lab";
                    $log_service_request->jenis_service = 'Pengiriman Data Pemeriksaan Penunjang Laboratorium';
                    $log_service_request->save();
                    return response()->json([
                        'noreg' => $noreg_terakhir,
                        'message' => "Pasien Tidak mempunyai order lab",
                        'nama schedule' => 'Service Request'
                    ], 200);
                }

                foreach ($trxpmr as $item) {
                    $mapmr = MAPMR::where('KODEPMR', $item->KODEPMR)->first();
                    if ($mapmr == null) {
                        $log_service_request = new LogServiceRequest();
                        $log_service_request->noreg = $noreg_terakhir;
                        $log_service_request->ket_log = "order lab tidak mempunyai mapmr";
                        $log_service_request->jenis_service = 'Pengiriman Data Pemeriksaan Penunjang Laboratorium';
                        $log_service_request->save();
                        return response()->json([
                            'noreg' => $noreg_terakhir,
                            'message' => "order lab tidak mempunyai mapmr",
                            'nama schedule' => 'Service Request'
                        ], 200);
                    }

                    $master_mapmr_loinc = MasterMapmrLoinc::where('kode_mapmr', $item->KODEPMR)->get();
                    if ($master_mapmr_loinc == null || $master_mapmr_loinc->isEmpty()) {
                        $log_service_request = new LogServiceRequest();
                        $log_service_request->noreg = $noreg_terakhir;
                        $log_service_request->ket_log = "bukan pemeriksaan laborat/tidak mempunyai master mapmr loinc";
                        $log_service_request->jenis_service = 'Pengiriman Data Pemeriksaan Penunjang Laboratorium';
                        $log_service_request->save();
                        // return response()->json([
                        //     'noreg' => $noreg_terakhir,
                        //     'message' => "tidak mempunyai master mapmr loinc",
                        //     'nama schedule' => 'Service Request'
                        // ], 200);
                        continue;
                    }

                    foreach ($master_mapmr_loinc as $item2) {
                        $master_loinc = MasterLoinc::where('id', $item2->id_master_loinc)->first();

                        try {
                            $code_loinc = $master_loinc->code;
                            $desc_loinc = $master_loinc->display;
                            $code_text = $master_loinc->nama_pemeriksaan;
                            $id_patient = $mapping_pasien->kodesatusehat;
                            $encounter = $mapping_kunjungan_inap->encounter;
                            $encounter_display = $master_loinc->permintaan_hasil.' '.$master_loinc->kategori_kelompok_pemeriksaan;
                            $occurrenceDateTime = date('Y-m-d', strtotime($item->TGLBILL)).'T00:00:00.000+07:00';
                            $requester_reference = $mapping_dokter_spesialis->satusehat;
                            $requester_display = $mapping_dokter_spesialis->nama;

                            $data = $this->sevice_request->create_laboratorium(
                                $code_loinc,
                                $desc_loinc,
                                $code_text,
                                $id_patient,
                                $encounter,
                                $encounter_display,
                                $occurrenceDateTime,
                                $requester_reference,
                                $requester_display);
                            // dd($data);
                            $service_request = new ServiceRequest();
                            $service_request->encounter = (String)$mapping_kunjungan_inap->encounter;
                            $service_request->noreg = (String)$noreg_terakhir;
                            $service_request->id_service_request = (String)$data->id;
                            $service_request->code_snomed = "108252007";
                            $service_request->desc_snomed = "Laboratory procedure";
                            $service_request->code_loinc = $master_loinc->code;
                            $service_request->desc_loinc = $master_loinc->display;
                            $service_request->jenis_service = "Pengiriman Data Pemeriksaan Penunjang Laboratorium";
                            $service_request->save();
                        } catch (\Throwable $th) {
                            $log_service_request = new LogServiceRequest();
                            $log_service_request->noreg = $noreg_terakhir;
                            $log_service_request->ket_log = 'duplicate';
                            $log_service_request->jenis_service = 'Pengiriman Data Pemeriksaan Penunjang Laboratorium';
                            $log_service_request->save();
                        }
                        sleep(10);
                    }
                    sleep(10);
                }
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => 'Semua Data sukses dikirim',
                    'nama schedule' => 'Service Request'
                ], 200);
            }
        // ===================================End Request Service=====================================
    // +++++++++++++++++++++++++++++++++++End Rujukan Laboratoriom++++++++++++++++++++++++

    // ====================================procedure================================
        public function tindakan(Request $request){
            // dd("halo");
            // dd($request->id_patient.'_'.$request->id_patient_display.'_'.$request->encounter.'_'.$request->practitioner.'_'.$request->practitioner_display);
            $procedure = $this->procedure->create_procedure($request->id_patient, $request->id_patient_display, $request->encounter, $request->practitioner, $request->practitioner_display);
            return response()->json($procedure);
        }

        public function procedure_edukasi_nutrisi(Request $request){
            set_time_limit((int) 0);
            $procedure_edukasi_nutrisi = ProcedureEdukasiNutrisi::orderBy('noreg', 'desc')->pluck('noreg')->first();
            $log_procedure_edukasi_nutrisi = LogProcedureEdukasiNutrisi::orderBy('noreg', 'desc')->pluck('noreg')->first();
            if ($procedure_edukasi_nutrisi > $log_procedure_edukasi_nutrisi) {
                $noreg_terakhir = $procedure_edukasi_nutrisi;
            }elseif ($procedure_edukasi_nutrisi < $log_procedure_edukasi_nutrisi) {
                $noreg_terakhir = $log_procedure_edukasi_nutrisi;
            }elseif ($procedure_edukasi_nutrisi == $log_procedure_edukasi_nutrisi) {
                $noreg_terakhir = $procedure_edukasi_nutrisi;
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
                    'nama schedule' => 'Procedure Edukasi Nutrisi'
                ], 200);
            }

            $data_terbesar = MappingKunjunganInap::orderBy('noreg', 'desc')->pluck('noreg')->first();
            if ($noreg_terakhir > $data_terbesar) {
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "Noreg Belum Terdaftar",
                    'nama schedule' => 'Procedure Edukasi Nutrisi'
                ], 200);
            }
            // $noreg_terakhir = '2401010001';
            $mapping_kunjungan_inap = MappingKunjunganInap::where('noreg', $noreg_terakhir)->first();
            if ($mapping_kunjungan_inap == null) {
                $log_procedure_edukasi_nutrisi = new LogProcedureEdukasiNutrisi();
                $log_procedure_edukasi_nutrisi->noreg = $noreg_terakhir;
                $log_procedure_edukasi_nutrisi->ket_log = 'Mapping kunjungan poli tidak ditemukan';
                $log_procedure_edukasi_nutrisi->save();
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "Mapping kunjungan poli tidak ditemukan",
                    'nama schedule' => 'Procedure Edukasi Nutrisi'
                ], 200);
            }
            $registrasi_pasien = RegistrasiPasien::where('NOREG', $noreg_terakhir)->first();
            if ($registrasi_pasien == null) {
                $log_procedure_edukasi_nutrisi = new LogProcedureEdukasiNutrisi();
                $log_procedure_edukasi_nutrisi->noreg = $noreg_terakhir;
                $log_procedure_edukasi_nutrisi->ket_log = 'Noreg Belum Terdaftar';
                $log_procedure_edukasi_nutrisi->save();
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "Noreg Belum Terdaftar",
                    'nama schedule' => 'Procedure Edukasi Nutrisi'
                ], 200);
            }
            $mapping_pasien = MappingPasien::where('norm', $registrasi_pasien->NOPASIEN)->first();
            if ($mapping_pasien == null) {
                $log_procedure_edukasi_nutrisi = new LogProcedureEdukasiNutrisi();
                $log_procedure_edukasi_nutrisi->noreg = $noreg_terakhir;
                $log_procedure_edukasi_nutrisi->ket_log = "Pasien Tidak Ditemukan Di Mapping Pasien";
                $log_procedure_edukasi_nutrisi->save();
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "Pasien Tidak Ditemukan Di Mapping Pasien",
                    'nama schedule' => 'Procedure Edukasi Nutrisi'
                ], 200);
            }

            $mapping_dokter_spesialis = MappingDokterSpesialis::where('kodepelayanan', $registrasi_pasien->Registrasi_Dokter->KODEDOKTER)->first();
            if ($mapping_dokter_spesialis == null) {
                $log_procedure_edukasi_nutrisi = new LogProcedureEdukasiNutrisi();
                $log_procedure_edukasi_nutrisi->noreg = $noreg_terakhir;
                $log_procedure_edukasi_nutrisi->ket_log = "Dokter untuk Pasien ".$registrasi_pasien->Pasien->NAMAPASIEN." Tidak Ditemukan Di Mapping Dokter Spesialis";
                $log_procedure_edukasi_nutrisi->save();
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "Dokter untuk Pasien ".$registrasi_pasien->Pasien->NAMAPASIEN." Tidak Ditemukan Di Mapping Dokter Spesialis",
                    'nama schedule' => 'Procedure Edukasi Nutrisi'
                ], 200);
            }

            $id_patient             = $mapping_pasien->kodesatusehat;
            $id_patient_display     = $mapping_pasien->nama;
            $encounter              = $mapping_kunjungan_inap->encounter;
            $practitioner           = $mapping_dokter_spesialis->satusehat;
            $practitioner_display   = $mapping_dokter_spesialis->nama;
            $tanggal_start          = date('Y-m-d', strtotime($registrasi_pasien->TGLREG)).'T10:00:00.000+07:00';
            $tanggal_end            = date('Y-m-d', strtotime($registrasi_pasien->TGLREG)).'T10:10:00.000+07:00';
            try {
                $procedure = $this->procedure->create_procedure_edukasi_nutrisi($id_patient, $id_patient_display, $encounter, $practitioner, $practitioner_display, $tanggal_start, $tanggal_end);
                // dd($procedure);
                $procedure_edukasi_nutrisi_model = new ProcedureEdukasiNutrisi();
                $procedure_edukasi_nutrisi_model->encounter = $encounter;
                $procedure_edukasi_nutrisi_model->noreg = $noreg_terakhir;
                $procedure_edukasi_nutrisi_model->id_procedure = $procedure->id;
                $procedure_edukasi_nutrisi_model->save();
            } catch (\Throwable $th) {
                $log_procedure_edukasi_nutrisi = new LogProcedureEdukasiNutrisi();
                $log_procedure_edukasi_nutrisi->noreg = $noreg_terakhir;
                $log_procedure_edukasi_nutrisi->ket_log = "duplicate";
                $log_procedure_edukasi_nutrisi->save();
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "duplicate",
                    'nama schedule' => 'Procedure Edukasi Nutrisi'
                ], 200);
            }

            return response()->json([
                'noreg' => $noreg_terakhir,
                'message' => 'Semua Data sukses dikirim',
                'nama schedule' => 'Procedure Edukasi Nutrisi'
            ], 200);

        }
    // ====================================procedure================================

    // ===============================Medication================================
        public function medication_get(Request $request){
            dd($request->id_medication);
            $medication = $this->medication->get_medication($request->id_medication);
            // dd($medication);
            return response()->json($medication);
        }

        public function medication(Request $request){
            // dd($request->kode_kfa.'_'.$request->deskripsi_kfa.'_'.$request->kode_obat.'_'.$request->display_obat);
            $medication = $this->medication->create_medication($request->kode_kfa, $request->deskripsi_kfa, $request->kode_obat, $request->display_obat);
            // dd($medication);
            return response()->json($medication);
        }

        public function medication_request(Request $request){
            set_time_limit((int) 0);
            $medication_request = MedicationRequestModel::orderBy('noreg', 'desc')->pluck('noreg')->first();
            $log_medication_request = LogMedicationRequest::orderBy('noreg', 'desc')->pluck('noreg')->first();
            if ($medication_request > $log_medication_request) {
                $noreg_terakhir = $medication_request;
            }elseif ($medication_request < $log_medication_request) {
                $noreg_terakhir = $log_medication_request;
            }elseif ($medication_request == $log_medication_request) {
                $noreg_terakhir = $medication_request;
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
                    'nama schedule' => 'Medication Request'
                ], 200);
            }

            $data_terbesar = MappingKunjunganInap::orderBy('noreg', 'desc')->pluck('noreg')->first();
            if ($noreg_terakhir > $data_terbesar) {
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "Noreg Belum Terdaftar",
                    'nama schedule' => 'Medication Request'
                ], 200);
            }

            // $noreg_terakhir = '2110210043';
            // $noreg_terakhir = '2401020001';

            $mapping_kunjungan_inap = MappingKunjunganInap::where('noreg', $noreg_terakhir)->first();
            if ($mapping_kunjungan_inap == null) {
                $log_medication_request = new LogMedicationRequest();
                $log_medication_request->noreg = $noreg_terakhir;
                $log_medication_request->ket_log = 'Mapping kunjungan poli tidak ditemukan';
                $log_medication_request->save();
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "Mapping kunjungan poli tidak ditemukan",
                    'nama schedule' => 'Medication Request'
                ], 200);
            }
            $registrasi_pasien = RegistrasiPasien::where('NOREG', $noreg_terakhir)->first();
            if ($registrasi_pasien == null) {
                $log_medication_request = new LogMedicationRequest();
                $log_medication_request->noreg = $noreg_terakhir;
                $log_medication_request->ket_log = 'Noreg Belum Terdaftar';
                $log_medication_request->save();
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "Noreg Belum Terdaftar",
                    'nama schedule' => 'Medication Request'
                ], 200);
            }
            $mapping_pasien = MappingPasien::where('norm', $registrasi_pasien->NOPASIEN)->first();
            if ($mapping_pasien == null) {
                $log_medication_request = new LogMedicationRequest();
                $log_medication_request->noreg = $noreg_terakhir;
                $log_medication_request->ket_log = "Pasien Tidak Ditemukan Di Mapping Pasien";
                $log_medication_request->save();
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "Pasien Tidak Ditemukan Di Mapping Pasien",
                    'nama schedule' => 'Medication Request'
                ], 200);
            }

            $mapping_dokter_spesialis = MappingDokterSpesialis::where('kodepelayanan', $registrasi_pasien->Registrasi_Dokter->KODEDOKTER)->first();
            if ($mapping_dokter_spesialis == null) {
                $log_medication_request = new LogMedicationRequest();
                $log_medication_request->noreg = $noreg_terakhir;
                $log_medication_request->ket_log = "Dokter untuk Pasien ".$registrasi_pasien->Pasien->NAMAPASIEN." Tidak Ditemukan Di Mapping Dokter Spesialis";
                $log_medication_request->save();
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "Dokter untuk Pasien ".$registrasi_pasien->Pasien->NAMAPASIEN." Tidak Ditemukan Di Mapping Dokter Spesialis",
                    'nama schedule' => 'Medication Request'
                ], 200);
            }

            $trpmn = Trpmn::where('noreg', (String)$noreg_terakhir)->select('NORESEP', 'TGLRESEP')->first();
            // dd(date('Y-m-d', strtotime($trpmn->TGLRESEP)).'T00:00:00.000+07:00');
            // dd(date('Y-m-d', strtotime($trpmn->TGLRESEP . ' +7 days')).'T00:00:00.000+07:00');
            if ($trpmn == null) {
                $log_medication_request = new LogMedicationRequest();
                $log_medication_request->noreg = $noreg_terakhir;
                $log_medication_request->ket_log = 'Pasien Tidak Memiliki Resep';
                $log_medication_request->save();
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "Pasien Tidak Memiliki Resep",
                    'nama schedule' => 'Medication Request'
                ], 200);
            }

            $trpdn = Trpdn::where('NORESEP', $trpmn->NORESEP)->get();
            if ($trpdn == null || $trpdn->isEmpty()) {
                $log_medication_request = new LogMedicationRequest();
                $log_medication_request->noreg = $noreg_terakhir;
                $log_medication_request->ket_log = 'Pasien Tidak Memiliki Resep';
                $log_medication_request->save();
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "Pasien Tidak Memiliki Resep",
                    'nama schedule' => 'Medication Request'
                ], 200);
            }

            $nosep = CheckinPoli::where('noreg', $noreg_terakhir)->first();
            $mappingsep = MappingSep::where('NOREG', $noreg_terakhir)->first();
            if ($nosep == null && $mappingsep == null){
                $log_medication_request = new LogMedicationRequest();
                $log_medication_request->noreg = $noreg_terakhir;
                $log_medication_request->ket_log = 'Pasien Tidak Memiliki SEP';
                $log_medication_request->save();
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "Pasien Tidak Memiliki SEP",
                    'nama schedule' => 'Medication Request'
                ], 200);
            }
            if ($nosep != null) {
                $nomer_sep = $nosep->nosep;
            }elseif ($mappingsep != null) {
                $nomer_sep = $mappingsep->SEP;
            }
            try {
                $apiUrl = "http://10.10.6.14:10000/api/sep-new/{$nomer_sep}";
                $client = new Client();
                $response = $client->get($apiUrl);
                $apiData = $response->getBody()->getContents();
                $dataArray = json_decode($apiData, true);

                if ($dataArray != null) {
                    $diagnosa = explode(' - ', $dataArray['response']['diagnosa']);
                    $kode_diagnosa = $diagnosa[0];
                    $deskripsi_diagnosa = $diagnosa[1];
                }else {
                    $log_medication_request = new LogMedicationRequest();
                    $log_medication_request->noreg = $noreg_terakhir;
                    $log_medication_request->ket_log = 'Pasien Tidak Memiliki SEP';
                    $log_medication_request->save();
                    return response()->json([
                        'noreg' => $noreg_terakhir,
                        'message' => "Sep Tidak Ditemukan",
                        'nama schedule' => 'Medication Request'
                    ], 200);
                }
            } catch (\Throwable $th) {
                $log_medication_request = new LogMedicationRequest();
                $log_medication_request->noreg = $noreg_terakhir;
                $log_medication_request->ket_log = 'Pasien Tidak Memiliki SEP';
                $log_medication_request->save();
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "Sep Tidak Ditemukan",
                    'nama schedule' => 'Medication Request'
                ], 200);
            }

            foreach ($trpdn as $item) {
                $mabar = Mabar::where('KODEBARANG', $item->KODEBARANG)->first();
                $master_kfa_obat = MasterKfaObat::where('kode_barang_mabar', $item->KODEBARANG)->first();
                if ($master_kfa_obat == null) {
                    $log_medication_request = new LogMedicationRequest();
                    $log_medication_request->noreg = $noreg_terakhir;
                    $log_medication_request->ket_log = 'Obat dengan Kode ini '.$item->KODEBARANG.' Tidak Ditemukan Di Master KFA Obat';
                    $log_medication_request->save();
                    continue;
                }
                $id_obat_satusehat = $master_kfa_obat->kode_satu_sehat;
                $display_obat_satusehat = $master_kfa_obat->keterangan_kfa;
                $id_patient = $mapping_pasien->kodesatusehat;
                $id_patient_display = $mapping_pasien->namasatusehat;
                $encounter = $mapping_kunjungan_inap->encounter;
                $tanggal_peresepan = date('Y-m-d', strtotime($trpmn->TGLRESEP)).'T00:00:00.000+07:00';
                $id_dokter_satu_sehat = $mapping_dokter_spesialis->satusehat;
                $display_dokter_satu_sehat = $mapping_dokter_spesialis->nama;
                $dosis_obat = $item->KETERANGANATRPKAI.' '.$item->KETERANGAN;
                $start_waktu_pemberian_obat = date('Y-m-d', strtotime($trpmn->TGLRESEP)).'T00:00:00.000+07:00';
                $end_waktu_pemberian_obat = date('Y-m-d', strtotime($trpmn->TGLRESEP . ' +7 days')).'T00:00:00.000+07:00';
                $jumlah_obat = intval($item->QTYBAR);
                $durasi_penggunaan = '7';

                try {
                    $medication_request = $this->medication_request->create_medication_request($id_obat_satusehat, $display_obat_satusehat, $id_patient, $id_patient_display, $encounter, $tanggal_peresepan, $id_dokter_satu_sehat, $display_dokter_satu_sehat, $dosis_obat, $start_waktu_pemberian_obat, $end_waktu_pemberian_obat, $jumlah_obat, $durasi_penggunaan, $kode_diagnosa, $deskripsi_diagnosa);
                    $medication_request_model = new MedicationRequestModel();
                    $medication_request_model->encounter = $encounter;
                    $medication_request_model->noreg = $noreg_terakhir;
                    $medication_request_model->id_medication_request = $medication_request->id;
                    $medication_request_model->save();

                    try {
                        $start_waktu_pemberian_obat = date('Y-m-d', strtotime($trpmn->TGLRESEP));
                        $medication_dispense = $this->medication_dispense->create_medication_dispense($id_obat_satusehat, $display_obat_satusehat, $id_patient, $id_patient_display, $encounter, $tanggal_peresepan, $id_dokter_satu_sehat, $display_dokter_satu_sehat, $dosis_obat, $start_waktu_pemberian_obat, $end_waktu_pemberian_obat, $jumlah_obat, $durasi_penggunaan, $medication_request->id);
                        $medication_dispense_model = new MedicationDispenseModel();
                        $medication_dispense_model->encounter = $encounter;
                        $medication_dispense_model->noreg = $noreg_terakhir;
                        $medication_dispense_model->id_medication_dispense = $medication_dispense->id;
                        $medication_dispense_model->save();
                    } catch (\Throwable $th) {
                        $log_medication_dispense = new LogMedicationDispense();
                        $log_medication_dispense->noreg = $noreg_terakhir;
                        $log_medication_dispense->ket_log = 'duplicate';
                        $log_medication_dispense->save();
                    }
                    // return response()->json([
                    //     'noreg' => $noreg_terakhir,
                    //     'message' => 'Data sukses dikirim',
                    //     'nama schedule' => 'Medication Request'
                    // ], 200);
                } catch (\Throwable $th) {
                    $log_medication_request = new LogMedicationRequest();
                    $log_medication_request->noreg = $noreg_terakhir;
                    $log_medication_request->ket_log = 'duplicate';
                    $log_medication_request->save();
                    return response()->json([
                        'noreg' => $noreg_terakhir,
                        'message' => "duplicate",
                        'nama schedule' => 'Medication Request'
                    ], 200);
                }

                // try {
                //     $medication_statement = $this->medication_statement->create_medication_statement($id_obat_satusehat, $display_obat_satusehat, $id_patient, $id_patient_display, $encounter, $dosis_obat, $start_waktu_pemberian_obat, $end_waktu_pemberian_obat);
                //     // dd($medication_statement);
                //     $medication_statement_model = new MedicationStatementModel();
                //     $medication_statement_model->encounter = $encounter;
                //     $medication_statement_model->noreg = $noreg_terakhir;
                //     $medication_statement_model->id_medication_statement = $medication_statement->id;
                //     $medication_statement_model->save();
                // } catch (\Throwable $th) {
                //     $log_medication_statement = new LogMedicationStatement();
                //     $log_medication_statement->noreg = $noreg_terakhir;
                //     $log_medication_statement->ket_log = 'duplicate';
                //     $log_medication_statement->save();
                // }
                sleep(10);
            }

            return response()->json([
                'noreg' => $noreg_terakhir,
                'message' => 'Semua Data sukses dikirim',
                'nama schedule' => 'Medication Request'
            ], 200);
        }

        public function medication_statement(Request $request){
            set_time_limit((int) 0);
            $medication_statement = MedicationStatementModel::orderBy('noreg', 'desc')->pluck('noreg')->first();
            $log_medication_statement = LogMedicationStatement::orderBy('noreg', 'desc')->pluck('noreg')->first();
            if ($medication_statement > $log_medication_statement) {
                $noreg_terakhir = $medication_statement;
            }elseif ($medication_statement < $log_medication_statement) {
                $noreg_terakhir = $log_medication_statement;
            }elseif ($medication_statement == $log_medication_statement) {
                $noreg_terakhir = $medication_statement;
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
                    'nama schedule' => 'Medication Statement'
                ], 200);
            }

            $data_terbesar = MappingKunjunganInap::orderBy('noreg', 'desc')->pluck('noreg')->first();
            if ($noreg_terakhir > $data_terbesar) {
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "Noreg Belum Terdaftar",
                    'nama schedule' => 'Medication Statement'
                ], 200);
            }

            $mapping_kunjungan_inap = MappingKunjunganInap::where('noreg', $noreg_terakhir)->first();
            if ($mapping_kunjungan_inap == null) {
                $log_medication_statement = new LogMedicationStatement();
                $log_medication_statement->noreg = $noreg_terakhir;
                $log_medication_statement->ket_log = 'Mapping kunjungan poli tidak ditemukan';
                $log_medication_statement->save();
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "Mapping kunjungan poli tidak ditemukan",
                    'nama schedule' => 'Medication Statement'
                ], 200);
            }
            $registrasi_pasien = RegistrasiPasien::where('NOREG', $noreg_terakhir)->first();
            if ($registrasi_pasien == null) {
                $log_medication_statement = new LogMedicationStatement();
                $log_medication_statement->noreg = $noreg_terakhir;
                $log_medication_statement->ket_log = 'Noreg Belum Terdaftar';
                $log_medication_statement->save();
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "Noreg Belum Terdaftar",
                    'nama schedule' => 'Medication Statement'
                ], 200);
            }
            $mapping_pasien = MappingPasien::where('norm', $registrasi_pasien->NOPASIEN)->first();
            if ($mapping_pasien == null) {
                $log_medication_statement = new LogMedicationStatement();
                $log_medication_statement->noreg = $noreg_terakhir;
                $log_medication_statement->ket_log = "Pasien Tidak Ditemukan Di Mapping Pasien";
                $log_medication_statement->save();
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "Pasien Tidak Ditemukan Di Mapping Pasien",
                    'nama schedule' => 'Medication Statement'
                ], 200);
            }

            $mapping_dokter_spesialis = MappingDokterSpesialis::where('kodepelayanan', $registrasi_pasien->Registrasi_Dokter->KODEDOKTER)->first();
            if ($mapping_dokter_spesialis == null) {
                $log_medication_statement = new LogMedicationStatement();
                $log_medication_statement->noreg = $noreg_terakhir;
                $log_medication_statement->ket_log = "Dokter untuk Pasien ".$registrasi_pasien->Pasien->NAMAPASIEN." Tidak Ditemukan Di Mapping Dokter Spesialis";
                $log_medication_statement->save();
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "Dokter untuk Pasien ".$registrasi_pasien->Pasien->NAMAPASIEN." Tidak Ditemukan Di Mapping Dokter Spesialis",
                    'nama schedule' => 'Medication Statement'
                ], 200);
            }

            $trpmn = Trpmn::where('noreg', (String)$noreg_terakhir)->select('NORESEP', 'TGLRESEP')->first();
            // dd(date('Y-m-d', strtotime($trpmn->TGLRESEP)).'T00:00:00.000+07:00');
            // dd(date('Y-m-d', strtotime($trpmn->TGLRESEP . ' +7 days')).'T00:00:00.000+07:00');
            if ($trpmn == null) {
                $log_medication_statement = new LogMedicationStatement();
                $log_medication_statement->noreg = $noreg_terakhir;
                $log_medication_statement->ket_log = 'Pasien Tidak Memiliki Resep';
                $log_medication_statement->save();
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "Pasien Tidak Memiliki Resep",
                    'nama schedule' => 'Medication Statement'
                ], 200);
            }

            $trpdn = Trpdn::where('NORESEP', $trpmn->NORESEP)->get();
            if ($trpdn == null || $trpdn->isEmpty()) {
                $log_medication_statement = new LogMedicationStatement();
                $log_medication_statement->noreg = $noreg_terakhir;
                $log_medication_statement->ket_log = 'Pasien Tidak Memiliki Resep';
                $log_medication_statement->save();
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "Pasien Tidak Memiliki Resep",
                    'nama schedule' => 'Medication Statement'
                ], 200);
            }

            foreach ($trpdn as $item) {
                $master_kfa_obat = MasterKfaObat::where('kode_barang_mabar', $item->KODEBARANG)->first();
                if ($master_kfa_obat == null) {
                    $log_medication_statement = new LogMedicationStatement();
                    $log_medication_statement->noreg = $noreg_terakhir;
                    $log_medication_statement->ket_log = 'Obat dengan Kode ini '.$item->KODEBARANG.' Tidak Ditemukan Di Master KFA Obat';
                    $log_medication_statement->save();
                    continue;
                }
                $id_obat_satusehat = $master_kfa_obat->kode_satu_sehat;
                $display_obat_satusehat = $master_kfa_obat->keterangan_kfa;
                $id_patient = $mapping_pasien->kodesatusehat;
                $id_patient_display = $mapping_pasien->namasatusehat;
                $encounter = $mapping_kunjungan_inap->encounter;
                $tanggal_peresepan = date('Y-m-d', strtotime($trpmn->TGLRESEP)).'T00:00:00.000+07:00';
                $id_dokter_satu_sehat = $mapping_dokter_spesialis->satusehat;
                $display_dokter_satu_sehat = $mapping_dokter_spesialis->nama;
                $dosis_obat = $item->KETERANGANATRPKAI.' '.$item->KETERANGAN;
                $start_waktu_pemberian_obat = date('Y-m-d', strtotime($trpmn->TGLRESEP)).'T00:00:00.000+07:00';
                $end_waktu_pemberian_obat = date('Y-m-d', strtotime($trpmn->TGLRESEP . ' +7 days')).'T00:00:00.000+07:00';
                $jumlah_obat = intval($item->QTYBAR);
                $durasi_penggunaan = '7';

                try {
                    $medication_statement = $this->medication_statement->create_medication_statement($id_obat_satusehat, $display_obat_satusehat, $id_patient, $id_patient_display, $encounter, $dosis_obat, $start_waktu_pemberian_obat, $end_waktu_pemberian_obat);
                    // dd($medication_statement);
                    $medication_statement_model = new MedicationStatementModel();
                    $medication_statement_model->encounter = $encounter;
                    $medication_statement_model->noreg = $noreg_terakhir;
                    $medication_statement_model->id_medication_statement = $medication_statement->id;
                    $medication_statement_model->save();
                } catch (\Throwable $th) {
                    $log_medication_statement = new LogMedicationStatement();
                    $log_medication_statement->noreg = $noreg_terakhir;
                    $log_medication_statement->ket_log = 'duplicate';
                    $log_medication_statement->save();
                }
                sleep(10);
            }

            return response()->json([
                'noreg' => $noreg_terakhir,
                'message' => 'Semua Data sukses dikirim',
                'nama schedule' => 'Medication Statement'
            ], 200);
        }

        // Pengkajian Resep
        public function questionnaire_response(Request $request){
            set_time_limit((int) 0);
            $questionnaire_response = QuestionnaireResponseModel::orderBy('noreg', 'desc')->pluck('noreg')->first();
            $log_questionnaire_response = LogQuestionnaireResponse::orderBy('noreg', 'desc')->pluck('noreg')->first();
            if ($questionnaire_response > $log_questionnaire_response) {
                $noreg_terakhir = $questionnaire_response;
            }elseif ($questionnaire_response < $log_questionnaire_response) {
                $noreg_terakhir = $log_questionnaire_response;
            }elseif ($questionnaire_response == $log_questionnaire_response) {
                $noreg_terakhir = $questionnaire_response;
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
                    'nama schedule' => 'Questionnaire Response'
                ], 200);
            }

            $data_terbesar = MappingKunjunganInap::orderBy('noreg', 'desc')->pluck('noreg')->first();
            if ($noreg_terakhir > $data_terbesar) {
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "Noreg Belum Terdaftar",
                    'nama schedule' => 'Questionnaire Response'
                ], 200);
            }

            // $noreg_terakhir = '2401010001';

            $mapping_kunjungan_inap = MappingKunjunganInap::where('noreg', $noreg_terakhir)->first();
            if ($mapping_kunjungan_inap == null) {
                $log_questionnaire_response = new LogQuestionnaireResponse();
                $log_questionnaire_response->noreg = $noreg_terakhir;
                $log_questionnaire_response->ket_log = 'Mapping kunjungan poli tidak ditemukan';
                $log_questionnaire_response->save();
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "Mapping kunjungan poli tidak ditemukan",
                    'nama schedule' => 'Questionnaire Response'
                ], 200);
            }
            $registrasi_pasien = RegistrasiPasien::where('NOREG', $noreg_terakhir)->first();
            if ($registrasi_pasien == null) {
                $log_questionnaire_response = new LogQuestionnaireResponse();
                $log_questionnaire_response->noreg = $noreg_terakhir;
                $log_questionnaire_response->ket_log = 'Noreg Belum Terdaftar';
                $log_questionnaire_response->save();
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "Noreg Belum Terdaftar",
                    'nama schedule' => 'Questionnaire Response'
                ], 200);
            }
            $mapping_pasien = MappingPasien::where('norm', $registrasi_pasien->NOPASIEN)->first();
            if ($mapping_pasien == null) {
                $log_questionnaire_response = new LogQuestionnaireResponse();
                $log_questionnaire_response->noreg = $noreg_terakhir;
                $log_questionnaire_response->ket_log = "Pasien Tidak Ditemukan Di Mapping Pasien";
                $log_questionnaire_response->save();
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "Pasien Tidak Ditemukan Di Mapping Pasien",
                    'nama schedule' => 'Questionnaire Response'
                ], 200);
            }

            $mapping_dokter_spesialis = MappingDokterSpesialis::where('kodepelayanan', $registrasi_pasien->Registrasi_Dokter->KODEDOKTER)->first();
            if ($mapping_dokter_spesialis == null) {
                $log_questionnaire_response = new LogQuestionnaireResponse();
                $log_questionnaire_response->noreg = $noreg_terakhir;
                $log_questionnaire_response->ket_log = "Dokter untuk Pasien ".$registrasi_pasien->Pasien->NAMAPASIEN." Tidak Ditemukan Di Mapping Dokter Spesialis";
                $log_questionnaire_response->save();
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "Dokter untuk Pasien ".$registrasi_pasien->Pasien->NAMAPASIEN." Tidak Ditemukan Di Mapping Dokter Spesialis",
                    'nama schedule' => 'Questionnaire Response'
                ], 200);
            }

            $trpmn = Trpmn::where('noreg', (String)$noreg_terakhir)->select('NORESEP', 'TGLRESEP')->first();
            // dd(date('Y-m-d', strtotime($trpmn->TGLRESEP)).'T00:00:00.000+07:00');
            // dd(date('Y-m-d', strtotime($trpmn->TGLRESEP . ' +7 days')).'T00:00:00.000+07:00');
            if ($trpmn == null) {
                $log_questionnaire_response = new LogQuestionnaireResponse();
                $log_questionnaire_response->noreg = $noreg_terakhir;
                $log_questionnaire_response->ket_log = 'Pasien Tidak Memiliki Resep';
                $log_questionnaire_response->save();
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "Pasien Tidak Memiliki Resep",
                    'nama schedule' => 'Questionnaire Response'
                ], 200);
            }

            $trpdn = Trpdn::where('NORESEP', $trpmn->NORESEP)->get();
            if ($trpdn == null || $trpdn->isEmpty()) {
                $log_questionnaire_response = new LogQuestionnaireResponse();
                $log_questionnaire_response->noreg = $noreg_terakhir;
                $log_questionnaire_response->ket_log = 'Pasien Tidak Memiliki Resep';
                $log_questionnaire_response->save();
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "Pasien Tidak Memiliki Resep",
                    'nama schedule' => 'Questionnaire Response'
                ], 200);
            }
            $trpdn = Trpdn::where('NORESEP', $trpmn->NORESEP)->first();

            $id_patient = $mapping_pasien->kodesatusehat;
            $id_patient_display = $mapping_pasien->namasatusehat;
            $encounter = $mapping_kunjungan_inap->encounter;
            $tanggal_peresepan = date('Y-m-d', strtotime($trpmn->TGLRESEP)).'T00:00:00.000+07:00';
            $id_dokter_satu_sehat = $mapping_dokter_spesialis->satusehat;
            $display_dokter_satu_sehat = $mapping_dokter_spesialis->nama;

            try {
                $questionnaire_response = $this->questionnaire_response->create_questionnaire_response($id_patient, $id_patient_display, $encounter, $tanggal_peresepan, $id_dokter_satu_sehat, $display_dokter_satu_sehat);
                // dd($questionnaire_response);
                $questionnaire_response_model = new QuestionnaireResponseModel();
                $questionnaire_response_model->encounter = $encounter;
                $questionnaire_response_model->noreg = $noreg_terakhir;
                $questionnaire_response_model->id_questionnaire_response = $questionnaire_response->id;
                $questionnaire_response_model->save();
            } catch (\Throwable $th) {
                $log_questionnaire_response = new LogQuestionnaireResponse();
                $log_questionnaire_response->noreg = $noreg_terakhir;
                $log_questionnaire_response->ket_log = 'duplicate';
                $log_questionnaire_response->save();
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "duplicate",
                    'nama schedule' => 'Questionnaire Response'
                ], 200);
            }

            return response()->json([
                'noreg' => $noreg_terakhir,
                'message' => 'Semua Data sukses dikirim',
                'nama schedule' => 'Questionnaire Response'
            ], 200);
        }

    // ===============================End Medication================================

    public function resume_diet(Request $request){
        set_time_limit((int) 0);
        $composition = CompositionModel::orderBy('noreg', 'desc')->pluck('noreg')->first();
        $log_composition = LogComposition::orderBy('noreg', 'desc')->pluck('noreg')->first();
        if ($composition > $log_composition) {
            $noreg_terakhir = $composition;
        }elseif ($composition < $log_composition) {
            $noreg_terakhir = $log_composition;
        }elseif ($composition == $log_composition) {
            $noreg_terakhir = $composition;
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
                'nama schedule' => 'Composition'
            ], 200);
        }

        $data_terbesar = MappingKunjunganInap::orderBy('noreg', 'desc')->pluck('noreg')->first();
        if ($noreg_terakhir > $data_terbesar) {
            return response()->json([
                'noreg' => $noreg_terakhir,
                'message' => "Noreg Belum Terdaftar",
                'nama schedule' => 'Composition'
            ], 200);
        }

        $mapping_kunjungan_inap = MappingKunjunganInap::where('noreg', $noreg_terakhir)->first();
        if ($mapping_kunjungan_inap == null) {
            $log_composition = new LogComposition();
            $log_composition->noreg = $noreg_terakhir;
            $log_composition->ket_log = 'Mapping kunjungan poli tidak ditemukan';
            $log_composition->save();
            return response()->json([
                'noreg' => $noreg_terakhir,
                'message' => "Mapping kunjungan poli tidak ditemukan",
                'nama schedule' => 'Composition'
            ], 200);
        }
        $registrasi_pasien = RegistrasiPasien::where('NOREG', $noreg_terakhir)->first();
        if ($registrasi_pasien == null) {
            $log_composition = new LogComposition();
            $log_composition->noreg = $noreg_terakhir;
            $log_composition->ket_log = 'Noreg Belum Terdaftar';
            $log_composition->save();
            return response()->json([
                'noreg' => $noreg_terakhir,
                'message' => "Noreg Belum Terdaftar",
                'nama schedule' => 'Composition'
            ], 200);
        }
        $mapping_pasien = MappingPasien::where('norm', $registrasi_pasien->NOPASIEN)->first();
        if ($mapping_pasien == null) {
            $log_composition = new LogComposition();
            $log_composition->noreg = $noreg_terakhir;
            $log_composition->ket_log = "Pasien Tidak Ditemukan Di Mapping Pasien";
            $log_composition->save();
            return response()->json([
                'noreg' => $noreg_terakhir,
                'message' => "Pasien Tidak Ditemukan Di Mapping Pasien",
                'nama schedule' => 'Composition'
            ], 200);
        }

        $mapping_dokter_spesialis = MappingDokterSpesialis::where('kodepelayanan', $registrasi_pasien->Registrasi_Dokter->KODEDOKTER)->first();
        if ($mapping_dokter_spesialis == null) {
            $log_composition = new LogComposition();
            $log_composition->noreg = $noreg_terakhir;
            $log_composition->ket_log = "Dokter untuk Pasien ".$registrasi_pasien->Pasien->NAMAPASIEN." Tidak Ditemukan Di Mapping Dokter Spesialis";
            $log_composition->save();
            return response()->json([
                'noreg' => $noreg_terakhir,
                'message' => "Dokter untuk Pasien ".$registrasi_pasien->Pasien->NAMAPASIEN." Tidak Ditemukan Di Mapping Dokter Spesialis",
                'nama schedule' => 'Composition'
            ], 200);
        }

        $ass_awal_medis_rawat_inap_diet = AssessmentAwalMedisRawatInapDiet::where('noreg', (String)$noreg_terakhir)->first();
        if ($ass_awal_medis_rawat_inap_diet == null) {
            $log_composition = new LogComposition();
            $log_composition->noreg = $noreg_terakhir;
            $log_composition->ket_log = "Pasien Tidak Mempunyai Diet";
            $log_composition->save();
            return response()->json([
                'noreg' => $noreg_terakhir,
                'message' => "Pasien Tidak Mempunyai Diet",
                'nama schedule' => 'Composition'
            ], 200);
        }elseif ($ass_awal_medis_rawat_inap_diet->jenis_diet != "lainnya" && $ass_awal_medis_rawat_inap_diet->jenis_diet != null) {
            $ass_awal_medis_rawat_inap = AssessmentAwalMedisRawatInap::where('NOREG', (String)$noreg_terakhir)->first();
            $keterangan_diet = $ass_awal_medis_rawat_inap_diet->jenis_diet;
            $tanggal = date('Y-m-d', strtotime($ass_awal_medis_rawat_inap->tgl_input)).'T00:00:00.000+07:00';;
        }elseif ($ass_awal_medis_rawat_inap_diet->jenis_diet == "lainnya") {
            $ass_awal_medis_rawat_inap = AssessmentAwalMedisRawatInap::where('NOREG', (String)$noreg_terakhir)->first();
            if ($ass_awal_medis_rawat_inap == null) {
                $log_composition = new LogComposition();
                $log_composition->noreg = $noreg_terakhir;
                $log_composition->ket_log = "Pasien Tidak Mempunyai Diet";
                $log_composition->save();
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "Pasien Tidak Mempunyai Diet",
                    'nama schedule' => 'Composition'
                ], 200);
            }elseif ($ass_awal_medis_rawat_inap != null){
                if ($ass_awal_medis_rawat_inap->rencana_diet_jenis_diet != null) {
                    $keterangan_diet = $ass_awal_medis_rawat_inap->rencana_diet_jenis_diet;
                    $tanggal = date('Y-m-d', strtotime($ass_awal_medis_rawat_inap->tgl_input)).'T00:00:00.000+07:00';;
                }elseif ($ass_awal_medis_rawat_inap->rencana_diet_jenis_diet == null) {
                    $log_composition = new LogComposition();
                    $log_composition->noreg = $noreg_terakhir;
                    $log_composition->ket_log = "Pasien Tidak Mempunyai Diet";
                    $log_composition->save();
                    return response()->json([
                        'noreg' => $noreg_terakhir,
                        'message' => "Pasien Tidak Mempunyai Diet",
                        'nama schedule' => 'Composition'
                    ], 200);
                }
            }
        }else {
            $log_composition = new LogComposition();
            $log_composition->noreg = $noreg_terakhir;
            $log_composition->ket_log = "Pasien Tidak Mempunyai Diet";
            $log_composition->save();
            return response()->json([
                'noreg' => $noreg_terakhir,
                'message' => "Pasien Tidak Mempunyai Diet",
                'nama schedule' => 'Composition'
            ], 200);
        }

        $id_patient = $mapping_pasien->kodesatusehat;
        $id_patient_display = $mapping_pasien->namasatusehat;
        $encounter = $mapping_kunjungan_inap->encounter;
        $tanggal = $tanggal;
        $id_dokter_satu_sehat = $mapping_dokter_spesialis->satusehat;
        $display_dokter_satu_sehat = $mapping_dokter_spesialis->nama;
        $keterangan_diet = $keterangan_diet;

        try {
            $composition = $this->composition->create_composition($id_patient, $id_patient_display, $encounter, $tanggal, $id_dokter_satu_sehat, $display_dokter_satu_sehat, $keterangan_diet);
            // dd($composition);
            $composition_model = new CompositionModel();
            $composition_model->encounter = $encounter;
            $composition_model->noreg = $noreg_terakhir;
            $composition_model->id_composition = $composition->id;
            $composition_model->save();
        } catch (\Throwable $th) {
            $log_composition = new LogComposition();
            $log_composition->noreg = $noreg_terakhir;
            $log_composition->ket_log = 'duplicate';
            $log_composition->save();
            return response()->json([
                'noreg' => $noreg_terakhir,
                'message' => "duplicate",
                'nama schedule' => 'Composition'
            ], 200);
        }

        return response()->json([
            'noreg' => $noreg_terakhir,
            'message' => 'Semua Data sukses dikirim',
            'nama schedule' => 'Composition'
        ], 200);




    }

    // ==================================Careplan=================================
        public function careplan_rencana_rawat_pasien(Request $request){
            set_time_limit((int) 0);
            $careplan_rencana_rawat_pasien = CareplanRencanaRawatPasienModel::orderBy('noreg', 'desc')->pluck('noreg')->first();
            $log_careplan_rencana_rawat_pasien = LogCareplanRencanaRawatPasien::orderBy('noreg', 'desc')->pluck('noreg')->first();
            if ($careplan_rencana_rawat_pasien > $log_careplan_rencana_rawat_pasien) {
                $noreg_terakhir = $careplan_rencana_rawat_pasien;
            }elseif ($careplan_rencana_rawat_pasien < $log_careplan_rencana_rawat_pasien) {
                $noreg_terakhir = $log_careplan_rencana_rawat_pasien;
            }elseif ($careplan_rencana_rawat_pasien == $log_careplan_rencana_rawat_pasien) {
                $noreg_terakhir = $careplan_rencana_rawat_pasien;
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
                    'nama schedule' => 'Careplan Rencana Rawat Pasien'
                ], 200);
            }

            $data_terbesar = MappingKunjunganInap::orderBy('noreg', 'desc')->pluck('noreg')->first();
            if ($noreg_terakhir > $data_terbesar) {
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "Noreg Belum Terdaftar",
                    'nama schedule' => 'Careplan Rencana Rawat Pasien'
                ], 200);
            }
            // $noreg_terakhir = '2401010001';

            $mapping_kunjungan_inap = MappingKunjunganInap::where('noreg', $noreg_terakhir)->first();
            if ($mapping_kunjungan_inap == null) {
                $log_careplan_rencana_rawat_pasien = new LogCareplanRencanaRawatPasien();
                $log_careplan_rencana_rawat_pasien->noreg = $noreg_terakhir;
                $log_careplan_rencana_rawat_pasien->ket_log = 'Mapping kunjungan poli tidak ditemukan';
                $log_careplan_rencana_rawat_pasien->save();
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "Mapping kunjungan poli tidak ditemukan",
                    'nama schedule' => 'Careplan Rencana Rawat Pasien'
                ], 200);
            }
            $registrasi_pasien = RegistrasiPasien::where('NOREG', $noreg_terakhir)->first();
            if ($registrasi_pasien == null) {
                $log_careplan_rencana_rawat_pasien = new LogCareplanRencanaRawatPasien();
                $log_careplan_rencana_rawat_pasien->noreg = $noreg_terakhir;
                $log_careplan_rencana_rawat_pasien->ket_log = 'Noreg Belum Terdaftar';
                $log_careplan_rencana_rawat_pasien->save();
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "Noreg Belum Terdaftar",
                    'nama schedule' => 'Careplan Rencana Rawat Pasien'
                ], 200);
            }
            $mapping_pasien = MappingPasien::where('norm', $registrasi_pasien->NOPASIEN)->first();
            if ($mapping_pasien == null) {
                $log_careplan_rencana_rawat_pasien = new LogCareplanRencanaRawatPasien();
                $log_careplan_rencana_rawat_pasien->noreg = $noreg_terakhir;
                $log_careplan_rencana_rawat_pasien->ket_log = "Pasien Tidak Ditemukan Di Mapping Pasien";
                $log_careplan_rencana_rawat_pasien->save();
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "Pasien Tidak Ditemukan Di Mapping Pasien",
                    'nama schedule' => 'Careplan Rencana Rawat Pasien'
                ], 200);
            }

            $mapping_dokter_spesialis = MappingDokterSpesialis::where('kodepelayanan', $registrasi_pasien->Registrasi_Dokter->KODEDOKTER)->first();
            if ($mapping_dokter_spesialis == null) {
                $log_careplan_rencana_rawat_pasien = new LogCareplanRencanaRawatPasien();
                $log_careplan_rencana_rawat_pasien->noreg = $noreg_terakhir;
                $log_careplan_rencana_rawat_pasien->ket_log = "Dokter untuk Pasien ".$registrasi_pasien->Pasien->NAMAPASIEN." Tidak Ditemukan Di Mapping Dokter Spesialis";
                $log_careplan_rencana_rawat_pasien->save();
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "Dokter untuk Pasien ".$registrasi_pasien->Pasien->NAMAPASIEN." Tidak Ditemukan Di Mapping Dokter Spesialis",
                    'nama schedule' => 'Careplan Rencana Rawat Pasien'
                ], 200);
            }

            $bpjs_rencana_kontrol = BpjsRencanaKontrol::where('noreg', (String)$noreg_terakhir)->first();
            // dd($bpjs_rencana_kontrol);
            if ($bpjs_rencana_kontrol != null) {
                $id_patient                 = $mapping_pasien->kodesatusehat;
                $id_patient_display         = $mapping_pasien->namasatusehat;
                $encounter                  = $mapping_kunjungan_inap->encounter;
                $tanggal                    = date('Y-m-d', strtotime($bpjs_rencana_kontrol->tanggal_kunjungan)).'T12:00:00.000+07:00';
                $id_dokter_satu_sehat       = $mapping_dokter_spesialis->satusehat;
                $display_dokter_satu_sehat  = $mapping_dokter_spesialis->nama;
                try {
                    $careplan = $this->careplan->create_careplane_rencana_rawat_pasien($id_patient, $id_patient_display, $encounter, $tanggal, $id_dokter_satu_sehat, $display_dokter_satu_sehat);
                    // dd($careplan);
                    $composition_model = new CareplanRencanaRawatPasienModel();
                    $composition_model->encounter = $encounter;
                    $composition_model->noreg = $noreg_terakhir;
                    $composition_model->id_careplan = $careplan->id;
                    $composition_model->save();
                } catch (\Throwable $th) {
                    $log_careplan_rencana_rawat_pasien = new LogCareplanRencanaRawatPasien();
                    $log_careplan_rencana_rawat_pasien->noreg = $noreg_terakhir;
                    $log_careplan_rencana_rawat_pasien->ket_log = "duplicate";
                    $log_careplan_rencana_rawat_pasien->save();
                    return response()->json([
                        'noreg' => $noreg_terakhir,
                        'message' => "duplicate",
                        'nama schedule' => 'Careplan Rencana Rawat Pasien'
                    ], 200);
                }
            }else {
                $log_careplan_rencana_rawat_pasien = new LogCareplanRencanaRawatPasien();
                $log_careplan_rencana_rawat_pasien->noreg = $noreg_terakhir;
                $log_careplan_rencana_rawat_pasien->ket_log = "Pasien Tidak Memiliki Rencana Kontrol";
                $log_careplan_rencana_rawat_pasien->save();
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "Pasien Tidak Memiliki Rencana Kontrol",
                    'nama schedule' => 'Careplan Rencana Rawat Pasien'
                ], 200);
            }

            return response()->json([
                'noreg' => $noreg_terakhir,
                'message' => 'Semua Data sukses dikirim',
                'nama schedule' => 'Careplan Rencana Rawat Pasien'
            ], 200);

        }
    // ==================================End Careplan=================================


}
