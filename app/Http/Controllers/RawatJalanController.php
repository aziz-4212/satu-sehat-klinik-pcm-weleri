<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Services\Patient;
use App\Services\RawatJalan;
use App\Models\Rekam;
use App\Models\RekamLaborat;
use App\Models\RekamDiagnosa;
use App\Models\Pasien;
use App\Models\Obat;
use App\Models\PengeluaranObat;
use App\Models\RJ00UkpKefarmasianLaboratorium;
use App\Models\RJ_00_Organisation_Location;
use App\Models\RJ_01_Patient;
use App\Models\RJ_01_Practitioner;
use App\Models\RJ_02_A_Kunjungan_Baru;
use App\Models\RJ_02_A_Kunjungan_Baru_Log;
use App\Models\RJ_02_B_Masuk_Ruang;
use App\Models\RJ_02_B_Masuk_Ruang_Log;
use App\Models\RJ_04_Pemeriksaan_Tanda_Tanda_Vital;
use App\Models\RJ_04_Pemeriksaan_Tanda_Tanda_Vital_Log;
use App\Models\RJ_06_Riwayat_Perjalanan_Penyakit;
use App\Models\RJ_06_Riwayat_Perjalanan_Penyakit_Log;
use App\Models\RJ_10_laboratory;
use App\Models\RJ_10_laboratory_Log;
use App\Models\RJ_10_Radiologi;
use App\Models\RJ_10_Radiologi_Log;
use App\Models\RJ_12_Diagnosis;
use App\Models\RJ_12_Diagnosis_Log;
use App\Models\RJ_14_Tindakan_Konseling_Log;
use App\Models\RJ_14_Tindakan_Konseling;
use App\Models\RJ_15_Medication_Obat;
use App\Models\RJ_15_Medication_Request;
use App\Models\RJ_15_Medication_Request_Log;
use App\Models\RJ_15_Questionnaire_Response;
use App\Models\RJ_15_Questionnaire_Response_Log;
use App\Models\RJ_15_Medication_Dispense;
use App\Models\RJ_15_Medication_Dispense_Log;
use App\Models\Configs;
class RawatJalanController extends Controller
{
    public function __construct()
    {
        $this->rawatJalan = new RawatJalan;
        $this->patient = new Patient;
    }

    public function menu(){
        return view('rawat-jalan.menu');
    }

    // ========00 Membuat Struktur Organisasi dan Lokasi========
        public function membuat_struktur_organisasi_dan_lokasi(){
            $RJ_00_Organisation_Location = RJ_00_Organisation_Location::orderBy('id', 'desc')->paginate(25);
            $data_option_RJ_00_Organisation_Location = RJ_00_Organisation_Location::orderBy('id', 'desc')->get();
            return view('rawat-jalan.00-membuat-struktur-organisasi-dan-lokasi.index', compact('RJ_00_Organisation_Location', 'data_option_RJ_00_Organisation_Location'));
        }

        public function membuat_struktur_organisasi_dan_lokasi_store(Request $request)
        {
            $request->validate([
                'nama' => 'required|string',
                'parent_id' => 'nullable|integer',
                // 'id_organisation' => 'required|string',
                // 'id_location' => 'required|string',
            ]);
            $org = new RJ_00_Organisation_Location();
            $org->nama = $request->nama;
            $org->parent_id = $request->parent_id ? $request->parent_id : null;
            $org->kode_bagian = $request->kode_bagian;
            // $org->id_organisation = $request->id_organisation;
            // $org->id_location = $request->id_location;
            $org->save();
            return redirect()->back()->with('success', 'Data organisasi/lokasi berhasil ditambahkan');
        }

        public function ambil_id_organisasi_satu_sehat($id)
        {
            $RJ_00_Organisation_Location = RJ_00_Organisation_Location::find($id);
            $nama_divisi = $RJ_00_Organisation_Location->nama;
            $sub_org_id = $RJ_00_Organisation_Location->parent->id_organisation;
            $sub_org_nama = $RJ_00_Organisation_Location->parent->nama;

            // $data = $this->rawatJalan->ambil_data_organisasi_rumah_sakit();
            $data = $this->rawatJalan->ambil_data_organisasi_divisi($nama_divisi, $sub_org_id, $sub_org_nama);
            if (isset($data->id)) {
                $RJ_00_Organisation_Location->id_organisation = $data->id;
                $RJ_00_Organisation_Location->save();
                return redirect()->back()->with('success', 'Id organisasi Satu Sehat berhasil diambil');
            }else {
                return redirect()->back()->with('success', 'Gagagal mengambil Id organisasi Satu Sehat');
            }
        }

        public function ambil_id_lokasi_satu_sehat($id)
        {
            $RJ_00_Organisation_Location = RJ_00_Organisation_Location::find($id);
            $nama = $RJ_00_Organisation_Location->nama;
            $org_id = $RJ_00_Organisation_Location->id_organisation;
            // $data = $this->rawatJalan->ambil_data_lokasi_rumah_sakit($org_id);
            $data = $this->rawatJalan->ambil_data_lokasi_divisi($nama, $org_id);
            if (isset($data->id)) {
                $RJ_00_Organisation_Location->id_location = $data->id;
                $RJ_00_Organisation_Location->save();
                return redirect()->back()->with('success', 'Id Lokasi Satu Sehat berhasil diambil');
            }else {
                return redirect()->back()->with('success', 'Gagagal mengambil Id Lokasi Satu Sehat');
            }
        }
    // ========End 00 Membuat Struktur Organisasi dan Lokasi========

    // ========01 Mencari Data Pasien dan Nakes========
        public function mencari_data_pasien_dan_nakes_menu(){
            return view('rawat-jalan.01-mencari-data-pasien-dan-nakes.menu');
        }

        public function pasien_index(){
            $pasien = Pasien::orderBy('id', 'asc')->paginate(25);
            return view('rawat-jalan.01-mencari-data-pasien-dan-nakes.pasien.index', compact('pasien'));
        }

