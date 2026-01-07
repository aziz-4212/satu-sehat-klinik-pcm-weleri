<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use DB;
use App\Services\Config;
use App\Services\Condition;
use App\Services\Patient;
use App\Services\Encounter;

use App\Models\RegistrasiPasien;
use App\Models\MappingPasien;
use App\Models\MappingKunjunganPoli;
use App\Models\MappingDokterSpesialis;
use App\Models\MappingOrganization;
use App\Models\LogEncounter;
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
use App\Models\Mapmr;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Scheduling\Schedule;
class ResumeMedisRawatJalanController extends Controller
{
    public function __construct()
    {
        $this->patient = new Patient;
        $this->encounter = new Encounter;
        $this->condition = new Condition;
        $this->observasion = new Observasion;
        // $this->sevice_request = new SeviceRequest;
    }

    // ===============================Pendafataran Pasien===============================
        public function pendaftaran_pendataan_pasien_index(Request $request){
            if (isset($request->tanggal)) {
                $yesterdayFormatted = Carbon::parse($request->tanggal)->format('ymd');

                $mapping_kunjungan_poli = MappingKunjunganPoli::where('noreg', 'like', $yesterdayFormatted.'%')->pluck('noreg');
                $log_encounter = LogEncounter::where('noreg', 'like', $yesterdayFormatted.'%')->pluck('noreg');
                $registrasi_pasien = RegistrasiPasien::where('noreg', 'like', $yesterdayFormatted.'%')
                                    ->whereNotIn('NOREG', $mapping_kunjungan_poli)
                                    ->whereNotIn('NOREG', $log_encounter)
                                    ->whereHas('Registrasi_Dokter', function ($query) {
                                        $query->where('BAGREGDR', 'like', '91%');
                                    })->orderBy('NOREG')
                                    ->whereHas('Pasien', function ($query) {
                                        $query->where('NOKTP', '!=', null);
                                        $query->where('NOKTP', 'not like', '0%');
                                    })
                                    ->paginate(100)->appends(request()->query());
            }else {
                // $now = Carbon::now()->setTimezone('Asia/Jakarta');
                // $yesterday = $now->subDay();
                // $yesterdayFormatted = $yesterday->format('ymd');
                $registrasi_pasien = null;
            }
            return view('resume-medis-rawat-jalan.pendaftaran-pendataan-pasien.index', compact('registrasi_pasien'));
        }

