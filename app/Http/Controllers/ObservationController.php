<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\Config;
use App\Services\Observasion;
use App\Models\KondisiPulang;
use App\Models\Diagnosis;
use App\Models\Observation;
use App\Models\RegistrasiPasien;
use App\Models\LogObservation;
use App\Models\RjSkriningTb;
use App\Models\IgdSkriningTb;
use App\Models\MappingDokterSpesialis;
use App\Models\MappingPasien;
use Illuminate\Support\Carbon;

class ObservationController extends Controller
{
    public function __construct()
    {
        $this->config = new Config;
        // $this->token = $this->config->auth()->access_token; //jika tidak menggunakan cache
        $this->token = $this->config->getAccessToken(); //jika menggunakan cache
        $this->observasion = new Observasion;
    }

    public function index(Request $request){
        if (isset($request->id)) {
            $data = $this->observasion->search_by_id($request->id);
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
        return view('observation.index', compact('data', 'id', 'mode_search'));
    }

    public function create(Request $request){
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
                                    ->paginate(100)->appends(request()->query());
        return view('observation.create', compact('diagnosis'));
    }

    public function store(Request $request){
        set_time_limit((int) 900000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000);
        if ($request->noreg != null) {
            foreach ($request->noreg as $item){
                $rj_skrining_tb = RjSkriningTb::where('noreg', $item)->first();
                $igd_skrining_tb = IgdSkriningTb::where('noreg', $item)->first();

                if ($rj_skrining_tb != null) {
                    $nadi = $rj_skrining_tb->nadi;
                } elseif ($igd_skrining_tb != null) {
                    $nadi = $igd_skrining_tb->nadi;
                } else {
                    $nadi = null;
                }

                if ($nadi != null) {
                    try {
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

                        $data = $this->observasion->create($mapping_pasien, $diagnosis, $id_practitioner, $date, $nadi);
                        $observation = new Observation();
                        $observation->encounter = $diagnosis->encounter;
                        $observation->noreg = $item;
                        $observation->id_observation = $data->id;
                        $observation->code_loinc = $data->code->coding[0]->code;
                        $observation->desc_loinc = $data->code->coding[0]->display;
                        $observation->unit = $data->valueQuantity->unit;
                        $observation->value = $data->valueQuantity->value;
                        $observation->tanggal = $date;
                        $observation->save();
                    } catch (\Throwable $th) {
                        $log_observation = new LogObservation();
                        $log_observation->noreg = $item;
                        $log_observation->ket_log = $th;
                        $log_observation->save();
                    }
                }else {
                    $log_observation = new LogObservation();
                    $log_observation->noreg = $item;
                    $log_observation->ket_log = "Skriing Tidak Ditemukan";
                    $log_observation->save();
                }
            }
            return redirect()->back()->with('success', 'Data Berhasil Disimpan');
        }else {
            return redirect()->back()->with('error', 'Anda Belum Memilih Data');
        }
    }

}