        public function pasien_ambil_satu_sehat_id(Request $request){
            $rj_01_patient = RJ_01_Patient::orderBy('norm', 'desc')->select('norm')->first();
            $norm = str_pad((int)$rj_01_patient->norm + 1, 8, '0', STR_PAD_LEFT);
            $pasien = Pasien::where('NOPASIEN', $norm)->select('NOPASIEN' ,'NOKTP')->first();
            if ($pasien == null) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data Pasien Kosong',
                    'data' => null
                ], 404);
            }else if ($pasien->NOKTP == null && $pasien->NOPASIEN == null) {
                $rj_01_patient = new RJ_01_Patient();
                $rj_01_patient->norm = $pasien->NOPASIEN;
                $rj_01_patient->satu_sehat_id = "NIK PASIEN NULL";
                $rj_01_patient->save();

                return response()->json([
                    'success' => false,
                    'message' => 'NIK Pasien Tidak Ditemukan',
                    'data' => null
                ], 404);
            }else {
                $data = $this->rawatJalan->pasien($pasien->NOKTP);
                if (isset($data->entry[0]->resource->id)) {
                    $rj_01_patient = new RJ_01_Patient();
                    $rj_01_patient->norm = $pasien->NOPASIEN;
                    $rj_01_patient->satu_sehat_id = $data->entry[0]->resource->id;
                    $rj_01_patient->response = json_encode($data);
                    $rj_01_patient->save();
                    return response()->json([
                        'success' => true,
                        'message' => 'Berhasil Mendapatkan Data Pasien',
                        'data' => $data
                    ], 200);
                } else {
                    $rj_01_patient = new RJ_01_Patient();
                    $rj_01_patient->norm = $pasien->NOPASIEN;
                    $rj_01_patient->satu_sehat_id = "NIK TIDAK DITEMUKAN";
                    $rj_01_patient->response = $data;
                    $rj_01_patient->save();
                    return response()->json([
                        'success' => false,
                        'message' => 'NIK Pasien Tidak Ditemukan',
                        'data' => null
                    ], 404);
                }
            }
        }

        public function practitioner_index(){
            $karyawan = RJ_01_Practitioner::orderBy('id', 'desc')->paginate(25);
            return view('rawat-jalan.01-mencari-data-pasien-dan-nakes.practitioner.index', compact('karyawan'));
        }

        public function practitioner_sinkronisasi_data_pegawai(Request $request){
            $karyawan = Personel::get();
            foreach ($karyawan as $item) {
                if ($item->NAMA_KRT == null && ($item->TGL_LHR_PERSON == null || $item->KLN_PERSONEL == null)) {
                    $rj_01_practitioner = RJ_01_Practitioner::where('NIK_pegawai', $item->NIK)->first();
                    $rj_01_practitioner = $rj_01_practitioner ?? new RJ_01_Practitioner();
                    $rj_01_practitioner->NIK_pegawai = $item->NIK;
                    $rj_01_practitioner->NIK_KTP = $item->NAMA_KRT;
                    $rj_01_practitioner->nama = $item->NM_PERSON;
                    $rj_01_practitioner->tanggal_lahir = \Carbon\Carbon::parse($item->TGL_LHR_PERSON)->format('Y-m-d');
                    $rj_01_practitioner->jenis_kelamin = $item->KLN_PERSONEL === 'L' ? 'male' : ($item->KLN_PERSONEL === 'P' ? 'female' : null);
                    $rj_01_practitioner->satu_sehat_id = NULL;
                    $rj_01_practitioner->response = NULL;
                    $rj_01_practitioner->kode_dokter = "karyawan Bukan Dokter";
                    $rj_01_practitioner->save();
                }else {
                    $data_nik = $this->rawatJalan->karyawan_nik($item->NAMA_KRT);

                    $nama = $item->NM_PERSON;
                    $tanggal_lahir = \Carbon\Carbon::parse($item->TGL_LHR_PERSON)->format('Y-m-d');
                    $jenis_kelamin = $item->KLN_PERSONEL === 'L' ? 'male' : ($item->KLN_PERSONEL === 'P' ? 'female' : null);
                    $data_tgl_lahir = $this->rawatJalan->karyawan_tgl_lahir($nama, $tanggal_lahir, $jenis_kelamin);
                    if (isset($data_nik->entry[0]->resource->id)) {
                        $rj_01_practitioner = RJ_01_Practitioner::where('NIK_pegawai', $item->NIK)->first();
                        $rj_01_practitioner = $rj_01_practitioner ?? new RJ_01_Practitioner();
                        $rj_01_practitioner->NIK_pegawai = $item->NIK;
                        $rj_01_practitioner->NIK_KTP = $item->NAMA_KRT;
                        $rj_01_practitioner->nama = $item->NM_PERSON;
                        $rj_01_practitioner->tanggal_lahir = \Carbon\Carbon::parse($item->TGL_LHR_PERSON)->format('Y-m-d');
                        $rj_01_practitioner->jenis_kelamin = $item->KLN_PERSONEL === 'L' ? 'male' : ($item->KLN_PERSONEL === 'P' ? 'female' : null);
                        $rj_01_practitioner->satu_sehat_id = $data_nik->entry[0]->resource->id;
                        $rj_01_practitioner->response = json_encode($data_nik);
                        $rj_01_practitioner->kode_dokter = "karyawan Bukan Dokter";
                        $rj_01_practitioner->save();
                    }elseif (isset($data_tgl_lahir->entry[0]->resource->id)) {
                        $rj_01_practitioner = RJ_01_Practitioner::where('NIK_pegawai', $item->NIK)->first();
                        $rj_01_practitioner = $rj_01_practitioner ?? new RJ_01_Practitioner();
                        $rj_01_practitioner->NIK_pegawai = $item->NIK;
                        $rj_01_practitioner->NIK_KTP = $item->NAMA_KRT;
                        $rj_01_practitioner->nama = $item->NM_PERSON;
                        $rj_01_practitioner->tanggal_lahir = \Carbon\Carbon::parse($item->TGL_LHR_PERSON)->format('Y-m-d');
                        $rj_01_practitioner->jenis_kelamin = $item->KLN_PERSONEL === 'L' ? 'male' : ($item->KLN_PERSONEL === 'P' ? 'female' : null);
                        $rj_01_practitioner->satu_sehat_id = $data_tgl_lahir->entry[0]->resource->id;
                        $rj_01_practitioner->response = json_encode($data_tgl_lahir);
                        $rj_01_practitioner->kode_dokter = "karyawan Bukan Dokter";
                        $rj_01_practitioner->save();
                    } else {
                        $rj_01_practitioner = RJ_01_Practitioner::where('NIK_pegawai', $item->NIK)->first();
                        $rj_01_practitioner = $rj_01_practitioner ?? new RJ_01_Practitioner();
                        $rj_01_practitioner->NIK_pegawai = $item->NIK;
                        $rj_01_practitioner->NIK_KTP = $item->NAMA_KRT;
                        $rj_01_practitioner->nama = $item->NM_PERSON;
                        $rj_01_practitioner->tanggal_lahir = \Carbon\Carbon::parse($item->TGL_LHR_PERSON)->format('Y-m-d');
                        $rj_01_practitioner->jenis_kelamin = $item->KLN_PERSONEL === 'L' ? 'male' : ($item->KLN_PERSONEL === 'P' ? 'female' : null);
                        $rj_01_practitioner->satu_sehat_id = "NIK TIDAK DITEMUKAN";
                        $rj_01_practitioner->response = NULL;
                        $rj_01_practitioner->kode_dokter = NULL;
                        $rj_01_practitioner->save();
                    }
                }
            }
            return redirect()->back()->with('success', 'Data berhasil disimpan.');
        }

        /**
         * Store a new practitioner (AJAX or normal request)
         */
        public function practitioner_store(Request $request)
        {
            $validated = $request->validate([
            'NIK_KTP' => 'nullable|string',
            'nama' => 'required|string',
            'kode_dokter' => 'nullable|string',
            ]);

            $pr = new RJ_01_Practitioner();
            $pr->NIK_pegawai = $request->NIK_pegawai;
            $pr->NIK_KTP = $validated['NIK_KTP'] ?? null;
            $pr->nama = $validated['nama'];
            $pr->kode_dokter = $validated['kode_dokter'] ?? null;
            $pr->save();

            if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Data practitioner berhasil dibuat', 'data' => $pr], 200);
            }

            return redirect()->back()->with('success', 'Data practitioner berhasil dibuat');
        }

        /**
         * Update existing practitioner
         */
        public function practitioner_update(Request $request, $id)
        {
            $validated = $request->validate([
                'NIK_KTP' => 'nullable|string',
                'nama' => 'required|string',
                'kode_dokter' => 'nullable|string',
            ]);

            $pr = RJ_01_Practitioner::find($id);
            if (!$pr) {
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
                }
                return redirect()->back()->with('error', 'Data tidak ditemukan');
            }

            $pr->NIK_pegawai = $request->NIK_pegawai;
            $pr->NIK_KTP = $validated['NIK_KTP'] ?? null;
            $pr->nama = $validated['nama'];
            $pr->kode_dokter = $validated['kode_dokter'] ?? null;
            $pr->save();

            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Data practitioner berhasil diperbarui', 'data' => $pr], 200);
            }

            return redirect()->back()->with('success', 'Data practitioner berhasil diperbarui');
        }

        /**5r
         * Destroy practitioner
         */
        public function practitioner_destroy(Request $request, $id)
        {
            $pr = RJ_01_Practitioner::find($id);
            if (!$pr) {
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
                }
                return redirect()->back()->with('error', 'Data tidak ditemukan');
            }

            $pr->delete();

            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Data practitioner berhasil dihapus'], 200);
            }

            return redirect()->back()->with('success', 'Data practitioner berhasil dihapus');
        }

        public function practitioner_ambil_id_satu_sehat(Request $request, $id)
        {
            $pr = RJ_01_Practitioner::find($id);

            $data_nik = $this->rawatJalan->karyawan_nik($pr->NIK_KTP);
            $nama = $pr->nama;
            $tanggal_lahir = \Carbon\Carbon::parse($pr->tanggal_lahir)->format('Y-m-d');
            $jenis_kelamin = $pr->jenis_kelamin;
            $data_tgl_lahir = $this->rawatJalan->karyawan_tgl_lahir($nama, $tanggal_lahir, $jenis_kelamin);
            if (isset($data_nik->entry[0]->resource->id)) {
                $pr = RJ_01_Practitioner::find($id);
                $pr->satu_sehat_id = $data_nik->entry[0]->resource->id;
                $pr->response = json_encode($data_nik);
                $pr->save();
                return redirect()->back()->with('success', 'Data practitioner berhasil diambil');
            } elseif (isset($data_tgl_lahir->entry[0]->resource->id)) {
                $pr = RJ_01_Practitioner::find($id);
                $pr->satu_sehat_id = $data_tgl_lahir->entry[0]->resource->id;
                $pr->response = json_encode($data_tgl_lahir);
                $pr->save();
                return redirect()->back()->with('success', 'Data practitioner berhasil diambil');
            } else {
                return redirect()->back()->with('error', 'Data practitioner gagal diambil');
            }
        }
    // ========End 01 Mencari Data Pasien dan Nakes========

    // ===========02. Pendaftaran Kunjungan Rawat Jalan================
        public function pendaftaran_kunjungan_rawat_jalan_menu(){
            return view('rawat-jalan.02-pendaftaran-kunjungan-rawat-jalan.menu');
        }

        public function pembuatan_kunjungan_baru(Request $request){
            if ($request->status == "error") {
                $data = RJ_02_A_Kunjungan_Baru_Log::orderBy('id', 'desc')->paginate(25);
            }else {
                $data = RJ_02_A_Kunjungan_Baru::orderBy('id', 'desc')->paginate(25);
            }
            return view('rawat-jalan.02-pendaftaran-kunjungan-rawat-jalan.pembuatan-kunjungan-baru.index', compact('data'));
        }

        public function pembuatan_kunjungan_baru_api(Request $request, $noreg_terakhir = null){
            set_time_limit((int) 0);
            if ($noreg_terakhir == null) {
                $mapping_kunjungan_poli = RJ_02_A_Kunjungan_Baru::orderBy('rekam_id', 'desc')->pluck('rekam_id')->first();
                $log_encounter = RJ_02_A_Kunjungan_Baru_Log::orderBy('rekam_id', 'desc')->pluck('rekam_id')->first();
                if ($mapping_kunjungan_poli > $log_encounter) {
                    $registrasi_pasien_terakhir = $mapping_kunjungan_poli;
                }elseif ($mapping_kunjungan_poli < $log_encounter) {
                    $registrasi_pasien_terakhir = $log_encounter;
                }elseif ($mapping_kunjungan_poli == $log_encounter) {
                    $registrasi_pasien_terakhir = $mapping_kunjungan_poli;
                }

                $registrasi_pasien_terakhir = $registrasi_pasien_terakhir+1;
                // $registrasi_pasien_terakhir = 23;
                $registrasi_pasien = Rekam::where('id', $registrasi_pasien_terakhir)->first();
                if ($registrasi_pasien == null) {
                    $log_encounter = new RJ_02_A_Kunjungan_Baru_Log();
                    $log_encounter->rekam_id = $registrasi_pasien_terakhir;
                    $log_encounter->ket_log = "Data Rekam Medis Tidak ditemukan";
                    $log_encounter->save();
                    return response()->json([
                        'rekam_id' => $registrasi_pasien_terakhir,
                        'message' => "Data Rekam Medis Tidak ditemukan",
                        'nama schedule' => 'pendaftaran pendataan pasien'
                    ], 200);
                }

                $tanggal_sekarang = Carbon::now()->setTimezone('Asia/Jakarta')->format('Y-m-d');
                if ($registrasi_pasien->tgl_rekam == $tanggal_sekarang) {
                    return response()->json([
                        'noreg' => $registrasi_pasien_terakhir,
                        'message' => "Noreg Dalam pelayanan",
                        'nama schedule' => 'pendaftaran pendataan pasien'
                    ], 200);
                }

                // ============================== cek format NIK ==============================
                    $data_pasien_search_nik = $this->patient->search_nik($registrasi_pasien->pasien->no_ktp);

                    $jk = ($registrasi_pasien->pasien->jk == 'Laki-Laki') ? 'male' : 'female';
                    $data_pasien_search_name = $this->patient->search_name_gender_birthdate($registrasi_pasien->pasien->nama, $jk, $registrasi_pasien->pasien->tgl_lahir);
                    if (is_object($data_pasien_search_nik) && property_exists($data_pasien_search_nik, 'total') && $data_pasien_search_nik->total != 0) {
                        $cek_RJ_01_Patient = RJ_01_Patient::where('norm', $registrasi_pasien->pasien->no_rm)->first();
                        if ($cek_RJ_01_Patient == null) {
                            $RJ_01_Patient = new RJ_01_Patient();
                            $RJ_01_Patient->norm = $registrasi_pasien->pasien->no_rm;
                            $RJ_01_Patient->satu_sehat_nama = $data_pasien_search_nik->entry[0]->resource->name[0]->text;
                            $RJ_01_Patient->satu_sehat_id = $data_pasien_search_nik->entry[0]->resource->id;
                            $RJ_01_Patient->response = json_encode($data_pasien_search_nik);
                            $RJ_01_Patient->save();
                        }
                        $RJ_01_Patient = RJ_01_Patient::where('norm', $registrasi_pasien->pasien->no_rm)->first();
                    }elseif (is_object($data_pasien_search_name) && property_exists($data_pasien_search_name, 'total') && $data_pasien_search_name->total != 0) {
                        $cek_RJ_01_Patient = RJ_01_Patient::where('norm', $registrasi_pasien->pasien->no_rm)->first();
                        if ($cek_RJ_01_Patient == null) {
                            $RJ_01_Patient = new RJ_01_Patient();
                            $RJ_01_Patient->norm = $registrasi_pasien->pasien->no_rm;
                            $RJ_01_Patient->satu_sehat_nama = $data_pasien_search_name->entry[0]->resource->name[0]->text;
                            $RJ_01_Patient->satu_sehat_id = $data_pasien_search_name->entry[0]->resource->id;
                            $RJ_01_Patient->response = json_encode($data_pasien_search_name);
                            $RJ_01_Patient->save();
                        }
                        $RJ_01_Patient = RJ_01_Patient::where('norm', $registrasi_pasien->pasien->no_rm)->first();
                    }else {
                        $log_encounter = new RJ_02_A_Kunjungan_Baru_Log();
                        $log_encounter->rekam_id = $registrasi_pasien_terakhir;
                        $log_encounter->ket_log = 'NIK '.$registrasi_pasien->pasien->nama.' Tidak Ditemukan';
                        $log_encounter->save();
                        return response()->json([
                            'noreg' => $registrasi_pasien_terakhir,
                            'message' => 'NIK '.$registrasi_pasien->pasien->nama.' Tidak Ditemukan',
                            'nama schedule' => 'pendaftaran pendataan pasien'
                        ], 200);
                    }
                // ============================== End cek format NIK ==============================
            }else {
                $registrasi_pasien_terakhir = (Integer)$noreg_terakhir;
                $registrasi_pasien = RegistrasiPasien::where('id', $registrasi_pasien_terakhir)->first();
                if ($registrasi_pasien == null) {
                    $log_encounter = new RJ_02_A_Kunjungan_Baru_Log();
                    $log_encounter->rekam_id = $registrasi_pasien_terakhir;
                    $log_encounter->ket_log = "Data Rekam Medis Tidak ditemukan";
                    $log_encounter->save();
                    return response()->json([
                        'rekam_id' => $registrasi_pasien_terakhir,
                        'message' => "Data Rekam Medis Tidak ditemukan",
                        'nama schedule' => 'pendaftaran pendataan pasien'
                    ], 200);
                }
            }

            // ===================================Encounter==============================================
                $tanggal = Carbon::parse($registrasi_pasien->created_at)->format('Y-m-d');
                $jam = Carbon::parse($registrasi_pasien->created_at)->format('H:i:s');

                $id_patient         = $RJ_01_Patient->satu_sehat_id;
                $name_patient       = $registrasi_pasien->pasien->nama;
                $id_practitioner    = "10016656663";
                $name_practitioner  = "TUTIK NUR FAIZAH";
                $date               = date('Y-m-d', strtotime($tanggal)).'T'.$jam.'.000+07:00';

                $encounter = $this->rawatJalan->kunjungan_baru($id_patient, $name_patient, $id_practitioner, $name_practitioner, $date);
                if (isset($encounter->id)) {
                    $mapping_kunjungan_poli = new RJ_02_A_Kunjungan_Baru();
                    $mapping_kunjungan_poli->rekam_id = $registrasi_pasien_terakhir;
                    $mapping_kunjungan_poli->encounter = $encounter->id;
                    $mapping_kunjungan_poli->save();

                    // ===========================Masuk ke Ruang Pemeriksaan===========================
                        $encounter      = $encounter->id;
                        // $encounter      = "ec031d36-4a51-4d2d-b8b8-ccc2f0271e20";
                        $datetime       = $date;
                        $datetime_end   = date('Y-m-d', strtotime($tanggal)).'T'.date('H:i:s', strtotime($jam . ' +15 minutes')).'.000+07:00';
                        $id_location    = "2b21293b-3cab-4c46-b7a0-9289e2526f2c";
                        $name_location  = "klinik dokter umum";
                        $this->masuk_ke_ruang_pemeriksaan_api($registrasi_pasien_terakhir, $encounter, $id_patient, $name_patient, $id_practitioner, $name_practitioner, $datetime, $datetime_end, $id_location, $name_location);
                    // ===========================Masuk ke Ruang Pemeriksaan===========================

                    // ===========04. Hasil Pemeriksaan Fisik========
                        // ===========Pemeriksaan Tanda Tanda Vital========
                                $pemeriksaan_text = $registrasi_pasien->pemeriksaan;

                                // Initialize variables
                                $sistole = null;
                                $diastole = null;
                                $suhu_tubuh = null;
                                $denyut_jantung = null;

                                // Extract TD (Tekanan Darah) - systole and diastole
                                if (preg_match('/TD\s+(\d+)\/(\d+)/i', $pemeriksaan_text, $matches)) {
                                    $sistole = $matches[1];
                                    $diastole = $matches[2];
                                }

                                // Extract TD with colon format (TD : systole/diastole)
                                if (preg_match('/TD\s*:\s*(\d+)\/(\d+)/i', $pemeriksaan_text, $matches)) {
                                    $sistole = $matches[1];
                                    $diastole = $matches[2];
                                }

                                // Extract S (Suhu) - temperature
                                if (preg_match('/S\s*:?\s*([\d,\.]+)/i', $pemeriksaan_text, $matches)) {
                                    $suhu_tubuh = str_replace(',', '.', $matches[1]);
                                }

                                if (preg_match('/S\s+([\d,\.]+)/i', $pemeriksaan_text, $matches)) {
                                    $suhu_tubuh = str_replace(',', '.', $matches[1]);
                                }

                                // Extract N (Nadi) - heart rate
                                if (preg_match('/N\s*:?\s*(\d+)/i', $pemeriksaan_text, $matches)) {
                                    $denyut_jantung = $matches[1];
                                }

                                if (preg_match('/N\s+(\d+)/i', $pemeriksaan_text, $matches)) {
                                    $denyut_jantung = $matches[1];
                                }

                                $this->pemeriksaan_tanda_tanda_vital_api($registrasi_pasien_terakhir, $id_patient, $id_practitioner, $encounter, $date, $sistole, $diastole, $suhu_tubuh, $denyut_jantung);
                        // ===========Pemeriksaan Tanda Tanda Vital========
                    // ===========04. Hasil Pemeriksaan Fisik========

                    // ===========06. Riwayat Perjalanan Penyakit========
                        $data_riwayat = str_replace(["\r\n", "\r", "\n"], ', ', $registrasi_pasien->keluhan);
                        $this->riwayat_perjalanan_penyakit_api($registrasi_pasien_terakhir, $id_patient, $name_patient, $id_practitioner, $encounter, $date, $data_riwayat);
                    // ===========06. Riwayat Perjalanan Penyakit========

                    // ===========10. Pemeriksaan Penunjang=======================
                        // =========================Laboratorium=======================
                            $rekam_laborat = RekamLaborat::where('rekam_id', $registrasi_pasien_terakhir)->get();
                            if ($rekam_laborat->count() != 0) {
                                foreach ($rekam_laborat as $item) {
                                    $kode_loinc = $item->master_laboratorium->loinc_req;
                                    $nama_loinc = $item->master_laboratorium->loinc_req;
                                    $deskripsi_loinc = $item->master_laboratorium->loinc_req;
                                    $kode_pemeriksaan = $item->master_laboratorium->kode;
                                    $kode_snomed = $item->master_laboratorium->spec_snomed_code;
                                    $nama_snomed = $item->master_laboratorium->spec_snomed_name;
                                    $value = preg_replace('/[^0-9,.]/', '', $item->hasil);
                                    $value = str_replace(',', '.', $value);
                                    $satuan = $item->master_laboratorium->satuan;
                                    $this->laboratory_api($registrasi_pasien_terakhir, $id_patient, $name_patient, $encounter, $id_practitioner, $name_practitioner, $datetime, $datetime_end, $kode_pemeriksaan, $kode_loinc, $nama_loinc, $deskripsi_loinc, $kode_snomed, $nama_snomed, $value, $satuan);
                                    break;
                                }
                            }
                        // =========================End Laboratorium===================
                    // ===========End 10. Pemeriksaan Penunjang===================

                    // ===========12. Diagnosis========
                        $rekam_diagnosa = RekamDiagnosa::where('rekam_id', $registrasi_pasien_terakhir)->first();
                        if ($rekam_diagnosa != null && $rekam_diagnosa->diagnosis && $rekam_diagnosa->diagnosis->name_id) {
                            $kode_diagnosa = $rekam_diagnosa->diagnosa;
                            $deskripsi_diagnosa = $rekam_diagnosa->diagnosis->name_id;
                            $this->diagnosis_primary_api($registrasi_pasien_terakhir, $kode_diagnosa, $deskripsi_diagnosa, $id_patient, $name_patient, $encounter);
                        }
                    // ===========12. Diagnosis========

                    // ===========14. Tindakan Konseling========
                        if ($rekam_diagnosa != null && $rekam_diagnosa->diagnosis && $rekam_diagnosa->diagnosis->name_id) {
                            $kode_diagnosa = $rekam_diagnosa->diagnosa;
                            $deskripsi_diagnosa = $rekam_diagnosa->diagnosis->name_id;
                            $this->konseling_api($registrasi_pasien_terakhir, $encounter, $kode_diagnosa, $deskripsi_diagnosa, $id_patient, $name_patient, $id_practitioner, $name_practitioner, $date);
                        }
                    // ===========End 14. Tindakan Konseling====


                    // =========================15. Tata laksana===================
                        $pengeluaran_obat = PengeluaranObat::where('rekam_id', $registrasi_pasien_terakhir)->get();
                        if ($pengeluaran_obat->count() != 0) {
                            foreach ($pengeluaran_obat as $item) {
                                $RJ_15_Medication_Obat = RJ_15_Medication_Obat::where('kode_barang_mabar', $item->obat->kd_obat)->first();
                                if (!$RJ_15_Medication_Obat) {
                                    continue;
                                }
                                $id_obat_satusehat = $RJ_15_Medication_Obat->kode_satu_sehat;
                                $display_obat_satusehat = $RJ_15_Medication_Obat->keterangan_kfa;
                                $dosis_obat = $item->keterangan;
                                $start_waktu_pemberian_obat = $date;
                                $end_waktu_pemberian_obat = date('Y-m-d', strtotime($tanggal . ' +3 days')).'T'.$jam.'.000+07:00';
                                $jumlah_obat = $item->jumlah;
                                $durasi_penggunaan = '3';
                                if (!$item->obat || !$item->obat->kd_obat) {
                                    continue;
                                }
                                $kode_obat = $item->obat->kd_obat;
                                $kode_obat_kfa = $item->obat->kode_kfa;
                                $deskripsi_obat_kfa = $item->obat->keterangan_kfa;
                                $nomer_resep = $item->id;
                                $this->peresepan_obat_api($registrasi_pasien_terakhir, $id_obat_satusehat, $display_obat_satusehat, $id_patient, $name_patient, $encounter, $date, $id_practitioner, $name_practitioner, $dosis_obat, $start_waktu_pemberian_obat, $end_waktu_pemberian_obat, $jumlah_obat, $durasi_penggunaan, $kode_obat, $kode_obat_kfa, $deskripsi_obat_kfa, $nomer_resep);
                            }
                        }
                    // =========================End 15. Tata laksana===============
                }else {
                    $log_encounter = new RJ_02_A_Kunjungan_Baru_Log();
                    $log_encounter->rekam_id = $registrasi_pasien_terakhir;
                    $log_encounter->ket_log = json_encode($encounter);
                    $log_encounter->save();
                }
            // ===================================End Encounter==============================================
            return response()->json([
                'noreg' => $registrasi_pasien_terakhir,
                'message' => 'Data Berhasil Disimpan',
                'nama schedule' => 'pendaftaran pendataan pasien'
            ], 200);
        }

        public function masuk_ke_ruang_pemeriksaan(Request $request){
            if ($request->status == "error") {
                $data = RJ_02_B_Masuk_Ruang_Log::orderBy('id', 'desc')->paginate(25);
            }else {
                $data = RJ_02_B_Masuk_Ruang::orderBy('id', 'desc')->paginate(25);
            }
            return view('rawat-jalan.02-pendaftaran-kunjungan-rawat-jalan.pembuatan-kunjungan-baru.index', compact('data'));
        }

        public function masuk_ke_ruang_pemeriksaan_api($rekam_id, $encounter_id, $id_patient, $name_patient, $id_practitioner, $name_practitioner, $datetime, $datetime_end, $id_location, $name_location){
            set_time_limit((int) 0);
            $data = $this->rawatJalan->masuk_ruang($encounter_id, $id_patient, $name_patient, $id_practitioner, $name_practitioner, $datetime, $datetime_end, $id_location, $name_location);
            if (isset($data->id)) {
                $rj_masuk_ruang_model = new RJ_02_B_Masuk_Ruang();
                $rj_masuk_ruang_model->encounter = $encounter_id;
                $rj_masuk_ruang_model->rekam_id = $rekam_id;
                $rj_masuk_ruang_model->id_satu_sehat = $data->id;
                $rj_masuk_ruang_model->save();

                return response()->json([
                    'rekam_id' => $rekam_id,
                    'message' => 'Data Masuk ke ruang pemeriksaan sukses dikirim',
                    'nama schedule' => 'Encounter Masuk Ruang'
                ], 200);
            }else {
                $log_rj_masuk_ruang = new RJ_02_B_Masuk_Ruang_Log();
                $log_rj_masuk_ruang->rekam_id = $rekam_id;
                $log_rj_masuk_ruang->ket_log = json_encode($data);
                $log_rj_masuk_ruang->save();
                return response()->json([
                    'rekam_id' => $rekam_id,
                    'message' => "Data Masuk ke ruang pemeriksaan gagal dikirim",
                    'nama schedule' => 'Encounter Masuk Ruang'
                ], 200);
            }
        }
    // ===========End 02. Pendaftaran Kunjungan Rawat Jalan============

    // ===========04. Hasil Pemeriksaan Fisik================
        public function hasil_pemeriksaan_fisik_menu(){
            return view('rawat-jalan.04_hasil_pemeriksaan_fisik.menu');
        }

        public function pemeriksaan_tanda_tanda_vital(Request $request){
            if ($request->status == "error") {
                $data = RJ_04_Pemeriksaan_Tanda_Tanda_Vital_Log::orderBy('id', 'desc')->paginate(25);
            }else {
                $data = RJ_04_Pemeriksaan_Tanda_Tanda_Vital::orderBy('id', 'desc')->paginate(25);
            }
            return view('rawat-jalan.04_hasil_pemeriksaan_fisik.pemeriksaan_tanda_tanda_vital.index', compact('data'));
        }

        public function pemeriksaan_tanda_tanda_vital_api($registrasi_pasien_terakhir, $id_patient, $id_practitioner, $encounter, $date, $sistole, $diastole, $suhu_tubuh, $denyut_jantung){
            $RJ_04_Pemeriksaan_Tanda_Tanda_Vital = RJ_04_Pemeriksaan_Tanda_Tanda_Vital::where('rekam_id', $registrasi_pasien_terakhir)
                                                    ->where('encounter', $encounter)
                                                    ->first();
            if ($RJ_04_Pemeriksaan_Tanda_Tanda_Vital == null) {
                $RJ_04_Pemeriksaan_Tanda_Tanda_Vital = new RJ_04_Pemeriksaan_Tanda_Tanda_Vital();
            }

            $RJ_04_Pemeriksaan_Tanda_Tanda_Vital->rekam_id = $registrasi_pasien_terakhir;
            $RJ_04_Pemeriksaan_Tanda_Tanda_Vital->encounter = $encounter;
            if ($sistole != null) {
                $data = $this->rawatJalan->tekanan_darah_sistole($id_patient, $id_practitioner, $encounter, $date, $sistole);
                if (isset($data->id)) {
                    $RJ_04_Pemeriksaan_Tanda_Tanda_Vital->td_sistolik_id = $data->id;
                }else {
                    $RJ_04_Pemeriksaan_Tanda_Tanda_Vital_Log = new RJ_04_Pemeriksaan_Tanda_Tanda_Vital_Log();
                    $RJ_04_Pemeriksaan_Tanda_Tanda_Vital_Log->rekam_id = $registrasi_pasien_terakhir;
                    $RJ_04_Pemeriksaan_Tanda_Tanda_Vital_Log->ket_log = json_encode($data);
                    $RJ_04_Pemeriksaan_Tanda_Tanda_Vital_Log->save();
                    return response()->json([
                        'rekam_id' => $registrasi_pasien_terakhir,
                        'message' => "Data tekanan darah sistole gagal dikirim",
                        'nama schedule' => 'Pemeriksaan Tanda Tanda Vital'
                    ], 200);
                }
            }

            if ($diastole != null) {
                $data = $this->rawatJalan->tekanan_darah_diastole($id_patient, $id_practitioner, $encounter, $date, $diastole);
                if (isset($data->id)) {
                    $RJ_04_Pemeriksaan_Tanda_Tanda_Vital->td_diastolik_id = $data->id;
                }else {
                    $RJ_04_Pemeriksaan_Tanda_Tanda_Vital_Log = new RJ_04_Pemeriksaan_Tanda_Tanda_Vital_Log();
                    $RJ_04_Pemeriksaan_Tanda_Tanda_Vital_Log->rekam_id = $registrasi_pasien_terakhir;
                    $RJ_04_Pemeriksaan_Tanda_Tanda_Vital_Log->ket_log = json_encode($data);
                    $RJ_04_Pemeriksaan_Tanda_Tanda_Vital_Log->save();
                    return response()->json([
                        'rekam_id' => $registrasi_pasien_terakhir,
                        'message' => "Data tekanan darah diastole gagal dikirim",
                        'nama schedule' => 'Pemeriksaan Tanda Tanda Vital'
                    ], 200);
                }
            }

            if ($suhu_tubuh != null) {
                $data = $this->rawatJalan->suhu_tubuh($id_patient, $id_practitioner, $encounter, $date, $suhu_tubuh);
                if (isset($data->id)) {
                    $RJ_04_Pemeriksaan_Tanda_Tanda_Vital->suhu_tubuh_id = $data->id;
                }else {
                    $RJ_04_Pemeriksaan_Tanda_Tanda_Vital_Log = new RJ_04_Pemeriksaan_Tanda_Tanda_Vital_Log();
                    $RJ_04_Pemeriksaan_Tanda_Tanda_Vital_Log->rekam_id = $registrasi_pasien_terakhir;
                    $RJ_04_Pemeriksaan_Tanda_Tanda_Vital_Log->ket_log = json_encode($data);
                    $RJ_04_Pemeriksaan_Tanda_Tanda_Vital_Log->save();
                    return response()->json([
                        'rekam_id' => $registrasi_pasien_terakhir,
                        'message' => "Data suhu tubuh gagal dikirim",
                        'nama schedule' => 'Pemeriksaan Tanda Tanda Vital'
                    ], 200);
                }
            }

            if ($denyut_jantung != null) {
                $data = $this->rawatJalan->denyut_jantung($id_patient, $id_practitioner, $encounter, $date, $denyut_jantung);
                if (isset($data->id)) {
                    $RJ_04_Pemeriksaan_Tanda_Tanda_Vital->denyut_jantung_id = $data->id;
                }else {
                    $RJ_04_Pemeriksaan_Tanda_Tanda_Vital_Log = new RJ_04_Pemeriksaan_Tanda_Tanda_Vital_Log();
                    $RJ_04_Pemeriksaan_Tanda_Tanda_Vital_Log->rekam_id = $registrasi_pasien_terakhir;
                    $RJ_04_Pemeriksaan_Tanda_Tanda_Vital_Log->ket_log = json_encode($data);
                    $RJ_04_Pemeriksaan_Tanda_Tanda_Vital_Log->save();
                    return response()->json([
                        'rekam_id' => $registrasi_pasien_terakhir,
                        'message' => "Data denyut jantung gagal dikirim",
                        'nama schedule' => 'Pemeriksaan Tanda Tanda Vital'
                    ], 200);
                }
            }

            $RJ_04_Pemeriksaan_Tanda_Tanda_Vital->save();
            return response()->json([
                'rekam_id' => $registrasi_pasien_terakhir,
                'message' => 'Data Pemeriksaan Tanda Tanda Vital sukses dikirim',
                'nama schedule' => 'Pemeriksaan Tanda Tanda Vital'
            ], 200);
        }
    // ===========End 04. Hasil Pemeriksaan Fisik============
    // ===========06. Riwayat Perjalanan Penyakit============
        public function riwayat_perjalanan_penyakit_index(Request $request){
            if ($request->status == "error") {
                $data = RJ_06_Riwayat_Perjalanan_Penyakit_Log::orderBy('id', 'desc')->paginate(25);
            }else {
                $data = RJ_06_Riwayat_Perjalanan_Penyakit::orderBy('id', 'desc')->paginate(25);
            }
            return view('rawat-jalan.06-riwayat-perjalanan-penyakit.index', compact('data'));
        }
        public function riwayat_perjalanan_penyakit_api($rekam_id, $id_patient, $name_patient, $id_practitioner, $encounter_id, $date, $data_riwayat){
            set_time_limit((int) 0);
            $data = $this->rawatJalan->riwayat_perjalanan_penyakit($id_patient, $name_patient, $id_practitioner, $encounter_id, $date, $data_riwayat);
            if (isset($data->id)) {
                $rj_masuk_ruang_model = new RJ_06_Riwayat_Perjalanan_Penyakit();
                $rj_masuk_ruang_model->encounter = $encounter_id;
                $rj_masuk_ruang_model->rekam_id = $rekam_id;
                $rj_masuk_ruang_model->satu_sehat_id = $data->id;
                $rj_masuk_ruang_model->save();

                return response()->json([
                    'rekam_id' => $rekam_id,
                    'message' => 'Data Riwayat Perjalanan Penyakit sukses dikirim',
                    'nama schedule' => 'Riwayat Perjalanan Penyakit'
                ], 200);
            }else {
                $RJ_06_Riwayat_Perjalanan_Penyakit_Log = new RJ_06_Riwayat_Perjalanan_Penyakit_Log();
                $RJ_06_Riwayat_Perjalanan_Penyakit_Log->rekam_id = $rekam_id;
                $RJ_06_Riwayat_Perjalanan_Penyakit_Log->ket_log = json_encode($data);
                $RJ_06_Riwayat_Perjalanan_Penyakit_Log->save();
                return response()->json([
                    'rekam_id' => $rekam_id,
                    'message' => "Data Riwayat Perjalanan Penyakit gagal dikirim",
                    'nama schedule' => 'Riwayat Perjalanan Penyakit'
                ], 200);
            }
        }
    // ===========End 06. Riwayat Perjalanan Penyakit========

    // =========================10. Pemeriksaan Penunjang=======================
        public function pemeriksaan_penunjang_menu(){
            return view('rawat-jalan.10-pemeriksaan-penunjang.menu');
        }
        // =========================Laboratorium=======================
            public function laboratorium_index(Request $request){
                if ($request->status == "error") {
                    $data = RJ_10_laboratory_Log::orderBy('id', 'desc')->paginate(25);
                }else {
                    $data = RJ_10_laboratory::orderBy('id', 'desc')->paginate(25);
                }
                return view('rawat-jalan.10-pemeriksaan-penunjang.laboratorium.index', compact('data'));
            }

            public function laboratory_api($registrasi_pasien_terakhir, $id_patient, $name_patient, $encounter, $id_practitioner, $name_practitioner, $datetime, $datetime_end, $kode_pemeriksaan, $kode_loinc, $nama_loinc, $deskripsi_loinc, $kode_snomed, $nama_snomed, $value, $satuan){
                set_time_limit((int) 0);
                $Cek_RJ_10_laboratory = RJ_10_laboratory::where('encounter', $encounter)
                                        ->where('rekam_id', $registrasi_pasien_terakhir)
                                        ->where('kode_pemeriksaan', $kode_pemeriksaan)
                                        ->first();

                if ($Cek_RJ_10_laboratory == null) {
                    $RJ_10_laboratory_new = new RJ_10_laboratory();
                    $RJ_10_laboratory_new->encounter = $encounter;
                    $RJ_10_laboratory_new->rekam_id = $registrasi_pasien_terakhir;
                    $RJ_10_laboratory_new->kode_pemeriksaan = $kode_pemeriksaan;
                    $RJ_10_laboratory_new->save();
                }

                $RJ_10_laboratory_update = RJ_10_laboratory::where('encounter', $encounter)
                                        ->where('rekam_id', $registrasi_pasien_terakhir)
                                        ->where('kode_pemeriksaan', $kode_pemeriksaan)
                                        ->first();

                if ($RJ_10_laboratory_update->procedure_id == null) {
                    $attempts = 0;
                    $max_attempts = 5;
                    $procedure_data = null;
                    while ($attempts < $max_attempts) {
                        $procedure_data = $this->rawatJalan->procedure_status_puasa_laboratorium_nominal($id_patient, $name_patient, $encounter, $id_practitioner, $name_practitioner, $datetime, $datetime_end);
                        if (isset($procedure_data->id)) {
                            $RJ_10_laboratory_update->procedure_id = $procedure_data->id;
                            $RJ_10_laboratory_update->save();
                            break; // Exit loop if successful
                        }else {
                            $attempts++;
                            if ($attempts >= $max_attempts) {
                                $RJ_10_laboratory_Log = new RJ_10_laboratory_Log();
                                $RJ_10_laboratory_Log->rekam_id = $registrasi_pasien_terakhir;
                                $RJ_10_laboratory_Log->ket_log = json_encode($procedure_data);
                                $RJ_10_laboratory_Log->save();
                                return response()->json([
                                    'rekam_id' => $registrasi_pasien_terakhir,
                                    'message' => "prosedur laboratorium gagal setelah beberapa kali percobaan",
                                    'nama schedule' => 'Laboratorium'
                                ], 200);
                            }
                            sleep(1); // Wait for 1 second before retrying
                        }
                    }
                }

                $RJ_10_laboratory_update = RJ_10_laboratory::where('encounter', $encounter)
                                        ->where('rekam_id', $registrasi_pasien_terakhir)
                                        ->where('kode_pemeriksaan', $kode_pemeriksaan)
                                        ->first();

                if ($RJ_10_laboratory_update->service_request_id == null) {
                    $attempts = 0;
                    $max_attempts = 5;
                    $service_request_data = null;
                    while ($attempts < $max_attempts) {
                        try {
                            $Procedure_Id       = $procedure_data->id;
                        } catch (\Throwable $th) {
                            $Procedure_Id       = $RJ_10_laboratory_update->procedure_id;
                        }
                        $service_request_data = $this->rawatJalan->service_request_laboratorium_nominal($registrasi_pasien_terakhir, $id_patient, $name_patient, $encounter, $id_practitioner, $name_practitioner, $datetime, $datetime_end, $kode_loinc, $nama_loinc, $deskripsi_loinc, $Procedure_Id);
                        if (isset($service_request_data->id)) {
                            $RJ_10_laboratory_update->service_request_id = $service_request_data->id;
                            $RJ_10_laboratory_update->save();
                            break; // Exit loop if successful
                        }else {
                            $attempts++;
                            if ($attempts >= $max_attempts) {
                                $RJ_10_laboratory_Log = new RJ_10_laboratory_Log();
                                $RJ_10_laboratory_Log->rekam_id = $registrasi_pasien_terakhir;
                                $RJ_10_laboratory_Log->ket_log = json_encode($service_request_data);
                                $RJ_10_laboratory_Log->save();
                                return response()->json([
                                    'rekam_id' => $registrasi_pasien_terakhir,
                                    'message' => "service request laboratorium gagal setelah beberapa kali percobaan",
                                    'nama schedule' => 'Laboratorium'
                                ], 200);
                            }
                            sleep(1); // Wait for 1 second before retrying
                        }
                    }
                }

                $RJ_10_laboratory_update = RJ_10_laboratory::where('encounter', $encounter)
                                        ->where('rekam_id', $registrasi_pasien_terakhir)
                                        ->where('kode_pemeriksaan', $kode_pemeriksaan)
                                        ->first();

                if ($RJ_10_laboratory_update->specimen_id == null) {
                    $attempts = 0;
                    $max_attempts = 5;
                    $specimen_data = null;
                    while ($attempts < $max_attempts) {
                        try {
                            $service_request_id     = $service_request_data->id;
                        } catch (\Throwable $th) {
                            $service_request_id     = $RJ_10_laboratory_update->service_request_id;
                        }
                        $specimen_data = $this->rawatJalan->specimen_laboratorium_nominal($registrasi_pasien_terakhir, $id_patient, $name_patient, $id_practitioner, $name_practitioner, $datetime, $kode_snomed, $nama_snomed, $value, $satuan, $service_request_id);
                        if (isset($specimen_data->id)) {
                            $RJ_10_laboratory_update->specimen_id = $specimen_data->id;
                            $RJ_10_laboratory_update->save();
                            break; // Exit loop if successful
                        }else {
                            $attempts++;
                            if ($attempts >= $max_attempts) {
                                $RJ_10_laboratory_Log = new RJ_10_laboratory_Log();
                                $RJ_10_laboratory_Log->rekam_id = $registrasi_pasien_terakhir;
                                $RJ_10_laboratory_Log->ket_log = json_encode($specimen_data);
                                $RJ_10_laboratory_Log->save();
                                return response()->json([
                                    'rekam_id' => $registrasi_pasien_terakhir,
                                    'message' => "specimen laboratorium gagal setelah beberapa kali percobaan",
                                    'nama schedule' => 'Laboratorium'
                                ], 200);
                            }
                            sleep(1); // Wait for 1 second before retrying
                        }
                    }
                }

                $RJ_10_laboratory_update = RJ_10_laboratory::where('encounter', $encounter)
                                        ->where('rekam_id', $registrasi_pasien_terakhir)
                                        ->where('kode_pemeriksaan', $kode_pemeriksaan)
                                        ->first();

                if ($RJ_10_laboratory_update->observation_id == null) {
                    $attempts = 0;
                    $max_attempts = 5;
                    $observation_data = null;
                    while ($attempts < $max_attempts) {
                        try {
                            $Specimen_Id           = $specimen_data->id;
                            $ServiceRequest_Id     = $service_request_data->id;
                        } catch (\Throwable $th) {
                            $Specimen_Id           = $RJ_10_laboratory_update->specimen_id;
                            $ServiceRequest_Id     = $RJ_10_laboratory_update->service_request_id;
                        }
                        $observation_data = $this->rawatJalan->observation_laboratorium_nominal($registrasi_pasien_terakhir, $id_patient, $encounter, $id_practitioner, $Specimen_Id, $ServiceRequest_Id, $kode_loinc, $nama_loinc, $datetime, $datetime_end);
                        if (isset($observation_data->id)) {
                            $RJ_10_laboratory_update->observation_id = $observation_data->id;
                            $RJ_10_laboratory_update->save();
                            break; // Exit loop if successful
                        }else {
                            $attempts++;
                            if ($attempts >= $max_attempts) {
                                $RJ_10_laboratory_Log = new RJ_10_laboratory_Log();
                                $RJ_10_laboratory_Log->rekam_id = $registrasi_pasien_terakhir;
                                $RJ_10_laboratory_Log->ket_log = json_encode($observation_data);
                                $RJ_10_laboratory_Log->save();
                                return response()->json([
                                    'rekam_id' => $registrasi_pasien_terakhir,
                                    'message' => "observation laboratorium gagal setelah beberapa kali percobaan",
                                    'nama schedule' => 'Laboratorium'
                                ], 200);
                            }
                            sleep(1); // Wait for 1 second before retrying
                        }
                    }
                }

                $RJ_10_laboratory_update = RJ_10_laboratory::where('encounter', $encounter)
                                        ->where('rekam_id', $registrasi_pasien_terakhir)
                                        ->where('kode_pemeriksaan', $kode_pemeriksaan)
                                        ->first();

                if ($RJ_10_laboratory_update->diagnostic_report_id == null) {
                    $attempts = 0;
                    $max_attempts = 5;
                    $diagnostic_report_data = null;
                    while ($attempts < $max_attempts) {
                        try {
                            $Observation_id        = $observation_data->id;
                            $Specimen_Id           = $specimen_data->id;
                            $ServiceRequest_id     = $service_request_data->id;
                        } catch (\Throwable $th) {
                            $Observation_id        = $RJ_10_laboratory_update->observation_id;
                            $Specimen_Id           = $RJ_10_laboratory_update->specimen_id;
                            $ServiceRequest_id     = $RJ_10_laboratory_update->service_request_id;
                        }
                        $diagnostic_report_data = $this->rawatJalan->diagnostic_report_laboratorium_nominal($registrasi_pasien_terakhir, $id_patient, $encounter, $id_practitioner, $Observation_id, $Specimen_Id, $ServiceRequest_id, $kode_loinc, $nama_loinc, $datetime, $datetime_end);
                        if (isset($diagnostic_report_data->id)) {
                            $RJ_10_laboratory_update->diagnostic_report_id = $diagnostic_report_data->id;
                            $RJ_10_laboratory_update->save();
                            break; // Exit loop if successful
                        }else {
                            $attempts++;
                            if ($attempts >= $max_attempts) {
                                $RJ_10_laboratory_Log = new RJ_10_laboratory_Log();
                                $RJ_10_laboratory_Log->rekam_id = $registrasi_pasien_terakhir;
                                $RJ_10_laboratory_Log->ket_log = json_encode($diagnostic_report_data);
                                $RJ_10_laboratory_Log->save();
                                return response()->json([
                                    'rekam_id' => $registrasi_pasien_terakhir,
                                    'message' => "diagnostic report laboratorium gagal setelah beberapa kali percobaan",
                                    'nama schedule' => 'Laboratorium'
                                ], 200);
                            }
                            sleep(1); // Wait for 1 second before retrying
                        }
                    }
                }

                return response()->json([
                    'rekam_id' => $registrasi_pasien_terakhir,
                    'message' => 'Semua Data sukses dikirim',
                    'nama schedule' => 'Laboratorium'
                ], 200);
            }
        // =========================end Laboratorium===================
        // =========================Radiologi===================
            public function radiologi_api(Request $request){
                set_time_limit((int) 0);
                // $RJ_10_Radiologi = RJ_10_Radiologi::orderBy('noreg', 'desc')->pluck('noreg')->first();
                // $RJ_10_Radiologi_Log = RJ_10_Radiologi_Log::orderBy('noreg', 'desc')->pluck('noreg')->first();
                // if ($RJ_10_Radiologi == null && $RJ_10_Radiologi_Log == null) {
                //     $noreg_terakhir = MappingKunjunganPoli::orderBy('noreg', 'asc')->pluck('noreg')->first();
                // }elseif ($RJ_10_Radiologi > $RJ_10_Radiologi_Log) {
                //     $noreg_terakhir = $RJ_10_Radiologi;
                // }elseif ($RJ_10_Radiologi < $RJ_10_Radiologi_Log) {
                //     $noreg_terakhir = $RJ_10_Radiologi_Log;
                // }elseif ($RJ_10_Radiologi == $RJ_10_Radiologi_Log) {
                //     $noreg_terakhir = $RJ_10_Radiologi;
                // }

                // $noreg_terakhir = $noreg_terakhir+1;
                // $noreg_tanggal_depan = (substr($noreg_terakhir, 0, -4)+1)."0000";
                // $data_tanggal_terakhir = MappingKunjunganPoli::where('noreg', "<",$noreg_tanggal_depan)->orderBy('noreg', 'desc')->pluck('noreg')->first();
                // if ($noreg_terakhir > $data_tanggal_terakhir) {
                //     $noreg_terakhir = $noreg_tanggal_depan+1;
                // }

                // //berhentikan sebelum noreg hari sekarang
                // $now = Carbon::now()->setTimezone('Asia/Jakarta')->format('ymd');
                // $noreg_batas = (Integer)($now . '0000');
                // if ($noreg_terakhir > $noreg_batas) {
                //     return response()->json([
                //         'noreg' => $noreg_terakhir,
                //         'message' => "Noreg Dalam Pelayanan",
                //         'nama schedule' => 'Radiologi'
                //     ], 200);
                // }

                // if (substr($noreg_terakhir, 2, 4) == '1232') {
                //     $noreg_terakhir += 100000000;
                //     $noreg_terakhir = substr_replace($noreg_terakhir, '0101', 2, 4);
                // }

                // $data_terbesar = MappingKunjunganPoli::orderBy('noreg', 'desc')->pluck('noreg')->first();
                // if ($noreg_terakhir > $data_terbesar) {
                //     return response()->json([
                //         'noreg' => $noreg_terakhir,
                //         'message' => "Noreg Belum Terdaftar",
                //         'nama schedule' => 'Radiologi'
                //     ], 200);
                // }
                $noreg_terakhir = '2511300002';
                $mapping_kunjungan_poli = RJ_02_A_Kunjungan_Baru::where('noreg', $noreg_terakhir)->first();
                if ($mapping_kunjungan_poli == null) {
                    $RJ_10_Radiologi_Log = new RJ_10_Radiologi_Log();
                    $RJ_10_Radiologi_Log->noreg = $noreg_terakhir;
                    $RJ_10_Radiologi_Log->ket_log = 'Mapping kunjungan poli tidak ditemukan';
                    $RJ_10_Radiologi_Log->save();
                    return response()->json([
                        'noreg' => $noreg_terakhir,
                        'message' => "Mapping kunjungan poli tidak ditemukan",
                        'nama schedule' => 'Radiologi'
                    ], 200);
                }
                $registrasi_pasien = RegistrasiPasien::where('NOREG', $noreg_terakhir)->first();
                if ($registrasi_pasien == null) {
                    $RJ_10_Radiologi_Log = new RJ_10_Radiologi_Log();
                    $RJ_10_Radiologi_Log->noreg = $noreg_terakhir;
                    $RJ_10_Radiologi_Log->ket_log = 'Noreg Belum Terdaftar';
                    $RJ_10_Radiologi_Log->save();
                    return response()->json([
                        'noreg' => $noreg_terakhir,
                        'message' => "Noreg Belum Terdaftar",
                        'nama schedule' => 'Radiologi'
                    ], 200);
                }
                $mapping_pasien = MappingPasien::where('norm', $registrasi_pasien->NOPASIEN)->first();
                if ($mapping_pasien == null) {
                    $RJ_10_Radiologi_Log = new RJ_10_Radiologi_Log();
                    $RJ_10_Radiologi_Log->noreg = $noreg_terakhir;
                    $RJ_10_Radiologi_Log->ket_log = "Pasien Tidak Ditemukan Di Mapping Pasien";
                    $RJ_10_Radiologi_Log->save();
                    return response()->json([
                        'noreg' => $noreg_terakhir,
                        'message' => "Pasien Tidak Ditemukan Di Mapping Pasien",
                        'nama schedule' => 'Radiologi'
                    ], 200);
                }

                // $mapping_dokter_spesialis = RJ_01_Practitioner::where('kode_dokter', $registrasi_pasien->Registrasi_Dokter->KODEDOKTER)->first();
                // if ($mapping_dokter_spesialis == null) {
                //     $RJ_10_Radiologi_Log = new RJ_10_Radiologi_Log();
                //     $RJ_10_Radiologi_Log->noreg = $noreg_terakhir;
                //     $RJ_10_Radiologi_Log->ket_log = "Dokter untuk Pasien ".$registrasi_pasien->Pasien->NAMAPASIEN." Tidak Ditemukan Di Mapping Dokter Spesialis";
                //     $RJ_10_Radiologi_Log->save();
                //     return response()->json([
                //         'noreg' => $noreg_terakhir,
                //         'message' => "Dokter untuk Pasien ".$registrasi_pasien->Pasien->NAMAPASIEN." Tidak Ditemukan Di Mapping Dokter Spesialis",
                //         'nama schedule' => 'Radiologi'
                //     ], 200);
                // }
                $TrxHasil1 = TrxHasil1::where('NOREG', (string) $noreg_terakhir)->first();
                if ($TrxHasil1 == null) {
                    $RJ_10_Radiologi_Log = new RJ_10_Radiologi_Log();
                    $RJ_10_Radiologi_Log->noreg = $noreg_terakhir;
                    $RJ_10_Radiologi_Log->ket_log = "Tidak ada data pemeriksaan radiologi pada noreg ini";
                    $RJ_10_Radiologi_Log->save();
                    return response()->json([
                        'noreg' => $noreg_terakhir,
                        'message' => "Tidak ada data pemeriksaan radiologi pada noreg ini",
                        'nama schedule' => 'Radiologi'
                    ], 200);
                }

                $Cek_RJ_10_Radiologi = RJ_10_Radiologi::where('encounter', $mapping_kunjungan_poli->encounter)
                                        ->where('noreg', $noreg_terakhir)
                                        ->first();
                if ($Cek_RJ_10_Radiologi == null) {
                    $RJ_10_Radiologi_new = new RJ_10_Radiologi();
                    $RJ_10_Radiologi_new->encounter = $mapping_kunjungan_poli->encounter;
                    $RJ_10_Radiologi_new->noreg = $noreg_terakhir;
                    $RJ_10_Radiologi_new->save();
                }
                $RJ_10_Radiologi_update = RJ_10_Radiologi::where('encounter', $mapping_kunjungan_poli->encounter)
                                        ->where('noreg', $noreg_terakhir)
                                        ->first();
                if ($RJ_10_Radiologi_update->service_request_id == null) {
                    $attempts = 0;
                    $max_attempts = 5;
                    $service_request_data = null;
                    while ($attempts < $max_attempts) {
                        $Noreg = (string) $noreg_terakhir;
                        $Patient_id = $mapping_pasien->kodesatusehat;
                        $Encounter_id = $mapping_kunjungan_poli->encounter;
                        // $Practitioner_id = $mapping_dokter_spesialis->satusehat;
                        // $Practitioner_Name = $mapping_dokter_spesialis->nama;
                        $Practitioner_id        = '10013576199';
                        $Practitioner_Name      = 'dr.Gusti Reka Kusuma';
                        $Practitioner_id_Radiologi = '10010763982';
                        $Practitioner_Name_Radiologi = 'PUSPITA SARI';
                        $start_date = date('Y-m-d', strtotime($registrasi_pasien->TGLREG)).'T'.date('H:i:s', strtotime($registrasi_pasien->JAMREG)).'+07:00';

                        $service_request_data = $this->rawatJalan->service_request_radiologi($Noreg, $Patient_id, $Encounter_id, $Practitioner_id, $Practitioner_Name, $Practitioner_id_Radiologi, $Practitioner_Name_Radiologi, $start_date);
                        if (isset($service_request_data->id)) {
                            $RJ_10_Radiologi_update->service_request_id = $service_request_data->id;
                            $RJ_10_Radiologi_update->save();
                            break; // Exit loop if successful
                        } else{
                            $attempts++;
                            if ($attempts >= $max_attempts) {
                                $RJ_10_Radiologi_Log = new RJ_10_Radiologi_Log();
                                $RJ_10_Radiologi_Log->noreg = $noreg_terakhir;
                                $RJ_10_Radiologi_Log->ket_log = json_encode($service_request_data);
                                $RJ_10_Radiologi_Log->save();
                                return response()->json([
                                    'noreg' => $noreg_terakhir,
                                    'message' => "Service Request radiologi gagal setelah beberapa kali percobaan",
                                    'nama schedule' => 'Radiologi'
                                ], 200);
                            }
                            sleep(1); // Wait for 1 second before retrying
                        }
                    }
                }

                $RJ_10_Radiologi_update = RJ_10_Radiologi::where('encounter', $mapping_kunjungan_poli->encounter)
                                        ->where('noreg', $noreg_terakhir)
                                        ->first();
                if ($RJ_10_Radiologi_update->imaging_study_id == null) {
                    // =====kirim dicom=========
                        // =====convert jpg to bmp=========
                            $possible_paths = [
                                'C:\Program Files\ImageMagick-7.1.2-Q16\magick.exe',
                                'C:\Program Files (x86)\ImageMagick-7.1.2-Q16\magick.exe',
                                'magick' // fallback ke system PATH
                            ];

                            $magick_path = 'magick'; // default

                            foreach ($possible_paths as $path) {
                                if (file_exists($path)) {
                                    $magick_path = $path;
                                    break;
                                }
                            }

                            $input_jpg = base_path('public/contoh_gambar_jpg/input.jpg');
                            $output_bmp = base_path('public/contoh_gambar_bmp/output.bmp');

                            $perintah_convert_jpg_bmp = "\"$magick_path\" " . escapeshellarg($input_jpg) . " -define bmp:format=bmp3 " . escapeshellarg($output_bmp);

                            // Eksekusi
                            $output = [];
                            $return_var = 0;
                            exec($perintah_convert_jpg_bmp . " 2>&1", $output, $return_var);

                            if ($return_var !== 0) {
                                $RJ_10_Radiologi_Log = new RJ_10_Radiologi_Log();
                                $RJ_10_Radiologi_Log->noreg = $noreg_terakhir;
                                $RJ_10_Radiologi_Log->ket_log = "convert jpg to png gagal ==== ". $magick_path . "====" .$return_var . "====" .$output;
                                $RJ_10_Radiologi_Log->save();
                                return response()->json([
                                    'noreg' => $noreg_terakhir,
                                    'message' => "convert jpg to png gagal". $magick_path . "====" .$return_var . "====" .$output,
                                    'debug' => [
                                        'magick_path' => $magick_path,
                                        'return_var' => $return_var,
                                        'output' => $output
                                    ],
                                    'nama schedule' => 'Radiologi'
                                ], 200);
                            }
                        // =====convert jpg to bmp=========
                        // =====convert bmp to dicom=========
                            $jenis_kelamin = 'O';
                            if (!empty($registrasi_pasien->Pasien->JNSKELAMIN)) {
                                if ($registrasi_pasien->Pasien->JNSKELAMIN == 'L') {
                                    $jenis_kelamin = 'M';
                                } elseif ($registrasi_pasien->Pasien->JNSKELAMIN == 'P') {
                                    $jenis_kelamin = 'F';
                                }
                            }

                            // return response()->json(['success' => true, 'message' => 'Konversi berhasil']);
                            $input_bmp = base_path('public/contoh_gambar_bmp/output.bmp');
                            $output_dicom = base_path('public/contoh_gambar_dicom/output.dcm');
                            $img2dcm_path = 'C:\project\5satu_sehat\dicom installer\dcmtk-3.6.9-win64-dynamic\bin\img2dcm.exe';

                            // Siapkan parameter DICOM
                            $timestamp = Carbon::parse($TrxHasil1->TGLINPUT)->format('YmdHis');
                            $dicom_root = "1.2.826.0.1.3680043.2.1125.1";
                            $dicom_params = [
                                // --- Patient Level ---
                                'PatientName' => strtok($registrasi_pasien->Pasien->NAMAPASIEN, ','),
                                'PatientID' => $registrasi_pasien->NOPASIEN,
                                'PatientBirthDate' => Carbon::parse($registrasi_pasien->Pasien->TGLLAHIR)->format('Ymd'),
                                'PatientSex' => $jenis_kelamin,

                                // --- Study Level ---
                                'StudyInstanceUID'  => "$dicom_root.1.$timestamp",
                                'StudyDescription' => 'XRAY THORAX',
                                'AccessionNumber' => $noreg_terakhir,
                                'StudyDate' => Carbon::parse($TrxHasil1->TGLINPUT)->format('Ymd'),
                                'StudyTime' => Carbon::parse($TrxHasil1->TGLINPUT)->format('His'),

                                // --- Series Level ---
                                'SeriesInstanceUID' => "$dicom_root.2.$timestamp",
                                'SeriesDescription' => 'XRAY IMAGE',
                                'Modality' => 'CR',

                                // --- Instance Level ---
                                'SOPInstanceUID'    => "$dicom_root.3.$timestamp",
                                'InstanceNumber' => '1',
                            ];

                            // Build string parameter -k "key=value"
                            $dicom_k_params = '';
                            foreach ($dicom_params as $key => $value) {
                                $dicom_k_params .= ' -k "' . $key . '=' . $value . '"';
                            }

                            $perintah_convert_bmp_dicom = "\"$img2dcm_path\" --input-format BMP " . escapeshellarg($input_bmp) . " " . escapeshellarg($output_dicom) . $dicom_k_params;
                            // dd($perintah_convert_bmp_dicom);
                            $output2 = [];
                            $return_var2 = 0;
                            exec($perintah_convert_bmp_dicom . " 2>&1", $output2, $return_var2);

                            if ($return_var2 !== 0) {
                                $RJ_10_Radiologi_Log = new RJ_10_Radiologi_Log();
                                $RJ_10_Radiologi_Log->noreg = $noreg_terakhir;
                                $RJ_10_Radiologi_Log->ket_log = "Konversi BMP ke DICOM gagal ==== ". $img2dcm_path . "====" .$return_var2 . "====" .$output2;
                                $RJ_10_Radiologi_Log->save();
                                return response()->json([
                                    'noreg' => $noreg_terakhir,
                                    'message' => "Konversi BMP ke DICOM gagal". $img2dcm_path . "====" .$return_var2 . "====" .$output2,
                                    'debug' => [
                                        'magick_path' => $img2dcm_path,
                                        'return_var' => $return_var2,
                                        'output' => $output2
                                    ],
                                    'nama schedule' => 'Radiologi'
                                ], 200);
                            }
                        // =====convert bmp to dicom=========
                        // =====kirim dicom ke router dicom=========
                            $output_dicom = base_path('public/contoh_gambar_dicom/output.dcm');
                            $storescu_path = 'C:\project\5satu_sehat\dicom installer\dcmtk-3.6.9-win64-dynamic\bin\storescu.exe';
                            $storescu_cmd = "\"$storescu_path\" -v -aec DCMROUTER -aet SENDER 10.10.6.59 11112 " . escapeshellarg($output_dicom);

                            $storescu_output = [];
                            $storescu_return_var = 0;
                            exec($storescu_cmd . " 2>&1", $storescu_output, $storescu_return_var);

                            if ($storescu_return_var !== 0) {
                                $RJ_10_Radiologi_Log = new RJ_10_Radiologi_Log();
                                $RJ_10_Radiologi_Log->noreg = $noreg_terakhir;
                                $RJ_10_Radiologi_Log->ket_log = "Pengiriman DICOM gagal ==== ". $storescu_cmd . "====" .$storescu_return_var . "====" .$storescu_output;
                                $RJ_10_Radiologi_Log->save();
                                return response()->json([
                                    'noreg' => $noreg_terakhir,
                                    'message' => "Pengiriman DICOM gagal". $storescu_cmd . "====" .$storescu_return_var . "====" .$storescu_output,
                                    'debug' => [
                                        'magick_path' => $storescu_cmd,
                                        'return_var' => $storescu_return_var,
                                        'output' => $storescu_output
                                    ],
                                    'nama schedule' => 'Radiologi'
                                ], 200);
                            }
                        // =====kirim dicom ke router dicom=========
                        // =====ambil data imaging study=========
                            $attempts = 0;
                            $max_attempts = 5;
                            $radiologi_imaging_study_data = null;
                            while ($attempts < $max_attempts) {
                                $Noreg = (string) $noreg_terakhir;
                                $radiologi_imaging_study_data = $this->rawatJalan->radiologi_imaging_study($Noreg);
                                if (isset($radiologi_imaging_study_data->id)) {
                                    $RJ_10_Radiologi_update->imaging_study_id = $radiologi_imaging_study_data->id;
                                    $RJ_10_Radiologi_update->save();
                                    break; // Exit loop if successful
                                } else {
                                    $attempts++;
                                    if ($attempts >= $max_attempts) {
                                        $RJ_10_Radiologi_Log = new RJ_10_Radiologi_Log();
                                        $RJ_10_Radiologi_Log->noreg = $noreg_terakhir;
                                        $RJ_10_Radiologi_Log->ket_log = json_encode($radiologi_imaging_study_data);
                                        $RJ_10_Radiologi_Log->save();
                                        return response()->json([
                                            'noreg' => $noreg_terakhir,
                                            'message' => "Pengambilan Data Imaging Study radiologi gagal setelah beberapa kali percobaan",
                                            'nama schedule' => 'Radiologi'
                                        ], 200);
                                    }
                                    sleep(1); // Wait for 1 second before retrying
                                }
                            }
                        // =====ambil data imaging study=========
                    // =====kirim dicom=========
                }

                $RJ_10_Radiologi_update = RJ_10_Radiologi::where('encounter', $mapping_kunjungan_poli->encounter)
                                        ->where('noreg', $noreg_terakhir)
                                        ->first();
                if ($RJ_10_Radiologi_update->observation_id == null) {
                    $attempts = 0;
                    $max_attempts = 5;
                    $radiologi_observation_data = null;
                    while ($attempts < $max_attempts) {
                        $Noreg = (string) $noreg_terakhir;
                        $Patient_id = $mapping_pasien->kodesatusehat;
                        $Patient_name = strtok($registrasi_pasien->Pasien->NAMAPASIEN, ',');
                        $Encounter_id = $mapping_kunjungan_poli->encounter;
                        // $Practitioner_id = $mapping_dokter_spesialis->satusehat;
                        // $Practitioner_name = $mapping_dokter_spesialis->nama;
                        $Practitioner_id        = '10013576199';
                        $Practitioner_Name      = 'dr.Gusti Reka Kusuma';
                        $start_date = date('Y-m-d', strtotime($registrasi_pasien->TGLREG)).'T'.date('H:i:s', strtotime($registrasi_pasien->JAMREG)).'+07:00';

                        $tglreg_date = date('Y-m-d', strtotime($registrasi_pasien->TGLREG));
                        $jamreg_time = date('H:i:s', strtotime($registrasi_pasien->JAMREG));
                        $end_date = Carbon::parse($tglreg_date . ' ' . $jamreg_time)
                            ->addMinutes(30)
                            ->format('Y-m-d\TH:i:s') . '+07:00';

                        // $keterangan_hasil = $TrxHasil1->KETHASIL;
                        $keterangan_hasil = trim($TrxHasil1->KETHASIL);

                        $service_request_id = $RJ_10_Radiologi_update->service_request_id;
                        $imaging_study_id = $RJ_10_Radiologi_update->imaging_study_id;
                        // dd($Noreg, $Patient_id, $Patient_name, $Encounter_id, $Practitioner_id, $Practitioner_Name, $start_date, $end_date, $keterangan_hasil, $service_request_id, $imaging_study_id);
                        $radiologi_observation_data = $this->rawatJalan->observation_radiologi($Noreg, $Patient_id, $Patient_name, $Encounter_id, $Practitioner_id, $Practitioner_Name, $start_date, $end_date, $keterangan_hasil, $service_request_id, $imaging_study_id);
                        if (isset($radiologi_observation_data->id)) {
                            $RJ_10_Radiologi_update->observation_id = $radiologi_observation_data->id;
                            $RJ_10_Radiologi_update->save();
                            break; // Exit loop if successful
                        } else {
                            $attempts++;
                            if ($attempts >= $max_attempts) {
                                $RJ_10_Radiologi_Log = new RJ_10_Radiologi_Log();
                                $RJ_10_Radiologi_Log->noreg = $noreg_terakhir;
                                $RJ_10_Radiologi_Log->ket_log = json_encode($radiologi_observation_data);
                                $RJ_10_Radiologi_Log->save();
                                return response()->json([
                                    'noreg' => $noreg_terakhir,
                                    'message' => "Observation radiologi gagal dikirim",
                                    'nama schedule' => 'Radiologi'
                                ], 200);
                            }
                            sleep(1);
                        }
                    }
                }

                $RJ_10_Radiologi_update = RJ_10_Radiologi::where('encounter', $mapping_kunjungan_poli->encounter)
                                        ->where('noreg', $noreg_terakhir)
                                        ->first();
                if ($RJ_10_Radiologi_update->diagnostic_report_id == null) {
                    $attempts = 0;
                    $max_attempts = 5;
                    $radiologi_diagnostic_report_data = null;
                    while ($attempts < $max_attempts) {
                        $Noreg = (string) $noreg_terakhir;
                        $Patient_id = $mapping_pasien->kodesatusehat;
                        $Encounter_id = $mapping_kunjungan_poli->encounter;
                        // $Practitioner_id = $mapping_dokter_spesialis->satusehat;
                        // $Practitioner_name = $mapping_dokter_spesialis->nama;
                        $Practitioner_id        = '10013576199';
                        $Practitioner_Name      = 'dr.Gusti Reka Kusuma';
                        $start_date = date('Y-m-d', strtotime($registrasi_pasien->TGLREG)).'T'.date('H:i:s', strtotime($registrasi_pasien->JAMREG)).'+07:00';

                        $tglreg_date = date('Y-m-d', strtotime($registrasi_pasien->TGLREG));
                        $jamreg_time = date('H:i:s', strtotime($registrasi_pasien->JAMREG));
                        $end_date = Carbon::parse($tglreg_date . ' ' . $jamreg_time)
                            ->addMinutes(30)
                            ->format('Y-m-d\TH:i:s') . '+07:00';

                        $keterangan_hasil = $TrxHasil1->KETHASIL;

                        $service_request_id = $RJ_10_Radiologi_update->service_request_id;
                        $imaging_study_id = $RJ_10_Radiologi_update->imaging_study_id;
                        $observation_id = $RJ_10_Radiologi_update->observation_id;
                        $radiologi_diagnostic_report_data = $this->rawatJalan->diagnostic_report_radiologi($Noreg, $Patient_id, $Encounter_id, $Practitioner_id, $Practitioner_Name, $start_date, $end_date, $keterangan_hasil, $service_request_id, $imaging_study_id, $observation_id);
                        if (isset($radiologi_diagnostic_report_data->id)) {
                            $RJ_10_Radiologi_update->diagnostic_report_id = $radiologi_diagnostic_report_data->id;
                            $RJ_10_Radiologi_update->save();
                            break; // Exit loop if successful
                        } else {
                            $attempts++;
                            if ($attempts >= $max_attempts) {
                                $RJ_10_Radiologi_Log = new RJ_10_Radiologi_Log();
                                $RJ_10_Radiologi_Log->noreg = $noreg_terakhir;
                                $RJ_10_Radiologi_Log->ket_log = json_encode($radiologi_diagnostic_report_data);
                                $RJ_10_Radiologi_Log->save();
                                return response()->json([
                                    'noreg' => $noreg_terakhir,
                                    'message' => "Diagnostic Report radiologi gagal dikirim",
                                    'nama schedule' => 'Radiologi'
                                ], 200);
                            }
                            sleep(1);
                        }
                    }
                }

            }
        // =========================end Radiologi===============
    // =========================End 10. Pemeriksaan Penunjang=======================

    // ===========12. Diagnosis============
        public function diagnosis_menu(){
            return view('rawat-jalan.12-diagnosis.menu');
        }

        public function diagnosis_index(Request $request){
            if ($request->status == "error") {
                $data = RJ_12_Diagnosis_Log::orderBy('id', 'desc')->paginate(25);
            }else {
                $data = RJ_12_Diagnosis::orderBy('id', 'desc')->paginate(25);
            }
            return view('rawat-jalan.12-diagnosis.index', compact('data'));
        }

        public function diagnosis_primary_api($rekam_id, $kode_diagnosa, $deskripsi_diagnosa, $id_patient, $name_patient, $encounter_id){
            set_time_limit((int) 0);

            $data = $this->rawatJalan->diagnosis_primer($kode_diagnosa, $deskripsi_diagnosa, $id_patient, $name_patient, $encounter_id);
            if (isset($data->id)) {
                $diagnosis = new RJ_12_Diagnosis();
                $diagnosis->encounter = $encounter_id;
                $diagnosis->rekam_id = $rekam_id;
                $diagnosis->kode_icd = (String)$kode_diagnosa;
                $diagnosis->nama_icd = (String)$deskripsi_diagnosa;
                $diagnosis->id_diagnosa = $data->id;
                $diagnosis->save();
            }else {
                $log_diagnosis = new RJ_12_Diagnosis_Log();
                $log_diagnosis->rekam_id = $rekam_id;
                $log_diagnosis->ket_log = json_encode($data);
                $log_diagnosis->save();
            }
            return response()->json([
                'noreg' => $rekam_id,
                'message' => 'Data Berhasil Disimpan',
                'nama schedule' => 'diagnosis primary'
            ], 200);
        }
    // ===========End 12. Diagnosis========

    // ===========14. Tindakan Konseling========
            public function tindakan_menu(){
                return view('rawat-jalan.14-tindakan.menu');
            }

            public function konseling_index(Request $request){
                if ($request->status == "error") {
                    $data = RJ_14_Tindakan_Konseling_Log::orderBy('id', 'desc')->paginate(25);
                }else {
                    $data = RJ_14_Tindakan_Konseling::orderBy('id', 'desc')->paginate(25);
                }
                return view('rawat-jalan.14-tindakan.konseling.index', compact('data'));
            }

            public function konseling_api($rekam_id, $encounter_id, $kode_diagnosa, $deskripsi_diagnosa, $id_patient, $name_patient, $practitioner_id, $practitioner_name, $date){
                set_time_limit((int) 0);

                $data = $this->rawatJalan->tindakan_konseling_service_request($rekam_id, $kode_diagnosa, $deskripsi_diagnosa, $id_patient, $encounter_id, $practitioner_id, $practitioner_name, $date);
                if (isset($data->id)) {
                    $RJ_14_Tindakan_Konseling = new RJ_14_Tindakan_Konseling();
                    $RJ_14_Tindakan_Konseling->encounter = $encounter_id;
                    $RJ_14_Tindakan_Konseling->rekam_id = $rekam_id;
                    $RJ_14_Tindakan_Konseling->service_request_id = $data->id;
                    $RJ_14_Tindakan_Konseling->save();

                    $RJ_14_Tindakan_Konseling = RJ_14_Tindakan_Konseling::where('encounter', $encounter_id)
                                                    ->where('rekam_id', $rekam_id)
                                                    ->first();
                    if ($RJ_14_Tindakan_Konseling != null) {
                        $service_request_id = $RJ_14_Tindakan_Konseling->service_request_id;
                        $data = $this->rawatJalan->tindakan_konseling_procedure($service_request_id, $kode_diagnosa, $deskripsi_diagnosa, $id_patient, $name_patient, $encounter_id, $practitioner_id, $practitioner_name, $date);
                        if (isset($data->id)) {
                            $RJ_14_Tindakan_Konseling->procedure_id = $data->id;
                            $RJ_14_Tindakan_Konseling->save();
                        }
                    }else {
                        $RJ_14_Tindakan_Konseling_Log = new RJ_14_Tindakan_Konseling_Log();
                        $RJ_14_Tindakan_Konseling_Log->rekam_id = $rekam_id;
                        $RJ_14_Tindakan_Konseling_Log->ket_log = json_encode($data);
                        $RJ_14_Tindakan_Konseling_Log->save();
                    }
                }else {
                    $RJ_14_Tindakan_Konseling_Log = new RJ_14_Tindakan_Konseling_Log();
                    $RJ_14_Tindakan_Konseling_Log->rekam_id = $rekam_id;
                    $RJ_14_Tindakan_Konseling_Log->ket_log = json_encode($data);
                    $RJ_14_Tindakan_Konseling_Log->save();
                }
                return response()->json([
                    'noreg' => $rekam_id,
                    'message' => 'Data Berhasil Disimpan',
                    'nama schedule' => 'tindakan konseling'
                ], 200);
            }
    // ===========End 14. Tindakan Konseling====

    // ===========15. Tata Laksana============
        public function tata_laksana_menu(){
            return view('rawat-jalan.15-tata-lakasana.menu');
        }
        public function tata_laksana_obat_menu(){
            return view('rawat-jalan.15-tata-lakasana.obat.menu');
        }

        public function medication_index()
        {
            $query = RJ_15_Medication_Obat::query();
            if (request()->has('search') && request('search') != '') {
                $search = request('search');
                $query->where(function($q) use ($search) {
                    $q->where('kode_barang_mabar', 'like', "%$search%")
                      ->orWhere('nama_barang_mabar', 'like', "%$search%")
                      ->orWhere('kode_kfa', 'like', "%$search%")
                      ->orWhere('keterangan_kfa', 'like', "%$search%")
                      ->orWhere('kode_satu_sehat', 'like', "%$search%") ;
                });
            }
            $data = $query->paginate(10)->appends(request()->all());
            return view('rawat-jalan.15-tata-lakasana.obat.medication', compact('data'));
        }

        public function medication_store(Request $request)
        {
            $request->validate([
                'kode_barang_mabar' => 'required',
                'kode_kfa' => 'required',
                'keterangan_kfa' => 'required',
            ]);
            $med = new RJ_15_Medication_Obat();
            $med->kode_barang_mabar = $request->kode_barang_mabar;
            $med->kode_kfa = $request->kode_kfa;
            $med->keterangan_kfa = $request->keterangan_kfa;
            $med->save();
            return redirect()->back()->with('success', 'Data berhasil ditambahkan');
        }

        public function medication_update(Request $request, $id)
        {
            $request->validate([
                'kode_barang_mabar' => 'required',
                'kode_kfa' => 'required',
                'keterangan_kfa' => 'required',
            ]);
            $med = RJ_15_Medication_Obat::findOrFail($id);
            $med->kode_barang_mabar = $request->kode_barang_mabar;
            $med->kode_kfa = $request->kode_kfa;
            $med->keterangan_kfa = $request->keterangan_kfa;
            $med->kode_satu_sehat = $request->kode_satu_sehat;
            $med->save();
            return redirect()->back()->with('success', 'Data berhasil diupdate');
        }

        public function medication_destroy($id)
        {
            $med = RJ_15_Medication_Obat::findOrFail($id);
            $med->delete();
            return redirect()->back()->with('success', 'Data berhasil dihapus');
        }

        public function medication_ambil_data_satu_sehat($id)
        {
            $med = RJ_15_Medication_Obat::findOrFail($id);

            $kode_obat_rs = $med->kode_barang_mabar;
            $kode_kfa = $med->kode_kfa;
            $deskripsi_kfa = $med->keterangan_kfa;
            $kode_obat = "BS066";
            $display_obat = "Tablet";
            $data = $this->rawatJalan->create_medication($kode_obat_rs, $kode_kfa, $deskripsi_kfa, $kode_obat, $display_obat);
            if (isset($data->id)) {
                $med->kode_satu_sehat = $data->id;
                $med->save();
                return redirect()->back()->with('success', 'Data obat berhasil diambil dari Satu Sehat');
            }else {
                return redirect()->back()->with('error', 'Data obat gagal diambil dari Satu Sehat Atau tidak valid, Silahkan ganti dengan kode kfa lainnya');
            }
        }

        public function medication_sync()
        {
            $mubar = Obat::get();
            if (!$mubar->isEmpty()) {
                foreach ($mubar as $item) {
                    if (!preg_match('/^[a-zA-Z ]/', $item->kd_obat)) {
                        continue;
                    }
                    $med = RJ_15_Medication_Obat::where('kode_barang_mabar', $item->kd_obat)->first();
                    if (!$med) {
                        $med = new RJ_15_Medication_Obat();
                        $med->kode_barang_mabar = $item->kd_obat;
                    }
                    $med->nama_barang_mabar = $item->nama;
                    $med->kode_kfa = $item->kode_kfa;
                    $med->keterangan_kfa = $item->keterangan_kfa;
                    $med->save();
                }
            }
            return redirect()->back()->with('success', 'Sinkronisasi obat berhasil dijalankan');
        }

        public function medication_cari_kfa(Request $request)
        {
            $search = $request->search ?? '';
            $page = $request->page ?? 1;
            $size = $request->size ?? 10;
            if (empty($search)) {
                $searchResult = [];
            } else {
                $searchResult = $this->rawatJalan->kfa_api($search, $page, $size);
            }
            return view('rawat-jalan.15-tata-lakasana.obat.cari-kfa', compact('searchResult', 'search', 'page', 'size'));
        }

        public function peresepan_obat_index(Request $request){
            if ($request->status == "error") {
                $data = RJ_15_Medication_Request_Log::orderBy('id', 'desc')->paginate(25);
            }else {
                $data = RJ_15_Medication_Request::orderBy('id', 'desc')->paginate(25);
            }
            return view('rawat-jalan.15-tata-lakasana.obat.peresepan-obat', compact('data'));
        }

        public function pengkajian_resep_index(Request $request){
            if ($request->status == "error") {
                $data = RJ_15_Questionnaire_Response_Log::orderBy('id', 'desc')->paginate(25);
            }else {
                $data = RJ_15_Questionnaire_Response::orderBy('id', 'desc')->paginate(25);
            }
            return view('rawat-jalan.15-tata-lakasana.obat.pengkajian-resep', compact('data'));
        }

        public function pengeluaran_obat_index(Request $request){
            if ($request->status == "error") {
                $data = RJ_15_Medication_Dispense_Log::orderBy('id', 'desc')->paginate(25);
            }else {
                $data = RJ_15_Medication_Dispense::orderBy('id', 'desc')->paginate(25);
            }
            return view('rawat-jalan.15-tata-lakasana.obat.pengeluaran-obat', compact('data'));
        }

        public function peresepan_obat_api($registrasi_pasien_terakhir, $id_obat_satusehat, $display_obat_satusehat, $id_patient, $name_patient, $encounter, $date, $id_practitioner, $name_practitioner, $dosis_obat, $start_waktu_pemberian_obat, $end_waktu_pemberian_obat, $jumlah_obat, $durasi_penggunaan, $kode_obat, $kode_obat_kfa, $deskripsi_obat_kfa, $nomer_resep)
        {
            // dd($registrasi_pasien_terakhir, $id_obat_satusehat, $display_obat_satusehat, $id_patient, $name_patient, $encounter, $date, $id_practitioner, $name_practitioner, $dosis_obat, $start_waktu_pemberian_obat, $end_waktu_pemberian_obat, $jumlah_obat, $durasi_penggunaan, $kode_obat, $kode_obat_kfa, $deskripsi_obat_kfa, $nomer_resep);
            set_time_limit((int) 0);
            $medication_request = $this->rawatJalan->create_medication_request($id_obat_satusehat, $display_obat_satusehat, $id_patient, $name_patient, $encounter, $date, $id_practitioner, $name_practitioner, $dosis_obat, $start_waktu_pemberian_obat, $end_waktu_pemberian_obat, $jumlah_obat, $durasi_penggunaan);
            if (isset($medication_request->id)) {
                $medication_request_model = new RJ_15_Medication_Request();
                $medication_request_model->encounter = $encounter;
                $medication_request_model->rekam_id = $registrasi_pasien_terakhir;
                $medication_request_model->id_medication_request = $medication_request->id;
                $medication_request_model->save();
                // ======================pengkajian resep=======================
                    $questionnaire_response = $this->rawatJalan->create_questionnaire_response($kode_obat, $id_patient, $name_patient, $encounter, $date, $id_practitioner, $name_practitioner);
                    if (isset($questionnaire_response->id)) {
                        $RJ_15_Questionnaire_Response = new RJ_15_Questionnaire_Response();
                        $RJ_15_Questionnaire_Response->encounter = $encounter;
                        $RJ_15_Questionnaire_Response->rekam_id = $registrasi_pasien_terakhir;
                        $RJ_15_Questionnaire_Response->id_questionnaire_response = $questionnaire_response->id;
                        $RJ_15_Questionnaire_Response->save();
                    }else {
                        $RJ_15_Questionnaire_Response_Log = new RJ_15_Questionnaire_Response_Log();
                        $RJ_15_Questionnaire_Response_Log->rekam_id = $registrasi_pasien_terakhir;
                        $RJ_15_Questionnaire_Response_Log->ket_log = json_encode($questionnaire_response);
                        $RJ_15_Questionnaire_Response_Log->save();
                    }
                // ======================end pengkajian resep=======================

                // ======================pengeluaran obat=======================
                    $medication_dispense_obat = $this->rawatJalan->create_medication_dispense_obat($kode_obat, $kode_obat_kfa, $deskripsi_obat_kfa);
                    if (isset($medication_dispense_obat->id)) {
                        $medication_dispense_model = new RJ_15_Medication_Dispense();
                        $medication_dispense_model->encounter = $encounter;
                        $medication_dispense_model->rekam_id = $registrasi_pasien_terakhir;
                        $medication_dispense_model->id_medication = $medication_dispense_obat->id;
                        $medication_dispense_model->save();

                        $medication_dispense_model = RJ_15_Medication_Dispense::where('rekam_id', $registrasi_pasien_terakhir)->where('id_medication', $medication_dispense_obat->id)->first();
                        // $medication_dispense_model = RJ_15_Medication_Dispense::where('noreg', $noreg_terakhir)->where('id_medication', 'dce53a47-bb37-4558-b525-fbb2f494f885')->first();

                        $medication_id = $medication_dispense_obat->id;
                        $medication_id = '109e9faa-d8f6-4768-9cdc-ba7069324dec';
                        $medication_request_id = $medication_request->id;
                        // $medication_request_id = '661e88a4-c193-48b7-909e-e73598109ee2';

                        $medication_dispense = $this->rawatJalan->create_medication_dispense($nomer_resep, $medication_id, $deskripsi_obat_kfa, $id_patient, $name_patient, $encounter, $id_practitioner, $name_practitioner, $medication_request_id, $start_waktu_pemberian_obat, $end_waktu_pemberian_obat);
                        if (isset($medication_dispense->id)) {
                            $medication_dispense_model->id_medication_dispense = $medication_dispense->id;
                            $medication_dispense_model->save();
                        } else {
                            $medication_dispense_model_log = new RJ_15_Medication_Dispense_Log();
                            $medication_dispense_model_log->rekam_id = $registrasi_pasien_terakhir;
                            $medication_dispense_model_log->ket_log = json_encode($medication_dispense);
                            $medication_dispense_model_log->save();
                        }
                    }else {
                        $log_medication_dispense = new RJ_15_Medication_Dispense_Log();
                        $log_medication_dispense->rekam_id = $registrasi_pasien_terakhir;
                        $log_medication_dispense->ket_log = json_encode($medication_dispense_obat);
                        $log_medication_dispense->save();
                    }
                // ======================end pengeluaran obat=======================
            }

            return response()->json([
                'noreg' => $registrasi_pasien_terakhir,
                'message' => 'Semua Data sukses dikirim',
                'nama schedule' => 'Tata Laksana Obat'
            ], 200);
        }
    // ===========End 15. Tata Laksana========

}