        public function pendaftaran_pendataan_pasien_store(Request $request){
            set_time_limit((int) 900000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000);
            $now = Carbon::now()->setTimezone('Asia/Jakarta');
            $now->format('Y-m-d H:i:s.v');
            if ($request->noreg != null) {
                foreach ($request->noreg as $item){
                    $registrasi_pasien = RegistrasiPasien::where('NOREG', $item)->first();
                    $mapping_dokter_spesialis = MappingDokterSpesialis::where('kodepelayanan', $registrasi_pasien->Registrasi_Dokter->KODEDOKTER)->first();
                    if ($mapping_dokter_spesialis == null) {
                        $log_encounter = new LogEncounter();
                        $log_encounter->noreg = $item;
                        $log_encounter->ket_log = 'Dokter untuk Pasien '.$registrasi_pasien->Pasien->NAMAPASIEN.' Tidak Ditemukan Di Mapping Dokter Spesialis';
                        $log_encounter->save();
                        continue;
                        // return redirect()->back()->with('error', 'Dokter untuk Pasien '.$registrasi_pasien->Pasien->NAMAPASIEN.' Tidak Ditemukan Di Mapping Dokter Spesialis');
                    }
                    $mapping_organization = MappingOrganization::where('koders', $registrasi_pasien->Registrasi_Dokter->BAGREGDR)->first();

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
                        $log_encounter = new LogEncounter();
                        $log_encounter->noreg = $item;
                        $log_encounter->ket_log = 'NIK '.$registrasi_pasien->Pasien->NAMAPASIEN.' Tidak Ditemukan';
                        $log_encounter->save();
                        continue;
                        // return redirect()->back()->with('error', 'NIK '.$registrasi_pasien->Pasien->NAMAPASIEN.' Tidak Ditemukan');
                    }
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

                        $encounter = $this->encounter->create($id_patient, $name_patient, $id_practitioner, $name_practitioner, $date, $id_location, $name_location);
                        $mapping_kunjungan_poli = new MappingKunjunganPoli();
                        $mapping_kunjungan_poli->noreg = $item;
                        $mapping_kunjungan_poli->encounter = $encounter->id;
                        $mapping_kunjungan_poli->tanggal = $now;
                        $mapping_kunjungan_poli->save();
                    } catch (\Throwable $th) {
                        $log_encounter = new LogEncounter();
                        $log_encounter->noreg = $item;
                        $log_encounter->ket_log = $th->getMessage();
                        $log_encounter->save();
                    }
                    // ===================================End Encounter==============================================
                }
                return redirect()->back()->with('success', 'Data Berhasil Disimpan');
            }else {
                return redirect()->back()->with('error', 'Anda Belum Memilih Data');
            }
        }
    // ===============================End Pendafataran Pasien===============================

    // ===============================Anamnesis Keluhan Utama================================
        public function keluhan_utama_index(Request $request){
            if (isset($request->tanggal)) {
                $yesterdayFormatted = Carbon::parse($request->tanggal)->format('ymd');

                $diagnosis = Diagnosis::where('noreg', 'like', $yesterdayFormatted.'%')->pluck('noreg');
                $log_diagnosis = LogDiagnosis::where('noreg', 'like', $yesterdayFormatted.'%')->pluck('noreg');
                $mapping_kunjungan_poli = MappingKunjunganPoli::whereNotIn('noreg', $diagnosis)
                                            ->whereNotIn('noreg', $log_diagnosis)
                                            ->where('noreg', 'like', $yesterdayFormatted.'%')
                                            ->paginate(5)->appends(request()->query());
                // $mapping_kunjungan_poli = MappingKunjunganPoli::paginate(25);
            }else {
                $mapping_kunjungan_poli = null;
            }
            // $cek_cekin = DB::connection('sqlsrv2')
            // ->table('_chekinpoli')
            // ->where('noreg', '2402210138')
            // ->first();
            // dd($cek_cekin);
            return view('resume-medis-rawat-jalan.anamnesis-keluhan-utama.index', compact('mapping_kunjungan_poli'));
        }

        public function keluhan_utama_store(Request $request){
            set_time_limit((int) 900000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000);
            if ($request->noreg != null) {
                foreach ($request->noreg as $item){

                    try {
                        $bpjs_sep_manual = BpjsSepManual::where('noreg', $item)->first();
                        $registrasi_pasien = RegistrasiPasien::where('NOREG', $item)->first();
                        $mapping_kunjungan_poli = MappingKunjunganPoli::where('noreg', $item)->first();
                        $mapping_pasien = MappingPasien::where('norm', $registrasi_pasien->NOPASIEN)->first();
                        $nosep = CheckinPoli::where('noreg', $item)->first();

                        $apiUrl = "http://10.10.6.14:10000/api/sep-new/{$nosep->nosep}";
                        $client = new Client();
                        $response = $client->get($apiUrl);
                        $apiData = $response->getBody()->getContents();
                        $dataArray = json_decode($apiData, true);
                    } catch (\Throwable $th) {
                        $dataArray = null;
                        $log_diagnosis = new LogDiagnosis();
                        $log_diagnosis->noreg = $item;
                        $log_diagnosis->ket_log = 'Sep Tidak Ditemukan';
                        $log_diagnosis->save();
                    }

                    if ($dataArray != null) {
                        try {
                            $diagnosa = explode(' - ', $dataArray['response']['diagnosa']);
                            $kode_diagnosa = $diagnosa[0];
                            $deskripsi_diagnosa = $diagnosa[1];
                            $data = $this->condition->create_diagnosis($kode_diagnosa, $deskripsi_diagnosa, $mapping_pasien, $mapping_kunjungan_poli);
                            $diagnosis = new Diagnosis();
                            $diagnosis->encounter = (String)$mapping_kunjungan_poli->encounter;
                            $diagnosis->noreg = (String)$item;
                            $diagnosis->kode_icd = (String)$kode_diagnosa;
                            $diagnosis->nama_icd = (String)$deskripsi_diagnosa;
                            $diagnosis->id_diagnosa = (String)$data->id;
                            $diagnosis->save();
                        } catch (\Throwable $th) {
                            $log_diagnosis = new LogDiagnosis();
                            $log_diagnosis->noreg = $item;
                            $log_diagnosis->ket_log = 'duplicate';
                            $log_diagnosis->save();
                        }
                    }
                }
                return redirect()->back()->with('success', 'Data Berhasil Disimpan');
            }else {
                return redirect()->back()->with('error', 'Anda Belum Memilih Data');
            }
        }
    // ===============================End Anamnesis Keluhan Utama================================

    // ===============================Anamnesis Riwayat Alergi================================
        public function riwayat_alergi_index(Request $request){
            // if (isset($request->tanggal)) {
            //     $yesterdayFormatted = Carbon::parse($request->tanggal)->format('ymd');

            //     $diagnosis = Diagnosis::where('noreg', 'like', $yesterdayFormatted.'%')->pluck('noreg');
            //     $log_diagnosis = LogDiagnosis::where('noreg', 'like', $yesterdayFormatted.'%')->pluck('noreg');
            //     $mapping_kunjungan_poli = MappingKunjunganPoli::whereNotIn('noreg', $diagnosis)
            //                                 ->whereNotIn('noreg', $log_diagnosis)
            //                                 ->where('noreg', 'like', $yesterdayFormatted.'%')
            //                                 ->paginate(100)->appends(request()->query());
            //     // $mapping_kunjungan_poli = MappingKunjunganPoli::paginate(25);
            // }else {
            //     $mapping_kunjungan_poli = null;
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
                                        ->paginate(5)->appends(request()->query());
            return view('resume-medis-rawat-jalan.hasil-pemeriksaan-fisik.index', compact('diagnosis'));
        }

        public function hasil_pemeriksaan_fisik_store(Request $request){
            set_time_limit((int) 900000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000);
            if ($request->noreg != null) {
                foreach ($request->noreg as $item){
                    $rj_skrining_tb = RjSkriningTb::where('noreg', $item)->first();
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
                        $log_observation->noreg = $item;
                        $log_observation->ket_log = "Skrining Tidak Ditemukan";
                        $log_observation->save();
                    }
                    // dd('nadi '.$nadi.' rr '.$rr.' nilai_sistole '.$nilai_sistole.' nilai_diastole '.$nilai_diastole.' suhu_tubuh '.$suhu_tubuh);
                    $registrasi_pasien = RegistrasiPasien::where('NOREG', $item)->first();
                    $mapping_pasien = MappingPasien::where('norm', $registrasi_pasien->NOPASIEN)->first();
                    $diagnosis = Diagnosis::where('noreg', $item)->first();

                    $mapping_dokter_spesialis = MappingDokterSpesialis::where('kodepelayanan', $registrasi_pasien->Registrasi_Dokter->KODEDOKTER)->first();
                    if ($mapping_dokter_spesialis == null) {
                        return redirect()->back()->with('error', 'Dokter untuk Pasien '.$registrasi_pasien->Pasien->NAMAPASIEN.' Tidak Ditemukan Di Mapping Dokter Spesialis');
                    }

                    $id_practitioner = $mapping_dokter_spesialis->satusehat;
                    $dateArray = explode(' ', $registrasi_pasien->TGLREG);
                    $dateValue = $dateArray[0];
                    $date = $dateValue;

                    $observation = new Observation();
                    $observation->encounter = $diagnosis->encounter;
                    $observation->noreg = $item;
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
                            $log_observation->noreg = $item;
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
                            $log_observation->noreg = $item;
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
                            $log_observation->noreg = $item;
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
                            $log_observation->noreg = $item;
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
                            $log_observation->noreg = $item;
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
                }
                return redirect()->back()->with('success', 'Data Berhasil Disimpan');
            }else {
                return redirect()->back()->with('error', 'Anda Belum Memilih Data');
            }
        }
    // ===============================End Hasil Pemeriksaan Fisik============================

    // +++++++++++++++++++++++++++++++++++Rujukan Laboratoriom++++++++++++++++++++++++++++
        // ===================================Request Service=========================================
            public function permintaan_pemeriksaan_penunjang_laboratorium_index(Request $request){
                if (isset($request->tanggal)) {
                    $yesterdayFormatted = Carbon::parse($request->tanggal)->format('ymd');

                    $diagnosis = Diagnosis::where('noreg', 'like', $yesterdayFormatted.'%')->pluck('noreg');
                    $log_diagnosis = LogDiagnosis::where('noreg', 'like', $yesterdayFormatted.'%')->pluck('noreg');
                    $mapping_kunjungan_poli = MappingKunjunganPoli::whereNotIn('noreg', $diagnosis)
                                                ->whereNotIn('noreg', $log_diagnosis)
                                                ->where('noreg', 'like', $yesterdayFormatted.'%')
                                                ->paginate(5)->appends(request()->query());
                    // $mapping_kunjungan_poli = MappingKunjunganPoli::paginate(25);
                }else {
                    $mapping_kunjungan_poli = null;
                }
                return view('resume-medis-rawat-jalan.laboratorium.request-service.index', compact('mapping_kunjungan_poli'));
            }

            public function permintaan_pemeriksaan_penunjang_laboratorium_store(Request $request){
                set_time_limit((int) 900000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000);
                if ($request->noreg != null) {
                    foreach ($request->noreg as $item){
                        $bpjs_sep_manual = BpjsSepManual::where('noreg', $item)->first();
                        $registrasi_pasien = RegistrasiPasien::where('NOREG', $item)->first();
                        $mapping_kunjungan_poli = MappingKunjunganPoli::where('noreg', $item)->first();
                        $mapping_pasien = MappingPasien::where('norm', $registrasi_pasien->NOPASIEN)->first();
                        dd($request->all());

                        try {
                            $data = $this->sevice_request->create_laboratorium($code_loinc, $desc_loinc, $id_patient, $encounter, $practitioner_requester, $practitioner_performer);
                            $diagnosis = new Diagnosis();
                            $diagnosis->encounter = (String)$mapping_kunjungan_poli->encounter;
                            $diagnosis->noreg = (String)$item;
                            $diagnosis->kode_icd = (String)$kode_diagnosa;
                            $diagnosis->nama_icd = (String)$deskripsi_diagnosa;
                            $diagnosis->id_diagnosa = (String)$data->id;
                            $diagnosis->save();
                        } catch (\Throwable $th) {
                            $log_diagnosis = new LogDiagnosis();
                            $log_diagnosis->noreg = $item;
                            $log_diagnosis->ket_log = 'duplicate';
                            $log_diagnosis->save();
                        }
                    }
                    return redirect()->back()->with('success', 'Data Berhasil Disimpan');
                }else {
                    return redirect()->back()->with('error', 'Anda Belum Memilih Data');
                }
            }
        // ===================================End Request Service=====================================
    // +++++++++++++++++++++++++++++++++++End Rujukan Laboratoriom++++++++++++++++++++++++

    // ===============================Anamnesis Keluhan Utama================================
        public function peresepan_obat_index(Request $request){
            if (isset($request->tanggal)) {
                $yesterdayFormatted = Carbon::parse($request->tanggal)->format('ymd');

                $diagnosis = Diagnosis::where('noreg', 'like', $yesterdayFormatted.'%')->pluck('noreg');
                $log_diagnosis = LogDiagnosis::where('noreg', 'like', $yesterdayFormatted.'%')->pluck('noreg');
                $mapping_kunjungan_poli = MappingKunjunganPoli::whereNotIn('noreg', $diagnosis)
                                            ->whereNotIn('noreg', $log_diagnosis)
                                            ->where('noreg', 'like', $yesterdayFormatted.'%')
                                            ->paginate(5)->appends(request()->query());
            }else {
                $mapping_kunjungan_poli = null;
            }
            return view('resume-medis-rawat-jalan.tatalaksana-peresepan-obat.index', compact('mapping_kunjungan_poli'));
        }

        public function peresepan_obat_store(Request $request){
            set_time_limit((int) 900000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000);
            if ($request->noreg != null) {
                foreach ($request->noreg as $item){

                    try {
                        $bpjs_sep_manual = BpjsSepManual::where('noreg', $item)->first();
                        $registrasi_pasien = RegistrasiPasien::where('NOREG', $item)->first();
                        $mapping_kunjungan_poli = MappingKunjunganPoli::where('noreg', $item)->first();
                        $mapping_pasien = MappingPasien::where('norm', $registrasi_pasien->NOPASIEN)->first();
                        $nosep = CheckinPoli::where('noreg', $item)->first();

                        $apiUrl = "http://10.10.6.14:10000/api/sep-new/{$nosep->nosep}";
                        $client = new Client();
                        $response = $client->get($apiUrl);
                        $apiData = $response->getBody()->getContents();
                        $dataArray = json_decode($apiData, true);
                    } catch (\Throwable $th) {
                        $dataArray = null;
                        $log_diagnosis = new LogDiagnosis();
                        $log_diagnosis->noreg = $item;
                        $log_diagnosis->ket_log = 'Sep Tidak Ditemukan';
                        $log_diagnosis->save();
                    }

                    if ($dataArray != null) {
                        try {
                            $diagnosa = explode(' - ', $dataArray['response']['diagnosa']);
                            $kode_diagnosa = $diagnosa[0];
                            $deskripsi_diagnosa = $diagnosa[1];
                            $data = $this->condition->create_diagnosis($kode_diagnosa, $deskripsi_diagnosa, $mapping_pasien, $mapping_kunjungan_poli);
                            $diagnosis = new Diagnosis();
                            $diagnosis->encounter = (String)$mapping_kunjungan_poli->encounter;
                            $diagnosis->noreg = (String)$item;
                            $diagnosis->kode_icd = (String)$kode_diagnosa;
                            $diagnosis->nama_icd = (String)$deskripsi_diagnosa;
                            $diagnosis->id_diagnosa = (String)$data->id;
                            $diagnosis->save();
                        } catch (\Throwable $th) {
                            $log_diagnosis = new LogDiagnosis();
                            $log_diagnosis->noreg = $item;
                            $log_diagnosis->ket_log = 'duplicate';
                            $log_diagnosis->save();
                        }
                    }
                }
                return redirect()->back()->with('success', 'Data Berhasil Disimpan');
            }else {
                return redirect()->back()->with('error', 'Anda Belum Memilih Data');
            }
        }
    // ===============================End Anamnesis Keluhan Utama================================
}
