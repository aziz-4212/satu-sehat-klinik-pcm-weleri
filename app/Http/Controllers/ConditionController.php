<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\Config;
use App\Services\Condition;
use App\Models\MappingKunjunganPoli;
use App\Models\BpjsSepManual;
use App\Models\RegistrasiPasien;
use App\Models\MappingPasien;
use App\Models\Diagnosis;
use App\Models\KondisiPulang;
use App\Models\LogDiagnosis;
use Illuminate\Support\Carbon;
use GuzzleHttp\Client;
class ConditionController extends Controller
{
    public function __construct()
    {
        $this->config = new Config;
        // $this->token = $this->config->auth()->access_token; //jika tidak menggunakan cache
        $this->token = $this->config->getAccessToken(); //jika menggunakan cache
        $this->condition = new Condition;
    }

    public function index(Request $request){
        if (isset($request->id)) {
            $data = $this->condition->search_by_id($request->id);
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
        return view('condition.index', compact('data', 'id', 'mode_search'));
    }

    public function create(Request $request){
        if (isset($request->tanggal)) {
            $yesterdayFormatted = Carbon::parse($request->tanggal)->format('ymd');
        }else {
            $yesterdayFormatted = Carbon::now()->setTimezone('Asia/Jakarta')->format('ymd');
        }
        $diagnosis = Diagnosis::where('noreg', 'like', $yesterdayFormatted.'%')->pluck('noreg');
        $log_diagnosis = LogDiagnosis::where('noreg', 'like', $yesterdayFormatted.'%')->pluck('noreg');
        $mapping_kunjungan_poli = MappingKunjunganPoli::whereNotIn('noreg', $diagnosis)
                                    ->whereNotIn('noreg', $log_diagnosis)
                                    ->where('noreg', 'like', $yesterdayFormatted.'%')
                                    ->paginate(100)->appends(request()->query());
        // $mapping_kunjungan_poli = MappingKunjunganPoli::paginate(25);
        return view('condition.create', compact('mapping_kunjungan_poli'));
    }

    public function store(Request $request){
        set_time_limit((int) 900000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000);
        if ($request->noreg != null) {
            foreach ($request->noreg as $item){

                try {
                    $bpjs_sep_manual = BpjsSepManual::where('noreg', $item)->first();
                    $registrasi_pasien = RegistrasiPasien::where('NOREG', $item)->first();
                    $mapping_kunjungan_poli = MappingKunjunganPoli::where('noreg', $item)->first();
                    $mapping_pasien = MappingPasien::where('norm', $registrasi_pasien->NOPASIEN)->first();

                    $apiUrl = "http://10.10.6.14:10000/api/sep-new/{$bpjs_sep_manual->sep}";
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
                    // return redirect()->back()->with('success', 'Data Berhasil Disimpan');
                }
                // else {
                //     // return redirect()->back()->with('error', $th);
                //     return redirect()->back()->with('success', 'Data Berhasil Disimpan');
                // }

            }
            return redirect()->back()->with('success', 'Data Berhasil Disimpan');
        }else {
            return redirect()->back()->with('error', 'Anda Belum Memilih Data');
        }
    }

    public function create_pasien_pulang(Request $request){
        if (isset($request->tanggal)) {
            $yesterdayFormatted = Carbon::parse($request->tanggal)->format('ymd');
        }else {
            $yesterdayFormatted = Carbon::now()->setTimezone('Asia/Jakarta')->format('ymd');
        }
        $kondisi_pulang = KondisiPulang::where('noreg', 'like', $yesterdayFormatted.'%')->pluck('noreg');
        $diagnosis = Diagnosis::where('noreg', 'like', $yesterdayFormatted.'%')
                                ->whereNotIn('noreg', $kondisi_pulang)
                                ->paginate(25)->appends(request()->query());
        return view('condition.create-pasien-pulang', compact('diagnosis'));
    }

    public function store_pasien_pulang(Request $request){
        if ($request->noreg != null) {
            foreach ($request->noreg as $item){
                $registrasi_pasien = RegistrasiPasien::where('NOREG', $item)->first();
                $mapping_pasien = MappingPasien::where('norm', $registrasi_pasien->NOPASIEN)->first();
                $diagnosis = Diagnosis::where('noreg', $item)->first();
                $data = $this->condition->create_meninggalkan_faskes($mapping_pasien, $diagnosis);

                $kondisi_pulang = new KondisiPulang();
                $kondisi_pulang->encounter = $diagnosis->encounter;
                $kondisi_pulang->id_condition = $data->id;
                $kondisi_pulang->kode_snomed = $data->code->coding[0]->code;
                $kondisi_pulang->ket_snomed = $data->code->coding[0]->display;
                $kondisi_pulang->noreg = $item;
                $kondisi_pulang->save();
            }
            return redirect()->back()->with('success', 'Data Berhasil Disimpan');
        }else {
            return redirect()->back()->with('error', 'Anda Belum Memilih Data');
        }
    }
}
