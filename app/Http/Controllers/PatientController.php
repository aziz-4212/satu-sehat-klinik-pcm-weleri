<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\Config;
use App\Services\Patient;
use App\Services\Encounter;
use Illuminate\Support\Carbon;
use App\Models\RegistrasiPasien;
use App\Models\MappingPasien;
use App\Models\MappingKunjunganPoli;
use App\Models\MappingDokterSpesialis;
use App\Models\MappingOrganization;
use App\Models\LogEncounter;
use App\Models\Pasien;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DataExport;

class PatientController extends Controller
{
    public function __construct()
    {
        $this->patient = new Patient;
        $this->encounter = new Encounter;
    }

    public function index(Request $request){
        $tahun_sekarang = Carbon::now()->year;
        if (isset($request->nik)) {
            $data = $this->patient->search_nik($request->nik);
            $nik = isset($request->nik);
            $name = isset($request->name);
            $gender = isset($request->gender);
            $birthdate = isset($request->birthdate);
            $id = isset($request->id);
            $mode_search = "nik";
        }elseif (isset($request->name) && isset($request->gender) && isset($request->birthdate)) {
            $data = $this->patient->search_name_gender_birthdate($request->name, $request->gender, $request->birthdate);
            $nik = isset($request->nik);
            $name = isset($request->name);
            $gender = isset($request->gender);
            $birthdate = isset($request->birthdate);
            $id = isset($request->id);
            $mode_search = "nama";
        }elseif (isset($request->id)) {
            $data = $this->patient->search_by_id($request->id);
            $nik = isset($request->nik);
            $name = isset($request->name);
            $gender = isset($request->gender);
            $birthdate = isset($request->birthdate);
            $id = isset($request->id);
            $mode_search = "id";
        }else {
            $data = null;
            $nik = isset($request->nik);
            $name = isset($request->name);
            $gender = isset($request->gender);
            $birthdate = isset($request->birthdate);
            $id = isset($request->id);
            $mode_search = null;
        }
        return view('patient.index', compact('data', 'nik', 'name', 'gender', 'birthdate', 'id', 'mode_search'));
    }

    public function create(Request $request){
        if (isset($request->tanggal)) {
            $yesterdayFormatted = Carbon::parse($request->tanggal)->format('ymd');
        }else {
            $now = Carbon::now()->setTimezone('Asia/Jakarta');
            $yesterday = $now->subDay();
            $yesterdayFormatted = $yesterday->format('ymd');
        }
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
        return view('patient.create', compact('registrasi_pasien'));
    }

    public function store(Request $request){
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

    public function pasien_nik_tidak_terdaftar(){
        $pasien = Pasien::first();
        // dd($pasien);
        $data = LogEncounter::whereRaw("CAST(ket_log AS NVARCHAR(MAX)) LIKE ?", ['%NIK%'])->paginate(25);
        return view('pasien-nik-tidak-terdaftar.index', compact('data'));
    }

    public function pasien_nik_tidak_terdaftar_download_excel()
    {
        set_time_limit((int) 0);
        $filename = 'Pasien_NIK_Tidak_Terdaftar_'.date('YmdHis').'.xlsx';
        $log_encounter = LogEncounter::whereRaw("CAST(ket_log AS NVARCHAR(MAX)) LIKE ?", ['%NIK%']);
            // dd($log_encounter);

        $data[] = [
            'NIK' => 'NIK',
            'Nama' => 'Nama',
            'NO Pasien' => 'NO Pasien',
        ];

        $log_encounter->chunk(1000, function ($log_encounter_chunk) use (&$data) {
            foreach ($log_encounter_chunk as $item) {
                try {
                    $data[] = [
                        'NIK' => "'".$item->regpas->pasien->NOKTP ?? "",
                        'Nama' => $item->regpas->pasien->NAMAPASIEN ?? "",
                        'NO Pasien' => $item->regpas->pasien->NOPASIEN ?? "",
                    ];
                } catch (\Throwable $th) {
                }
            }
        });

        return Excel::download(new DataExport($data), $filename);
    }
}
