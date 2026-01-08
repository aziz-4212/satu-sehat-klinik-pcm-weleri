<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Services\Patient;
use App\Services\RawatJalan;
use App\Models\Pasien;
use App\Models\Obat;
use App\Models\RJ00UkpKefarmasianLaboratorium;
use App\Models\RJ_00_Organisation_Location;
use App\Models\RJ_01_Patient;
use App\Models\RJ_01_Practitioner;
use App\Models\RJ_02_A_Kunjungan_Baru;
use App\Models\RJ_02_A_Kunjungan_Baru_Log;
use App\Models\RJ_02_B_Masuk_Ruang;
use App\Models\RJ_02_B_Masuk_Ruang_Log;
use App\Models\RJ_12_Diagnosis;
use App\Models\RJ_12_Diagnosis_Log;
use App\Models\RJ_15_Medication_Obat;
use App\Models\RJ_15_Medication_Request;
use App\Models\RJ_15_Medication_Request_Log;
use App\Models\RJ_15_Questionnaire_Response;
use App\Models\RJ_15_Questionnaire_Response_Log;
use App\Models\RJ_15_Medication_Dispense;
use App\Models\RJ_15_Medication_Dispense_Log;
use App\Models\RJ_10_laboratory;
use App\Models\RJ_10_laboratory_Log;
use App\Models\RJ_10_Radiologi;
use App\Models\RJ_10_Radiologi_Log;
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
            $Configs = Configs::first();
            // dd($Configs);
            $id_patient = "P01536204075";
            $name_patient = "sunarsih";
            $id_practitioner = "10016656663";
            $name_practitioner = "TUTIK NUR FAIZAH";
            $date = "2025-09-09";
            // $encounter = $this->rawatJalan->kunjungan_baru($id_patient, $name_patient, $id_practitioner, $name_practitioner, $date);
            // dd($encounter);

            $encounter_id = "1da6ffde-e80b-46bc-8e54-05554675b1c5";
            $id_patient = "P01536204075";
            $name_patient = "sunarsih";
            $id_practitioner = "10016656663";
            $name_practitioner = "TUTIK NUR FAIZAH";
            $datetime               = '2025-09-09T12:15:44.000+07:00';
            $datetime_end           = '2025-09-09T12:30:56.000+07:00';
            $id_location = "3a4ff0ba-3edd-42ce-99f8-25c20383a3f2";
            $name_location = "klinik dokter umum";
            // $data = $this->rawatJalan->masuk_ruang($encounter_id, $id_patient, $name_patient, $id_practitioner, $name_practitioner, $datetime, $datetime_end, $id_location, $name_location);
            // dd($data);

            $kode_diagnosa = "j06.9";
            $deskripsi_diagnosa = "Penyakit ISPA akut tidak spesifik";
            $mapping_pasien_kodesatusehat = "P01536204075";
            $mapping_pasien_namasatusehat = "sunarsih";
            $mapping_kunjungan_poli_encounter = "1da6ffde-e80b-46bc-8e54-05554675b1c5";
            // $data = $this->rawatJalan->diagnosis_primer($kode_diagnosa, $deskripsi_diagnosa, $mapping_pasien_kodesatusehat, $mapping_pasien_namasatusehat, $mapping_kunjungan_poli_encounter);
            // dd($data);

            $id_obat_satusehat = "88eb8501-c785-42b2-b252-7e9517f5600b";
            $display_obat_satusehat = "Paracetamol 500 mg Tablet (PAMOL)";
            $id_patient = "P01536204075";
            $id_patient_display = "sunarsih";
            $encounter = "1da6ffde-e80b-46bc-8e54-05554675b1c5";
            $tanggal_peresepan = '2025-09-09T12:30:56.000+07:00';
            $id_dokter_satu_sehat = "10016656663";
            $display_dokter_satu_sehat = "TUTIK NUR FAIZAH";
            $dosis_obat = "3x1 Tablet";
            $start_waktu_pemberian_obat = '2025-09-09T12:30:56.000+07:00';
            $end_waktu_pemberian_obat = '2025-09-12T12:30:56.000+07:00';
            $jumlah_obat = 9;
            $durasi_penggunaan = "3";
            // $medication_request = $this->rawatJalan->create_medication_request($id_obat_satusehat, $display_obat_satusehat, $id_patient, $id_patient_display, $encounter, $tanggal_peresepan, $id_dokter_satu_sehat, $display_dokter_satu_sehat, $dosis_obat, $start_waktu_pemberian_obat, $end_waktu_pemberian_obat, $jumlah_obat, $durasi_penggunaan);
            // dd($medication_request);
            $kode_quesnionnaire = "2025090911";
            $id_apoteker = "10016656663";
            $display_apoteker = "TUTIK NUR FAIZAH";
            // $questionnaire_response = $this->rawatJalan->create_questionnaire_response($kode_quesnionnaire, $id_patient, $id_patient_display, $encounter, $tanggal_peresepan, $id_apoteker, $display_apoteker);
            // dd($questionnaire_response);
            $kode_barang_obat = "Para";
            $kode_oabat_kfa = "93002313";
            $deskripsi_obat_kfa = "Paracetamol 500 mg Tablet (PAMOL)";
            // $medication_dispense_obat = $this->rawatJalan->create_medication_dispense_obat($kode_barang_obat, $kode_oabat_kfa, $deskripsi_obat_kfa);
            // dd($medication_dispense_obat);
            $nomer_resep = "REG#2025090911";
            $medication_id = "e6cb6f65-0a69-4f37-ac6a-9db817333db2";
            $id_dockter_satu_sehat = "10016656663";
            $display_dockter_satu_sehat = "TUTIK NUR FAIZAH";
            $medication_request_id = "ae25919a-0e6a-4e58-b7cc-476a5283ded4";
            $start_waktu_pemberian_obat = '2025-09-09T12:30:56.000+07:00';
            $end_waktu_pemberian_obat = '2025-09-09T12:30:56.000+07:00';
            // $medication_dispense = $this->rawatJalan->create_medication_dispense($nomer_resep, $medication_id, $display_obat_satusehat, $id_patient, $id_patient_display, $encounter, $id_dockter_satu_sehat, $display_dockter_satu_sehat, $medication_request_id, $start_waktu_pemberian_obat, $end_waktu_pemberian_obat);
            // dd($medication_dispense);

            $Patient_id = "P01536204075";
            $Patient_Name = "sunarsih";
            $Encounter_id ="1da6ffde-e80b-46bc-8e54-05554675b1c5";
            $Practitioner_id = "10016656663";
            $Practitioner_Name = "TUTIK NUR FAIZAH";
            $start_date = '2025-09-09T12:30:56.000+07:00';
            $end_date = '2025-09-09T12:30:56.000+07:00';
            // $procedure_data = $this->rawatJalan->procedure_status_puasa_laboratorium_nominal($Patient_id, $Patient_Name, $Encounter_id, $Practitioner_id, $Practitioner_Name, $start_date, $end_date);
            // dd($procedure_data);
            $Noreg = "REG#2025090911";
            $kode_loinc = "41653-7";
            $nama_loinc = "41653-7";
            $deskripsi_loinc = "41653-7";
            $Procedure_Id = "33a69faf-eb47-4abc-b2d4-44cd0c10d59f";
            // $service_request_data = $this->rawatJalan->service_request_laboratorium_nominal($Noreg, $Patient_id, $Patient_Name, $Encounter_id, $Practitioner_id, $Practitioner_Name, $start_date, $end_date, $kode_loinc, $nama_loinc, $deskripsi_loinc, $Procedure_Id);
            // dd($service_request_data);
            $date = '2025-09-09T12:30:56.000+07:00';
            $kode_snomed = "119364003";
            $nama_snomed = "Serum specimen";
            $value = "367";
            $satuan = "mg/dl";
            $service_request_id = "8d566bd9-14d5-426c-a2a3-8005c13aa649";
            // $specimen_data = $this->rawatJalan->specimen_laboratorium_nominal($Noreg, $Patient_id, $Patient_Name, $Practitioner_id, $Practitioner_Name, $date, $kode_snomed, $nama_snomed, $value, $satuan, $service_request_id);
            // dd($specimen_data);
            $Specimen_Id = "4d87f3d6-5155-42ef-8e68-d0721725d908";
            $ServiceRequest_Id = "8d566bd9-14d5-426c-a2a3-8005c13aa649";
            $loinc_code = "41653-7";
            $loinc_name = "41653-7";
            $request_date = '2025-09-09T12:30:56.000+07:00';
            $result_date = '2025-09-09T12:30:56.000+07:00';
            // $observation_data = $this->rawatJalan->observation_laboratorium_nominal($Noreg, $Patient_id, $Encounter_id, $Practitioner_id, $Specimen_Id, $ServiceRequest_Id, $loinc_code, $loinc_name, $request_date, $result_date);
            // dd($observation_data);
            // dd("halo");
            $Observation_id = "f08e3c1f-387d-476e-b7f3-1e61401d2eb2";
            $Specimen_Id = "4d87f3d6-5155-42ef-8e68-d0721725d908";
            $ServiceRequest_id = "8d566bd9-14d5-426c-a2a3-8005c13aa649";
            $diagnostic_report_data = $this->rawatJalan->diagnostic_report_laboratorium_nominal($Noreg, $Patient_id, $Encounter_id, $Practitioner_id, $Observation_id, $Specimen_Id, $ServiceRequest_id, $kode_loinc, $nama_loinc, $request_date, $result_date);
            dd($diagnostic_report_data);
            if ($noreg_terakhir == null) {
                $mapping_kunjungan_poli = RJ_02_A_Kunjungan_Baru::orderBy('noreg', 'desc')->pluck('noreg')->first();
                $log_encounter = RJ_02_A_Kunjungan_Baru_Log::orderBy('noreg', 'desc')->pluck('noreg')->first();
                if ($mapping_kunjungan_poli > $log_encounter) {
                    $registrasi_pasien_terakhir = $mapping_kunjungan_poli;
                }elseif ($mapping_kunjungan_poli < $log_encounter) {
                    $registrasi_pasien_terakhir = $log_encounter;
                }elseif ($mapping_kunjungan_poli == $log_encounter) {
                    $registrasi_pasien_terakhir = $mapping_kunjungan_poli;
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
                        'nama schedule' => 'pendaftaran pendataan pasien'
                    ], 200);
                }

                $registrasi_pasien = RegistrasiPasien::where('NOREG', $registrasi_pasien_terakhir)
                    ->whereHas('Registrasi_Dokter', function ($query) {
                        $query->where('BAGREGDR', 'like', '91%');
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
                                ->first();
            }
            if ($registrasi_pasien == null) {
                $log_encounter = new RJ_02_A_Kunjungan_Baru_Log();
                $log_encounter->noreg = $registrasi_pasien_terakhir;
                $log_encounter->ket_log = "noreg bukan pasien rawat jalan / NIK Salah";
                $log_encounter->save();

                return response()->json([
                    'noreg' => $registrasi_pasien_terakhir,
                    'message' => "noreg bukan pasien rawat jalan / NIK Salah",
                    'nama schedule' => 'pendaftaran pendataan pasien'
                ], 200);
            }

            $now = Carbon::now()->setTimezone('Asia/Jakarta');
            $now->format('Y-m-d H:i:s.v');
            // $registrasi_pasien_terakhir = '2511300002';
            $registrasi_pasien = RegistrasiPasien::where('NOREG', $registrasi_pasien_terakhir)->first();
            // $mapping_dokter_spesialis = RJ_01_Practitioner::where('kode_dokter', $registrasi_pasien->Registrasi_Dokter->KODEDOKTER)->first();
            // if ($mapping_dokter_spesialis == null) {
            //     $RJ_10_laboratory_Log = new RJ_10_laboratory_Log();
            //     $RJ_10_laboratory_Log->noreg = $noreg_terakhir;
            //     $RJ_10_laboratory_Log->ket_log = "Dokter untuk Pasien ".$registrasi_pasien->Pasien->NAMAPASIEN." Tidak Ditemukan Di Mapping Dokter Spesialis";
            //     $RJ_10_laboratory_Log->save();
            //     return response()->json([
            //         'noreg' => $noreg_terakhir,
            //         'message' => "Dokter untuk Pasien ".$registrasi_pasien->Pasien->NAMAPASIEN." Tidak Ditemukan Di Mapping Dokter Spesialis",
            //         'nama schedule' => 'Laboratorium'
            //     ], 200);
            // }

            // ============================== cek format NIK ==============================
            $data = $this->patient->search_nik($registrasi_pasien->Pasien->NOKTP);
            if (is_object($data) && property_exists($data, 'total') && $data->total != 0) {
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
                $log_encounter = new RJ_02_A_Kunjungan_Baru_Log();
                $log_encounter->noreg = $registrasi_pasien_terakhir;
                $log_encounter->ket_log = 'NIK '.$registrasi_pasien->Pasien->NAMAPASIEN.' Tidak Ditemukan';
                $log_encounter->save();
                return response()->json([
                    'noreg' => $registrasi_pasien_terakhir,
                    'message' => 'NIK '.$registrasi_pasien->Pasien->NAMAPASIEN.' Tidak Ditemukan',
                    'nama schedule' => 'pendaftaran pendataan pasien'
                ], 200);
            }
            // ===================================Encounter==============================================
                $dateArray = explode(' ', $registrasi_pasien->TGLREG);
                $dateValue = $dateArray[0];

                $id_patient = $data->entry[0]->resource->id;
                // $name_patient = $data->entry[0]->resource->name[0]->text;
                $name_patient = strtok($registrasi_pasien->Pasien->NAMAPASIEN, ',');
                $id_practitioner = '10013576199';
                $name_practitioner = 'dr.Gusti Reka Kusuma';
                $date = $dateValue;

                $encounter = $this->rawatJalan->kunjungan_baru($id_patient, $name_patient, $id_practitioner, $name_practitioner, $date);
                if (isset($encounter->id)) {
                    $mapping_kunjungan_poli = new RJ_02_A_Kunjungan_Baru();
                    $mapping_kunjungan_poli->noreg = $registrasi_pasien_terakhir;
                    $mapping_kunjungan_poli->encounter = $encounter->id;
                    $mapping_kunjungan_poli->tanggal = $now;
                    $mapping_kunjungan_poli->save();
                }else {
                    $log_encounter = new RJ_02_A_Kunjungan_Baru_Log();
                    $log_encounter->noreg = $registrasi_pasien_terakhir;
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

        public function masuk_ke_ruang_pemeriksaan_api(Request $request){
            set_time_limit((int) 0);
            $rj_masuk_ruang = RJ_02_B_Masuk_Ruang::orderBy('noreg', 'desc')->pluck('noreg')->first();
            $log_rj_masuk_ruang = RJ_02_B_Masuk_Ruang_Log::orderBy('noreg', 'desc')->pluck('noreg')->first();

            if ($rj_masuk_ruang == null && $log_rj_masuk_ruang == null) {
                $noreg_terakhir = RJ_02_A_Kunjungan_Baru::orderBy('noreg', 'asc')->pluck('noreg')->first();
            }elseif ($rj_masuk_ruang > $log_rj_masuk_ruang) {
                $noreg_terakhir = $rj_masuk_ruang;
            }elseif ($rj_masuk_ruang < $log_rj_masuk_ruang) {
                $noreg_terakhir = $log_rj_masuk_ruang;
            }elseif ($rj_masuk_ruang == $log_rj_masuk_ruang) {
                $noreg_terakhir = $rj_masuk_ruang;
            }

            $noreg_terakhir = $noreg_terakhir+1;
            $noreg_tanggal_depan = (substr($noreg_terakhir, 0, -4)+1)."0000";
            $data_tanggal_terakhir = RJ_02_A_Kunjungan_Baru::where('noreg', "<",$noreg_tanggal_depan)->orderBy('noreg', 'desc')->pluck('noreg')->first();
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
                    'nama schedule' => 'Encounter Masuk Ruang'
                ], 200);
            }

            if (substr($noreg_terakhir, 2, 4) == '1232') {
                $noreg_terakhir += 100000000;
                $noreg_terakhir = substr_replace($noreg_terakhir, '0101', 2, 4);
            }

            $data_terbesar = RJ_02_A_Kunjungan_Baru::orderBy('noreg', 'desc')->pluck('noreg')->first();
            if ($noreg_terakhir > $data_terbesar) {
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "Noreg Belum Terdaftar",
                    'nama schedule' => 'Encounter Masuk Ruang'
                ], 200);
            }
            // $noreg_terakhir = '2312300005';
            $mapping_kunjungan_poli = RJ_02_A_Kunjungan_Baru::where('noreg', $noreg_terakhir)->first();
            if ($mapping_kunjungan_poli == null) {
                $log_rj_masuk_ruang = new RJ_02_B_Masuk_Ruang_Log();
                $log_rj_masuk_ruang->noreg = $noreg_terakhir;
                $log_rj_masuk_ruang->ket_log = 'Mapping kunjungan poli tidak ditemukan';
                $log_rj_masuk_ruang->save();
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "Mapping kunjungan poli tidak ditemukan",
                    'nama schedule' => 'Encounter Masuk Ruang'
                ], 200);
            }
            $registrasi_pasien = RegistrasiPasien::where('NOREG', $noreg_terakhir)->first();
            if ($registrasi_pasien == null) {
                $log_rj_masuk_ruang = new RJ_02_B_Masuk_Ruang_Log();
                $log_rj_masuk_ruang->noreg = $noreg_terakhir;
                $log_rj_masuk_ruang->ket_log = 'Noreg Belum Terdaftar';
                $log_rj_masuk_ruang->save();
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "Noreg Belum Terdaftar",
                    'nama schedule' => 'Encounter Masuk Ruang'
                ], 200);
            }
            $mapping_organization = MappingOrganization::where('koders', $registrasi_pasien->Registrasi_Dokter->BAGREGDR)->first();
            if ($mapping_organization == null) {
                $log_rj_masuk_ruang = new RJ_02_B_Masuk_Ruang_Log();
                $log_rj_masuk_ruang->noreg = $noreg_terakhir;
                $log_rj_masuk_ruang->ket_log = "Organisasi Tidak Ditemukan Di Mapping Organization";
                $log_rj_masuk_ruang->save();
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "Organisasi Tidak Ditemukan Di Mapping Organization",
                    'nama schedule' => 'Encounter Masuk Ruang'
                ], 200);
            }
            $mapping_pasien = MappingPasien::where('norm', $registrasi_pasien->NOPASIEN)->first();
            if ($mapping_pasien == null) {
                $log_rj_masuk_ruang = new RJ_02_B_Masuk_Ruang_Log();
                $log_rj_masuk_ruang->noreg = $noreg_terakhir;
                $log_rj_masuk_ruang->ket_log = "Pasien Tidak Ditemukan Di Mapping Pasien";
                $log_rj_masuk_ruang->save();
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "Pasien Tidak Ditemukan Di Mapping Pasien",
                    'nama schedule' => 'Encounter Masuk Ruang'
                ], 200);
            }

            $mapping_dokter_spesialis = RJ_01_Practitioner::where('kode_dokter', $registrasi_pasien->Registrasi_Dokter->KODEDOKTER)->first();
            if ($mapping_dokter_spesialis == null) {
                $log_rj_masuk_ruang = new RJ_02_B_Masuk_Ruang_Log();
                $log_rj_masuk_ruang->noreg = $noreg_terakhir;
                $log_rj_masuk_ruang->ket_log = "Dokter untuk Pasien ".$registrasi_pasien->Pasien->NAMAPASIEN." Tidak Ditemukan Di Mapping Dokter Spesialis";
                $log_rj_masuk_ruang->save();
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "Dokter untuk Pasien ".$registrasi_pasien->Pasien->NAMAPASIEN." Tidak Ditemukan Di Mapping Dokter Spesialis",
                    'nama schedule' => 'Encounter Masuk Ruang'
                ], 200);
            }

            $hari = Carbon::parse(date('Y-m-d', strtotime($registrasi_pasien->TGLREG)))->locale('id')->isoFormat('dddd');
            if ($hari == 'Minggu') {
                $kodehari = '1';
            }elseif ($hari == 'Senin') {
                $kodehari = '2';
            }elseif ($hari == 'Selasa') {
                $kodehari = '3';
            }elseif ($hari == 'Rabu') {
                $kodehari = '4';
            }elseif ($hari == 'Kamis') {
                $kodehari = '5';
            }elseif ($hari == 'Jumat') {
                $kodehari = '6';
            }elseif ($hari == 'Sabtu') {
                $kodehari = '7';
            }

            // $jadwal_dokter = JadwalDokter::where('KODEDOKTER', $mapping_dokter_spesialis->kodepelayanan)
            //     ->where('KODEBAGIAN', $mapping_organization->koders)
            //     ->where('KODEHARI', $kodehari)
            //     ->first();
            // if ($jadwal_dokter == null) {
            //     $log_rj_masuk_ruang = new RJ_02_B_Masuk_Ruang_Log();
            //     $log_rj_masuk_ruang->noreg = $noreg_terakhir;
            //     $log_rj_masuk_ruang->ket_log = "jadwal dokter tidak ditemukan";
            //     $log_rj_masuk_ruang->save();
            //     return response()->json([
            //         'noreg' => $noreg_terakhir,
            //         'message' => "jadwal dokter tidak ditemukan",
            //         'nama schedule' => 'Encounter Masuk Ruang'
            //     ], 200);
            // }

            // $jam_mulai = explode(' ', $jadwal_dokter->JAMMULAI)[1];
            // $jam_selesai = explode(' ', $jadwal_dokter->JAMSELESAI)[1];

            // // Bersihkan milidetik
            // $jam_mulai = str_replace('.000', '', $jam_mulai);
            // $jam_selesai = str_replace('.000', '', $jam_selesai);
            $jam_mulai = sprintf('%02d:%02d:%02d', rand(9, 12), rand(0, 59), rand(0, 59));
            // Proses dengan Carbon
            $jam_masuk = Carbon::createFromFormat('H:i:s', $jam_mulai)
                ->addMinutes(rand(0, 15))
                ->format('H:i:s');
            $jam_keluar = Carbon::createFromFormat('H:i:s', $jam_masuk)
                ->addMinutes(rand(15, 30))
                ->format('H:i:s');
            // if (Carbon::createFromFormat('H:i:s', $jam_keluar)
            //         ->greaterThan(Carbon::createFromFormat('H:i:s', $jam_selesai))) {
            //     $jam_keluar = $jam_selesai;
            // }

            $encounter_id           = $mapping_kunjungan_poli->encounter;
            $id_patient             = $mapping_pasien->kodesatusehat;
            $name_patient           = $mapping_pasien->nama;
            $id_practitioner        = $mapping_dokter_spesialis->satu_sehat_id;
            $name_practitioner      = $mapping_dokter_spesialis->nama;
            $datetime               = date('Y-m-d', strtotime($registrasi_pasien->TGLREG)).'T'.$jam_masuk.'.000+07:00';
            $datetime_end           = date('Y-m-d', strtotime($registrasi_pasien->TGLREG)).'T'.$jam_keluar.'.000+07:00';
            $id_location            = $mapping_organization->location->kodesatusehat;
            $name_location          = $mapping_organization->location->deskripsi;

            try {
                $data = $this->rawatJalan->masuk_ruang($encounter_id, $id_patient, $name_patient, $id_practitioner, $name_practitioner, $datetime, $datetime_end, $id_location, $name_location);
                $rj_masuk_ruang_model = new RJ_02_B_Masuk_Ruang();
                $rj_masuk_ruang_model->encounter = $encounter_id;
                $rj_masuk_ruang_model->noreg = $noreg_terakhir;
                $rj_masuk_ruang_model->id_satu_sehat = $data->id;
                $rj_masuk_ruang_model->save();
            } catch (\Throwable $th) {
                $log_rj_masuk_ruang = new RJ_02_B_Masuk_Ruang_Log();
                $log_rj_masuk_ruang->noreg = $noreg_terakhir;
                $log_rj_masuk_ruang->ket_log = "duplicate";
                $log_rj_masuk_ruang->save();
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "duplicate",
                    'nama schedule' => 'Encounter Masuk Ruang'
                ], 200);
            }

            return response()->json([
                'noreg' => $noreg_terakhir,
                'message' => 'Semua Data sukses dikirim',
                'nama schedule' => 'Encounter Masuk Ruang'
            ], 200);
        }
    // ===========End 02. Pendaftaran Kunjungan Rawat Jalan============

    // =========================10. Pemeriksaan Penunjang=======================
        // =========================Laboratorium=======================
            public function laboratory_api(Request $request){
                set_time_limit((int) 0);
                // $RJ_10_laboratory = RJ_10_laboratory::orderBy('noreg', 'desc')->pluck('noreg')->first();
                // $RJ_10_laboratory_Log = RJ_10_laboratory_Log::orderBy('noreg', 'desc')->pluck('noreg')->first();
                // if ($RJ_10_laboratory == null && $RJ_10_laboratory_Log == null) {
                //     $noreg_terakhir = RJ_02_A_Kunjungan_Baru::orderBy('noreg', 'asc')->pluck('noreg')->first();
                // }elseif ($RJ_10_laboratory > $RJ_10_laboratory_Log) {
                //     $noreg_terakhir = $RJ_10_laboratory;
                // }elseif ($RJ_10_laboratory < $RJ_10_laboratory_Log) {
                //     $noreg_terakhir = $RJ_10_laboratory_Log;
                // }elseif ($RJ_10_laboratory == $RJ_10_laboratory_Log) {
                //     $noreg_terakhir = $RJ_10_laboratory;
                // }

                // $noreg_terakhir = $noreg_terakhir+1;
                // $noreg_tanggal_depan = (substr($noreg_terakhir, 0, -4)+1)."0000";
                // $data_tanggal_terakhir = RJ_02_A_Kunjungan_Baru::where('noreg', "<",$noreg_tanggal_depan)->orderBy('noreg', 'desc')->pluck('noreg')->first();
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
                //         'nama schedule' => 'Laboratorium'
                //     ], 200);
                // }

                // if (substr($noreg_terakhir, 2, 4) == '1232') {
                //     $noreg_terakhir += 100000000;
                //     $noreg_terakhir = substr_replace($noreg_terakhir, '0101', 2, 4);
                // }

                // $data_terbesar = RJ_02_A_Kunjungan_Baru::orderBy('noreg', 'desc')->pluck('noreg')->first();
                // if ($noreg_terakhir > $data_terbesar) {
                //     return response()->json([
                //         'noreg' => $noreg_terakhir,
                //         'message' => "Noreg Belum Terdaftar",
                //         'nama schedule' => 'Laboratorium'
                //     ], 200);
                // }

                $noreg_terakhir = '2508020017';

                $mapping_kunjungan_poli = RJ_02_A_Kunjungan_Baru::where('noreg', $noreg_terakhir)->first();
                if ($mapping_kunjungan_poli == null) {
                    $this->pembuatan_kunjungan_baru_api($request, $noreg_terakhir);
                    $mapping_kunjungan_poli = RJ_02_A_Kunjungan_Baru::where('noreg', $noreg_terakhir)->first();
                    // $RJ_10_laboratory_Log = new RJ_10_laboratory_Log();
                    // $RJ_10_laboratory_Log->noreg = $noreg_terakhir;
                    // $RJ_10_laboratory_Log->ket_log = 'Mapping kunjungan poli tidak ditemukan';
                    // $RJ_10_laboratory_Log->save();
                    // return response()->json([
                    //     'noreg' => $noreg_terakhir,
                    //     'message' => "Mapping kunjungan poli tidak ditemukan",
                    //     'nama schedule' => 'Laboratorium'
                    // ], 200);
                }
                $registrasi_pasien = RegistrasiPasien::where('NOREG', $noreg_terakhir)->first();
                if ($registrasi_pasien == null) {
                    $RJ_10_laboratory_Log = new RJ_10_laboratory_Log();
                    $RJ_10_laboratory_Log->noreg = $noreg_terakhir;
                    $RJ_10_laboratory_Log->ket_log = 'Noreg Belum Terdaftar';
                    $RJ_10_laboratory_Log->save();
                    return response()->json([
                        'noreg' => $noreg_terakhir,
                        'message' => "Noreg Belum Terdaftar",
                        'nama schedule' => 'Laboratorium'
                    ], 200);
                }

                $data_pasien = $this->patient->search_nik($registrasi_pasien->Pasien->NOKTP);
                if (!(is_object($data_pasien) && property_exists($data_pasien, 'total') && $data_pasien->total != 0)) {
                    $RJ_15_Medication_Request_Log = new RJ_10_laboratory_Log();
                    $RJ_15_Medication_Request_Log->noreg = $noreg_terakhir;
                    $RJ_15_Medication_Request_Log->ket_log = 'NIK '.$registrasi_pasien->Pasien->NAMAPASIEN.' Tidak Ditemukan';
                    $RJ_15_Medication_Request_Log->save();
                    return response()->json([
                        'noreg' => $noreg_terakhir,
                        'message' => 'NIK '.$registrasi_pasien->Pasien->NAMAPASIEN.' Tidak Ditemukan',
                        'nama schedule' => 'pendaftaran pendataan pasien'
                    ], 200);
                }

                // $mapping_dokter_spesialis = RJ_01_Practitioner::where('kode_dokter', $registrasi_pasien->Registrasi_Dokter->KODEDOKTER)->first();
                // if ($mapping_dokter_spesialis == null) {
                //     $RJ_10_laboratory_Log = new RJ_10_laboratory_Log();
                //     $RJ_10_laboratory_Log->noreg = $noreg_terakhir;
                //     $RJ_10_laboratory_Log->ket_log = "Dokter untuk Pasien ".$registrasi_pasien->Pasien->NAMAPASIEN." Tidak Ditemukan Di Mapping Dokter Spesialis";
                //     $RJ_10_laboratory_Log->save();
                //     return response()->json([
                //         'noreg' => $noreg_terakhir,
                //         'message' => "Dokter untuk Pasien ".$registrasi_pasien->Pasien->NAMAPASIEN." Tidak Ditemukan Di Mapping Dokter Spesialis",
                //         'nama schedule' => 'Laboratorium'
                //     ], 200);
                // }

                // $trxpmr = Trxpmr::where('NOREG', (string) $noreg_terakhir)
                //             ->where('KODEBAGIAN', '9404')
                //             ->whereNotNull('NOUPMR')
                //             ->first();
                // if ($trxpmr == null) {
                //     $RJ_10_laboratory_Log = new RJ_10_laboratory_Log();
                //     $RJ_10_laboratory_Log->noreg = $noreg_terakhir;
                //     $RJ_10_laboratory_Log->ket_log = "Tidak ada data pemeriksaan laboratorium pada noreg ini";
                //     $RJ_10_laboratory_Log->save();
                //     return response()->json([
                //         'noreg' => $noreg_terakhir,
                //         'message' => "Tidak ada data pemeriksaan laboratorium pada noreg ini",
                //         'nama schedule' => 'Laboratorium'
                //     ], 200);
                // }

                // $hasil_lis = HasilLis::where('NOLAB_RS', $trxpmr->NOUPMR)
                //                 ->orWhere('NOLAB_RS', 'LIS'.$trxpmr->NOUPMR)
                //                 ->first();
                $hasil_lis = HasilLis::where('NOREG', $noreg_terakhir)
                                ->first();
                if ($hasil_lis == null) {
                    $RJ_10_laboratory_Log = new RJ_10_laboratory_Log();
                    $RJ_10_laboratory_Log->noreg = $noreg_terakhir;
                    $RJ_10_laboratory_Log->ket_log = "Tidak ada data pemeriksaan laboratorium pada noreg ini";
                    $RJ_10_laboratory_Log->save();
                    return response()->json([
                        'noreg' => $noreg_terakhir,
                        'message' => "Tidak ada data pemeriksaan laboratorium pada noreg ini",
                        'nama schedule' => 'Laboratorium'
                    ], 200);
                }

                $data_lab = HasilLis::where('PARAMETER_NAME', $hasil_lis->PARAMETER_NAME)
                        ->where('loinc_req', '!=', null)
                        ->first();

                if ($data_lab == null) {
                    $RJ_10_laboratory_Log = new RJ_10_laboratory_Log();
                    $RJ_10_laboratory_Log->noreg = $noreg_terakhir;
                    $RJ_10_laboratory_Log->ket_log = "Parameter Loinc untuk ".$hasil_lis->PARAMETER_NAME." tidak ditemukan";
                    $RJ_10_laboratory_Log->save();
                }

                $Cek_RJ_10_laboratory = RJ_10_laboratory::where('encounter', $mapping_kunjungan_poli->encounter)
                                        ->where('noreg', $noreg_terakhir)
                                        ->where('kode_pemeriksaan', $hasil_lis->kode)
                                        ->first();
                if ($Cek_RJ_10_laboratory == null) {
                    $RJ_10_laboratory_new = new RJ_10_laboratory();
                    $RJ_10_laboratory_new->encounter = $mapping_kunjungan_poli->encounter;
                    $RJ_10_laboratory_new->noreg = $noreg_terakhir;
                    $RJ_10_laboratory_new->kode_pemeriksaan = $hasil_lis->kode;
                    $RJ_10_laboratory_new->save();
                }
                $RJ_10_laboratory_update = RJ_10_laboratory::where('encounter', $mapping_kunjungan_poli->encounter)
                                        ->where('noreg', $noreg_terakhir)
                                        ->where('kode_pemeriksaan', $hasil_lis->kode)
                                        ->first();

                if ($RJ_10_laboratory_update->procedure_id == null) {
                    $attempts = 0;
                    $max_attempts = 5;
                    $procedure_data = null;
                    while ($attempts < $max_attempts) {
                        $Patient_id             = $data_pasien->entry[0]->resource->id;
                        $Patient_Name           = $data_pasien->entry[0]->resource->name[0]->text;
                        $Encounter_id           = $mapping_kunjungan_poli->encounter;
                        // $Practitioner_id        = $mapping_dokter_spesialis->satu_sehat_id;
                        // $Practitioner_Name      = $mapping_dokter_spesialis->nama;
                        $Practitioner_id        = '10013576199';
                        $Practitioner_Name      = 'dr.Gusti Reka Kusuma';
                        $start_date             = date('Y-m-d', strtotime($registrasi_pasien->TGLREG)).'T'.date('H:i:s', strtotime($registrasi_pasien->JAMREG)).'+07:00';
                        $end_date               = date('Y-m-d', strtotime($registrasi_pasien->TGLREG)).'T'.date('H:i:s', strtotime($registrasi_pasien->JAMREG)).'+07:00';
                        $procedure_data = $this->rawatJalan->procedure_status_puasa_laboratorium_nominal($Patient_id, $Patient_Name, $Encounter_id, $Practitioner_id, $Practitioner_Name, $start_date, $end_date);
                        if (isset($procedure_data->id)) {
                            $RJ_10_laboratory_update->procedure_id = $procedure_data->id;
                            $RJ_10_laboratory_update->save();
                            break; // Exit loop if successful
                        }else {
                            $attempts++;
                            if ($attempts >= $max_attempts) {
                                $RJ_10_laboratory_Log = new RJ_10_laboratory_Log();
                                $RJ_10_laboratory_Log->noreg = $noreg_terakhir;
                                $RJ_10_laboratory_Log->ket_log = json_encode($procedure_data);
                                $RJ_10_laboratory_Log->save();
                                return response()->json([
                                    'noreg' => $noreg_terakhir,
                                    'message' => "prosedur laboratorium gagal setelah beberapa kali percobaan",
                                    'nama schedule' => 'Laboratorium'
                                ], 200);
                            }
                            sleep(1); // Wait for 1 second before retrying
                        }
                    }
                }

                $RJ_10_laboratory_update = RJ_10_laboratory::where('encounter', $mapping_kunjungan_poli->encounter)
                                        ->where('noreg', $noreg_terakhir)
                                        ->where('kode_pemeriksaan', $hasil_lis->kode)
                                        ->first();

                if ($RJ_10_laboratory_update->service_request_id == null) {
                    $attempts = 0;
                    $max_attempts = 5;
                    $service_request_data = null;
                    while ($attempts < $max_attempts) {
                        $Noreg                  = (string) $noreg_terakhir;
                        $Patient_id             = $data_pasien->entry[0]->resource->id;
                        $Patient_Name           = $data_pasien->entry[0]->resource->name[0]->text;
                        $Encounter_id           = $mapping_kunjungan_poli->encounter;
                        // $Practitioner_id        = $mapping_dokter_spesialis->satu_sehat_id;
                        // $Practitioner_Name      = $mapping_dokter_spesialis->nama;
                        $Practitioner_id        = '10013576199';
                        $Practitioner_Name      = 'dr.Gusti Reka Kusuma';
                        $start_date             = date('Y-m-d', strtotime($registrasi_pasien->TGLREG)).'T'.date('H:i:s', strtotime($registrasi_pasien->JAMREG)).'+07:00';
                        $end_date               = date('Y-m-d', strtotime($registrasi_pasien->TGLREG)).'T'.date('H:i:s', strtotime($registrasi_pasien->JAMREG)).'+07:00';
                        $kode_loinc             = $data_lab->LOINC_REQ;
                        $nama_loinc             = $data_lab->LOINC_REQ;
                        $deskripsi_loinc        = $data_lab->LOINC_REQ;
                        try {
                            $Procedure_Id       = $procedure_data->id;
                        } catch (\Throwable $th) {
                            $Procedure_Id       = $RJ_10_laboratory_update->procedure_id;
                        }
                        $service_request_data = $this->rawatJalan->service_request_laboratorium_nominal($Noreg, $Patient_id, $Patient_Name, $Encounter_id, $Practitioner_id, $Practitioner_Name, $start_date, $end_date, $kode_loinc, $nama_loinc, $deskripsi_loinc, $Procedure_Id);
                        if (isset($service_request_data->id)) {
                            $RJ_10_laboratory_update->service_request_id = $service_request_data->id;
                            $RJ_10_laboratory_update->save();
                            break; // Exit loop if successful
                        }else {
                            $attempts++;
                            if ($attempts >= $max_attempts) {
                                $RJ_10_laboratory_Log = new RJ_10_laboratory_Log();
                                $RJ_10_laboratory_Log->noreg = $noreg_terakhir;
                                $RJ_10_laboratory_Log->ket_log = json_encode($service_request_data);
                                $RJ_10_laboratory_Log->save();
                                return response()->json([
                                    'noreg' => $noreg_terakhir,
                                    'message' => "service request laboratorium gagal setelah beberapa kali percobaan",
                                    'nama schedule' => 'Laboratorium'
                                ], 200);
                            }
                            sleep(1); // Wait for 1 second before retrying
                        }
                    }
                }

                if ($RJ_10_laboratory_update->specimen_id == null) {
                    $attempts = 0;
                    $max_attempts = 5;
                    $specimen_data = null;
                    while ($attempts < $max_attempts) {
                        $Noreg                  = (string) $noreg_terakhir;
                        $Patient_id             = $data_pasien->entry[0]->resource->id;
                        $Patient_Name           = $data_pasien->entry[0]->resource->name[0]->text;
                        // $Practitioner_id        = $mapping_dokter_spesialis->satu_sehat_id;
                        // $Practitioner_Name      = $mapping_dokter_spesialis->nama;
                        $Practitioner_id        = '10013576199';
                        $Practitioner_Name      = 'dr.Gusti Reka Kusuma';
                        $date                   = date('Y-m-d', strtotime($registrasi_pasien->TGLREG)).'T'.date('H:i:s', strtotime($registrasi_pasien->JAMREG)).'+07:00';
                        $kode_snomed            = $data_lab->SPEC_SNOMED_CODE;
                        $nama_snomed            = $data_lab->SPEC_SNOMED_NAME;
                        $value                  = explode('.', $hasil_lis->HASIL)[0];
                        $satuan                 = $hasil_lis->SATUAN ?? $hasil_lis->HASIL;
                        $service_request_id     = $RJ_10_laboratory_update->service_request_id;
                        $specimen_data = $this->rawatJalan->specimen_laboratorium_nominal($Noreg, $Patient_id, $Patient_Name, $Practitioner_id, $Practitioner_Name, $date, $kode_snomed, $nama_snomed, $value, $satuan, $service_request_id);
                        if (isset($specimen_data->id)) {
                            $RJ_10_laboratory_update->specimen_id = $specimen_data->id;
                            $RJ_10_laboratory_update->save();
                            break; // Exit loop if successful
                        }else {
                            $attempts++;
                            if ($attempts >= $max_attempts) {
                                $RJ_10_laboratory_Log = new RJ_10_laboratory_Log();
                                $RJ_10_laboratory_Log->noreg = $noreg_terakhir;
                                $RJ_10_laboratory_Log->ket_log = json_encode($specimen_data);
                                $RJ_10_laboratory_Log->save();
                                return response()->json([
                                    'noreg' => $noreg_terakhir,
                                    'message' => "specimen laboratorium gagal setelah beberapa kali percobaan",
                                    'nama schedule' => 'Laboratorium'
                                ], 200);
                            }
                            sleep(1); // Wait for 1 second before retrying
                        }
                    }
                }

                $RJ_10_laboratory_update = RJ_10_laboratory::where('encounter', $mapping_kunjungan_poli->encounter)
                                        ->where('noreg', $noreg_terakhir)
                                        ->where('kode_pemeriksaan', $hasil_lis->kode)
                                        ->first();

                if ($RJ_10_laboratory_update->observation_id == null) {
                    $attempts = 0;
                    $max_attempts = 5;
                    $observation_data = null;
                    while ($attempts < $max_attempts) {
                        $Noreg                 = (string) $noreg_terakhir;
                        $Patient_id            = $data_pasien->entry[0]->resource->id;
                        // $Encounter_id          = $mapping_kunjungan_poli->encounter;
                        // $Practitioner_id        = $mapping_dokter_spesialis->satu_sehat_id;
                        $Practitioner_id        = '10013576199';
                        $Practitioner_Name      = 'dr.Gusti Reka Kusuma';
                        try {
                            $Specimen_Id           = $specimen_data->id;
                            $ServiceRequest_Id     = $service_request_data->id;
                        } catch (\Throwable $th) {
                            $Specimen_Id           = $RJ_10_laboratory_update->specimen_id;
                            $ServiceRequest_Id     = $RJ_10_laboratory_update->service_request_id;
                        }
                        $loinc_code            = $data_lab->LOINC_REQ;
                        $loinc_name            = $data_lab->LOINC_REQ;
                        $request_date          = date('Y-m-d', strtotime($hasil_lis->REG_DATE)).'T'.date('H:i:s', strtotime($hasil_lis->REG_DATE)).'+07:00';
                        $result_date           = date('Y-m-d', strtotime($hasil_lis->MODIFIED_DATE)).'T'.date('H:i:s', strtotime($hasil_lis->MODIFIED_DATE)).'+07:00';
                        $observation_data = $this->rawatJalan->observation_laboratorium_nominal($Noreg, $Patient_id, $Encounter_id, $Practitioner_id, $Specimen_Id, $ServiceRequest_Id, $loinc_code, $loinc_name, $request_date, $result_date);
                        if (isset($observation_data->id)) {
                            $RJ_10_laboratory_update->observation_id = $observation_data->id;
                            $RJ_10_laboratory_update->save();
                            break; // Exit loop if successful
                        }else {
                            $attempts++;
                            if ($attempts >= $max_attempts) {
                                $RJ_10_laboratory_Log = new RJ_10_laboratory_Log();
                                $RJ_10_laboratory_Log->noreg = $noreg_terakhir;
                                $RJ_10_laboratory_Log->ket_log = json_encode($observation_data);
                                $RJ_10_laboratory_Log->save();
                                return response()->json([
                                    'noreg' => $noreg_terakhir,
                                    'message' => "observation laboratorium gagal setelah beberapa kali percobaan",
                                    'nama schedule' => 'Laboratorium'
                                ], 200);
                            }
                            sleep(1); // Wait for 1 second before retrying
                        }
                    }
                }

                $RJ_10_laboratory_update = RJ_10_laboratory::where('encounter', $mapping_kunjungan_poli->encounter)
                                        ->where('noreg', $noreg_terakhir)
                                        ->where('kode_pemeriksaan', $hasil_lis->kode)
                                        ->first();

                if ($RJ_10_laboratory_update->diagnostic_report_id == null) {
                    $attempts = 0;
                    $max_attempts = 5;
                    $diagnostic_report_data = null;
                    while ($attempts < $max_attempts) {
                        $Noreg                 = (string) $noreg_terakhir;
                        $Patient_id             = $data_pasien->entry[0]->resource->id;
                        // $Encounter_id          = $mapping_kunjungan_poli->encounter;
                        // $Practitioner_id        = $mapping_dokter_spesialis->satu_sehat_id;
                        $Practitioner_id        = '10013576199';
                        $Practitioner_Name      = 'dr.Gusti Reka Kusuma';
                        try {
                            $Observation_id        = $observation_data->id;
                            $Specimen_Id           = $specimen_data->id;
                            $ServiceRequest_id     = $service_request_data->id;
                        } catch (\Throwable $th) {
                            $Observation_id        = $RJ_10_laboratory_update->observation_id;
                            $Specimen_Id           = $RJ_10_laboratory_update->specimen_id;
                            $ServiceRequest_id     = $RJ_10_laboratory_update->service_request_id;
                        }
                        $kode_loinc            = $data_lab->LOINC_REQ;
                        $nama_loinc            = $data_lab->LOINC_REQ;
                        $request_date          = date('Y-m-d', strtotime($hasil_lis->REG_DATE)).'T'.date('H:i:s', strtotime($hasil_lis->REG_DATE)).'+07:00';
                        $result_date           = date('Y-m-d', strtotime($hasil_lis->MODIFIED_DATE)).'T'.date('H:i:s', strtotime($hasil_lis->MODIFIED_DATE)).'+07:00';
                        $diagnostic_report_data = $this->rawatJalan->diagnostic_report_laboratorium_nominal($Noreg, $Patient_id, $Encounter_id, $Practitioner_id, $Observation_id, $Specimen_Id, $ServiceRequest_id, $kode_loinc, $nama_loinc, $request_date, $result_date);
                        if (isset($diagnostic_report_data->id)) {
                            $RJ_10_laboratory_update->diagnostic_report_id = $diagnostic_report_data->id;
                            $RJ_10_laboratory_update->save();
                            break; // Exit loop if successful
                        }else {
                            $attempts++;
                            if ($attempts >= $max_attempts) {
                                $RJ_10_laboratory_Log = new RJ_10_laboratory_Log();
                                $RJ_10_laboratory_Log->noreg = $noreg_terakhir;
                                $RJ_10_laboratory_Log->ket_log = json_encode($diagnostic_report_data);
                                $RJ_10_laboratory_Log->save();
                                return response()->json([
                                    'noreg' => $noreg_terakhir,
                                    'message' => "diagnostic report laboratorium gagal setelah beberapa kali percobaan",
                                    'nama schedule' => 'Laboratorium'
                                ], 200);
                            }
                            sleep(1); // Wait for 1 second before retrying
                        }
                    }
                }

                return response()->json([
                    'noreg' => $noreg_terakhir,
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

        public function peresepan_obat_medication_request_api(Request $request){
            set_time_limit((int) 0);
            $medication_request = RJ_15_Medication_Request::orderBy('noreg', 'desc')->pluck('noreg')->first();
            $log_medication_request = RJ_15_Medication_Request_log::orderBy('noreg', 'desc')->pluck('noreg')->first();
            if ($medication_request > $log_medication_request) {
                $noreg_terakhir = $medication_request;
            }elseif ($medication_request < $log_medication_request) {
                $noreg_terakhir = $log_medication_request;
            }elseif ($medication_request == $log_medication_request) {
                $noreg_terakhir = $medication_request;
            }

            $noreg_terakhir = $noreg_terakhir+1;
            $noreg_tanggal_depan = (substr($noreg_terakhir, 0, -4)+1)."0000";
            $data_tanggal_terakhir = RJ_02_A_Kunjungan_Baru::where('noreg', "<",$noreg_tanggal_depan)->orderBy('noreg', 'desc')->pluck('noreg')->first();
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

            if (substr($noreg_terakhir, 2, 4) == '1232') {
                $noreg_terakhir += 100000000;
                $noreg_terakhir = substr_replace($noreg_terakhir, '0101', 2, 4);
            }

            $data_terbesar = RJ_02_A_Kunjungan_Baru::orderBy('noreg', 'desc')->pluck('noreg')->first();
            if ($noreg_terakhir > $data_terbesar) {
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "Noreg Belum Terdaftar",
                    'nama schedule' => 'Medication Request'
                ], 200);
            }

            // $noreg_terakhir = '2506300003';
            // dd("halo");
            $mapping_kunjungan_poli = RJ_02_A_Kunjungan_Baru::where('noreg', $noreg_terakhir)->first();
            if ($mapping_kunjungan_poli == null) {
                $this->pembuatan_kunjungan_baru_api($request, $noreg_terakhir);
                $mapping_kunjungan_poli = RJ_02_A_Kunjungan_Baru::where('noreg', $noreg_terakhir)->first();
                // $log_medication_request = new RJ_15_Medication_Request_Log();
                // $log_medication_request->noreg = $noreg_terakhir;
                // $log_medication_request->ket_log = 'Mapping kunjungan poli tidak ditemukan';
                // $log_medication_request->save();
                // return response()->json([
                //     'noreg' => $noreg_terakhir,
                //     'message' => "Mapping kunjungan poli tidak ditemukan",
                //     'nama schedule' => 'Medication Request'
                // ], 200);
            }

            $registrasi_pasien = RegistrasiPasien::where('NOREG', $noreg_terakhir)->first();
            if ($registrasi_pasien == null) {
                $log_medication_request = new RJ_15_Medication_Request_Log();
                $log_medication_request->noreg = $noreg_terakhir;
                $log_medication_request->ket_log = 'Noreg Belum Terdaftar';
                $log_medication_request->save();
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "Noreg Belum Terdaftar",
                    'nama schedule' => 'Medication Request'
                ], 200);
            }

            $mapping_dokter_spesialis = RJ_01_Practitioner::where('kode_dokter', $registrasi_pasien->Registrasi_Dokter->KODEDOKTER)->first();
            if ($mapping_dokter_spesialis == null) {
                $log_medication_request = new RJ_15_Medication_Request_Log();
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
            if ($trpmn == null) {
                $log_medication_request = new RJ_15_Medication_Request_Log();
                $log_medication_request->noreg = $noreg_terakhir;
                $log_medication_request->ket_log = 'Pasien Tidak Memiliki Resep';
                $log_medication_request->save();
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "Pasien Tidak Memiliki Resep",
                    'nama schedule' => 'Medication Request'
                ], 200);
            }
            // dd($trpmn);

            $trpdn = Trpdn::where('NORESEP', $trpmn->NORESEP)->get();
            if ($trpdn == null || $trpdn->isEmpty()) {
                $log_medication_request = new RJ_15_Medication_Request_Log();
                $log_medication_request->noreg = $noreg_terakhir;
                $log_medication_request->ket_log = 'Pasien Tidak Memiliki Resep';
                $log_medication_request->save();
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "Pasien Tidak Memiliki Resep",
                    'nama schedule' => 'Medication Request'
                ], 200);
            }

            foreach ($trpdn as $item) {
                $mabar = Mabar::where('KODEBARANG', $item->KODEBARANG)->first();
                $master_kfa_obat = RJ_15_Medication_Obat::where('kode_barang_mabar', $item->KODEBARANG)->first();
                if ($master_kfa_obat == null) {
                    $log_medication_request = new RJ_15_Medication_Request_Log();
                    $log_medication_request->noreg = $noreg_terakhir;
                    $log_medication_request->ket_log = 'Obat dengan Kode ini '.$item->KODEBARANG.' Tidak Ditemukan Di Master KFA Obat';
                    $log_medication_request->save();
                    continue;
                }
                $data_pasien = $this->patient->search_nik($registrasi_pasien->Pasien->NOKTP);
                if (!(is_object($data_pasien) && property_exists($data_pasien, 'total') && $data_pasien->total != 0)) {
                    $RJ_15_Medication_Request_Log = new RJ_15_Medication_Request_Log();
                    $RJ_15_Medication_Request_Log->noreg = $noreg_terakhir;
                    $RJ_15_Medication_Request_Log->ket_log = 'NIK '.$registrasi_pasien->Pasien->NAMAPASIEN.' Tidak Ditemukan';
                    $RJ_15_Medication_Request_Log->save();
                    return response()->json([
                        'noreg' => $noreg_terakhir,
                        'message' => 'NIK '.$registrasi_pasien->Pasien->NAMAPASIEN.' Tidak Ditemukan',
                        'nama schedule' => 'pendaftaran pendataan pasien'
                    ], 200);
                }
                $id_obat_satusehat = $master_kfa_obat->kode_satu_sehat;
                $display_obat_satusehat = $master_kfa_obat->keterangan_kfa;
                $id_patient = $data_pasien->entry[0]->resource->id;
                $id_patient_display = $data_pasien->entry[0]->resource->name[0]->text;
                $encounter = $mapping_kunjungan_poli->encounter;
                $tanggal_peresepan = date('Y-m-d', strtotime($trpmn->TGLRESEP)).'T00:00:00.000+07:00';
                $id_dokter_satu_sehat = $mapping_dokter_spesialis->satu_sehat_id;
                $display_dokter_satu_sehat = $mapping_dokter_spesialis->nama;
                $dosis_obat = $item->KETERANGANATRPKAI.' '.$item->KETERANGAN;
                $start_waktu_pemberian_obat = date('Y-m-d', strtotime($trpmn->TGLRESEP)).'T00:00:00.000+07:00';
                $end_waktu_pemberian_obat = date('Y-m-d', strtotime($trpmn->TGLRESEP . ' +7 days')).'T00:00:00.000+07:00';
                $jumlah_obat = intval($item->QTYBAR);
                $durasi_penggunaan = '7';
                // dd($noreg_terakhir, $id_obat_satusehat, $display_obat_satusehat, $id_patient, $id_patient_display, $encounter, $tanggal_peresepan, $id_dokter_satu_sehat, $display_dokter_satu_sehat, $dosis_obat, $start_waktu_pemberian_obat, $end_waktu_pemberian_obat, $jumlah_obat, $durasi_penggunaan);
                    $medication_request = $this->rawatJalan->create_medication_request($id_obat_satusehat, $display_obat_satusehat, $id_patient, $id_patient_display, $encounter, $tanggal_peresepan, $id_dokter_satu_sehat, $display_dokter_satu_sehat, $dosis_obat, $start_waktu_pemberian_obat, $end_waktu_pemberian_obat, $jumlah_obat, $durasi_penggunaan);
                    if (isset($medication_request->id)) {
                        $medication_request_model = new RJ_15_Medication_Request();
                        $medication_request_model->encounter = $encounter;
                        $medication_request_model->noreg = $noreg_terakhir;
                        $medication_request_model->id_medication_request = $medication_request->id;
                        $medication_request_model->save();
                        // ======================pengkajian resep=======================
                            if ($item->USLOGNM != null) {
                                $RJ_01_Practitioner = RJ_01_Practitioner::where('NIK_pegawai', $item->USLOGNM)->first();
                                $id_apoteker = $RJ_01_Practitioner->satu_sehat_id;
                                $display_apoteker = $RJ_01_Practitioner->nama;
                            }else {
                                $id_apoteker = '12881512636';
                                $display_apoteker = "ZAHRINA KHUSNAYA";
                            }
                            $kode_quesnionnaire = $noreg_terakhir.str_replace(' ', '', $item->KODEBARANG);
                            $questionnaire_response = $this->rawatJalan->create_questionnaire_response($kode_quesnionnaire, $id_patient, $id_patient_display, $encounter, $tanggal_peresepan, $id_apoteker, $display_apoteker);
                            if (isset($questionnaire_response->id)) {
                                $RJ_15_Questionnaire_Response = new RJ_15_Questionnaire_Response();
                                $RJ_15_Questionnaire_Response->encounter = $encounter;
                                $RJ_15_Questionnaire_Response->noreg = $noreg_terakhir;
                                $RJ_15_Questionnaire_Response->id_questionnaire_response = $questionnaire_response->id;
                                $RJ_15_Questionnaire_Response->save();
                            }else {
                                $RJ_15_Questionnaire_Response_Log = new RJ_15_Questionnaire_Response_Log();
                                $RJ_15_Questionnaire_Response_Log->noreg = $noreg_terakhir;
                                $RJ_15_Questionnaire_Response_Log->ket_log = json_encode($questionnaire_response);
                                $RJ_15_Questionnaire_Response_Log->save();
                            }
                        // ======================end pengkajian resep=======================

                        // ======================pengeluaran obat=======================
                            $kode_barang_obat = $master_kfa_obat->kode_barang_mabar;
                            $kode_oabat_kfa = $master_kfa_obat->kode_kfa;
                            $deskripsi_obat_kfa = $master_kfa_obat->keterangan_kfa;

                            $medication_dispense_obat = $this->rawatJalan->create_medication_dispense_obat($kode_barang_obat, $kode_oabat_kfa, $deskripsi_obat_kfa);
                            if (isset($medication_dispense_obat->id)) {
                                $medication_dispense_model = new RJ_15_Medication_Dispense();
                                $medication_dispense_model->encounter = $encounter;
                                $medication_dispense_model->noreg = $noreg_terakhir;
                                $medication_dispense_model->id_medication = $medication_dispense_obat->id;
                                $medication_dispense_model->save();

                                $medication_dispense_model = RJ_15_Medication_Dispense::where('noreg', $noreg_terakhir)->where('id_medication', $medication_dispense_obat->id)->first();
                                // $medication_dispense_model = RJ_15_Medication_Dispense::where('noreg', $noreg_terakhir)->where('id_medication', 'dce53a47-bb37-4558-b525-fbb2f494f885')->first();

                                $nomer_resep = $item->NORESEP;
                                $medication_id = $medication_dispense_obat->id;
                                // $medication_id = 'dce53a47-bb37-4558-b525-fbb2f494f885';
                                $display_obat_satusehat = $master_kfa_obat->keterangan_kfa;
                                $id_patient = $data_pasien->entry[0]->resource->id;
                                $id_patient_display = $data_pasien->entry[0]->resource->name[0]->text;
                                $encounter = $mapping_kunjungan_poli->encounter;
                                $id_dockter_satu_sehat = $mapping_dokter_spesialis->satu_sehat_id;
                                $display_dockter_satu_sehat = $mapping_dokter_spesialis->nama;
                                $medication_request_id = $medication_request->id;
                                // $medication_request_id = 'ed7d1796-652c-4390-9a6c-bccdde76e7df';
                                $start_waktu_pemberian_obat = date('Y-m-d', strtotime($item->TANGGAL)).'T'.date('H:i:s', strtotime($item->TANGGAL)).'+07:00';
                                $end_waktu_pemberian_obat = \Carbon\Carbon::parse($item->TANGGAL)->addMinutes(15)->format('Y-m-d\TH:i:s').'+07:00';

                                $medication_dispense = $this->rawatJalan->create_medication_dispense($nomer_resep, $medication_id, $display_obat_satusehat, $id_patient, $id_patient_display, $encounter, $id_dockter_satu_sehat, $display_dockter_satu_sehat, $medication_request_id, $start_waktu_pemberian_obat, $end_waktu_pemberian_obat);
                                if (isset($medication_dispense->id)) {
                                    $medication_dispense_model->id_medication_dispense = $medication_dispense->id;
                                    $medication_dispense_model->save();
                                } else {
                                    $medication_dispense_model_log = new RJ_15_Medication_Dispense_Log();
                                    $medication_dispense_model_log->noreg = $noreg_terakhir;
                                    $medication_dispense_model_log->ket_log = json_encode($medication_dispense);
                                    $medication_dispense_model_log->save();
                                }
                            }else {
                                $log_medication_dispense = new RJ_15_Medication_Dispense_Log();
                                $log_medication_dispense->noreg = $noreg_terakhir;
                                $log_medication_dispense->ket_log = json_encode($medication_dispense_obat);
                                $log_medication_dispense->save();
                            }
                        // ======================end pengeluaran obat=======================
                    }
                sleep(10);
            }

            return response()->json([
                'noreg' => $noreg_terakhir,
                'message' => 'Semua Data sukses dikirim',
                'nama schedule' => 'Tata Laksana Obat'
            ], 200);
        }
    // ===========End 15. Tata Laksana========

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

        public function diagnosis_primary_api(){
            set_time_limit((int) 0);
            $diagnosis = RJ_12_Diagnosis::orderBy('noreg', 'desc')->pluck('noreg')->first();
            $log_diagnosis = RJ_12_Diagnosis_Log::orderBy('noreg', 'desc')->pluck('noreg')->first();
            if ($diagnosis == null && $log_diagnosis == null) {
                $noreg_terakhir = RJ_02_A_Kunjungan_Baru::orderBy('noreg', 'asc')->pluck('noreg')->first();
            }elseif ($diagnosis > $log_diagnosis) {
                $noreg_terakhir = $diagnosis;
            }elseif ($diagnosis < $log_diagnosis) {
                $noreg_terakhir = $log_diagnosis;
            }elseif ($diagnosis == $log_diagnosis) {
                $noreg_terakhir = $diagnosis;
            }

            $noreg_terakhir = $noreg_terakhir+1;

            $noreg_tanggal_depan = (substr($noreg_terakhir, 0, -4)+1)."0000";
            $data_tanggal_terakhir = RJ_02_A_Kunjungan_Baru::where('noreg', "<",$noreg_tanggal_depan)->orderBy('noreg', 'desc')->pluck('noreg')->first();
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
                    'nama schedule' => 'diagnosis primary'
                ], 200);
            }

            if (substr($noreg_terakhir, 2, 4) == '1232') {
                $noreg_terakhir += 100000000;
                $noreg_terakhir = substr_replace($noreg_terakhir, '0101', 2, 4);
            }

            $data_terbesar = RJ_02_A_Kunjungan_Baru::orderBy('noreg', 'desc')->pluck('noreg')->first();
            if ($noreg_terakhir > $data_terbesar) {
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "Noreg Belum Terdaftar",
                    'nama schedule' => 'diagnosis primary'
                ], 200);
            }

            $mapping_kunjungan_poli = RJ_02_A_Kunjungan_Baru::where('noreg', $noreg_terakhir)->first();
            $registrasi_pasien = RegistrasiPasien::where('NOREG', $noreg_terakhir)->first();
            if ($registrasi_pasien == null) {
                $log_diagnosis = new RJ_12_Diagnosis_Log();
                $log_diagnosis->noreg = $noreg_terakhir;
                $log_diagnosis->ket_log = 'Noreg Belum Terdaftar';
                $log_diagnosis->save();
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "Noreg Belum Terdaftar",
                    'nama schedule' => 'diagnosis primary'
                ], 200);
            }
            $mapping_kunjungan_poli = RJ_02_A_Kunjungan_Baru::where('noreg', $noreg_terakhir)->first();
            $mapping_pasien = MappingPasien::where('norm', $registrasi_pasien->NOPASIEN)->first();

            $sep = Sep::where('tanggal_sep', Carbon::parse($registrasi_pasien->TGLREG)->format('Y-m-d'))
                ->where('no_rm', $registrasi_pasien->Pasien->NOPASIEN)
                ->where('no_kartu', $registrasi_pasien->Pasien->NOKARTU)
                // ->where('jenis_rawat', 'Rawat Jalan')
                ->select('no_sep')
                ->first();
            $mapping_icd10 = MappingICD10RawatJalan::where('NOREG', $noreg_terakhir)->first();

            if ($sep == null && $mapping_icd10 == null){
                $RJ_12_Diagnosis_Log = new RJ_12_Diagnosis_Log();
                $RJ_12_Diagnosis_Log->noreg = $noreg_terakhir;
                $RJ_12_Diagnosis_Log->ket_log = 'Pasien Tidak Memiliki SEP';
                $RJ_12_Diagnosis_Log->save();
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "Pasien Tidak Memiliki mapping diagnosis",
                    'nama schedule' => 'diagnosis primary'
                ], 200);
            }

            if ($sep != null) {
                $nomer_sep = $sep->no_sep;
                $attempts = 0;
                $maxAttempts = 5;
                $dataArray = null;

                while ($attempts < $maxAttempts) {
                    try {
                        $apiUrl = "http://10.10.6.13:10000/api/sep-new/{$nomer_sep}";
                        $client = new Client();
                        $response = $client->get($apiUrl);
                        $apiData = $response->getBody()->getContents();
                        $dataArray = json_decode($apiData, true);

                        if ($dataArray['response'] == null) {
                            $log_diagnosis = new RJ_12_Diagnosis_Log();
                            $log_diagnosis->noreg = $noreg_terakhir;
                            $log_diagnosis->ket_log = 'Sep Tidak Ditemukan';
                            $log_diagnosis->save();
                            return response()->json([
                                'noreg' => $noreg_terakhir,
                                'message' => "Sep Tidak Ditemukan",
                                'nama schedule' => 'diagnosis primary'
                            ], 200);
                        }

                        $diagnosa = explode(' - ', $dataArray['response']['diagnosa']);
                        $kode_diagnosa = $diagnosa[0];
                        $deskripsi_diagnosa = $diagnosa[1];
                        break; // Exit the loop if successful
                    } catch (\Throwable $th) {
                        $attempts++;
                        if ($attempts >= $maxAttempts) {
                            return response()->json([
                                'noreg' => $noreg_terakhir,
                                'message' => "gagal mengambil sep setelah {$maxAttempts} percobaan",
                                'nama schedule' => 'diagnosis primary'
                            ], 200);
                        }
                    }
                }
            }

            if ($mapping_icd10 != null) {
                $kode_diagnosa = $mapping_icd10->KODE_ICD;
                $deskripsi_diagnosa = $mapping_icd10->KETERANGAN;
            }

            try {
            $mapping_pasien_kodesatusehat = $mapping_pasien->kodesatusehat;
            $mapping_pasien_namasatusehat = $mapping_pasien->nama;
            $mapping_kunjungan_poli_encounter = $mapping_kunjungan_poli->encounter;
                $data = $this->rawatJalan->diagnosis_primer($kode_diagnosa, $deskripsi_diagnosa, $mapping_pasien_kodesatusehat, $mapping_pasien_namasatusehat, $mapping_kunjungan_poli_encounter);
                $diagnosis = new RJ_12_Diagnosis();
                $diagnosis->encounter = (String)$mapping_kunjungan_poli->encounter;
                $diagnosis->noreg = (String)$noreg_terakhir;
                $diagnosis->kode_icd = (String)$kode_diagnosa;
                $diagnosis->nama_icd = (String)$deskripsi_diagnosa;
                $diagnosis->id_diagnosa = (String)$data->id;
                $diagnosis->save();
            } catch (\Throwable $th) {
                $log_diagnosis = new RJ_12_Diagnosis_Log();
                $log_diagnosis->noreg = $noreg_terakhir;
                $log_diagnosis->ket_log = 'duplicate';
                $log_diagnosis->save();
                return response()->json([
                    'noreg' => $noreg_terakhir,
                    'message' => "duplicate",
                    'nama schedule' => 'diagnosis primary'
                ], 200);
            }
            return response()->json([
                'noreg' => $noreg_terakhir,
                'message' => 'Data Berhasil Disimpan',
                'nama schedule' => 'diagnosis primary'
            ], 200);
        }
    // ===========End 12. Diagnosis========

}
