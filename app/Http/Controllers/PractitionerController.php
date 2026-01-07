<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\Practitioner;
use Illuminate\Support\Carbon;
use App\Models\Personel;
use App\Models\PractitionerModel;

class PractitionerController extends Controller
{
    public function __construct()
    {
        $this->practitioner = new Practitioner;
    }

    public function index(Request $request){
        $tahun_sekarang = Carbon::now()->year;
        if (isset($request->nik)) {
            $data = $this->practitioner->search_nik($request->nik);
            $nik = isset($request->nik);
            $name = isset($request->name);
            $gender = isset($request->gender);
            $birthdate = isset($request->birthdate);
            $id = isset($request->id);
            $mode_search = "nik";
        }elseif (isset($request->name) && isset($request->gender) && isset($request->birthdate)) {
            $data = $this->practitioner->search_name_gender_birthdate($request->name, $request->gender, $request->birthdate);
            $nik = isset($request->nik);
            $name = isset($request->name);
            $gender = isset($request->gender);
            $birthdate = isset($request->birthdate);
            $id = isset($request->id);
            $mode_search = "nama";
        }elseif (isset($request->id)) {
            $data = $this->practitioner->search_by_id($request->id);
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
        return view('practitioner.index', compact('data', 'nik', 'name', 'gender', 'birthdate', 'id', 'tahun_sekarang', 'mode_search'));
    }

    public function create(){
        set_time_limit((int) 90000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000);
        $practitioner_model = PractitionerModel::pluck('nik');
        $personel = Personel::where('NAMA_KRT', '!=', NULL)
                    ->where('ST_K_PERSON', 'A')
                    ->whereNotIn('NAMA_KRT', $practitioner_model)
                    ->paginate(25);
        return view('practitioner.create', compact('personel'));
    }

    public function store(Request $request){
        set_time_limit((int) 90000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000);
        if ($request->nik != null) {
            foreach ($request->nik as $item){
                $personel = Personel::where('NAMA_KRT', $item)->first();
                $data = $this->practitioner->search_nik($item);
                try {
                    if ($data->total != 0) {
                        $practitioner_model = new PractitionerModel();
                        $practitioner_model->nopeg = $personel->NIK;
                        $practitioner_model->nik = $item;
                        $practitioner_model->nama = $data->entry[0]->resource->name[0]->text;
                        $practitioner_model->id_practitioner = $data->entry[0]->resource->id;
                        $practitioner_model->save();
                    }else {
                        return redirect()->back()->with('error', 'NIK '.$personel->NM_PERSON.' Tidak Ditemukan');
                    }
                } catch (\Throwable $th) {
                    return redirect()->back()->with('error', 'NIK '.$personel->NM_PERSON.' Tidak Ditemukan');
                }

            }
            return redirect()->back()->with('success', 'Data Berhasil Disimpan');
        }else {
            return redirect()->back()->with('error', 'Anda Belum Memilih Data');
        }
    }
}
