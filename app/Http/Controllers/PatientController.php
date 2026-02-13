<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\Patient;
use Illuminate\Support\Carbon;

class PatientController extends Controller
{
    public function __construct()
    {
        $this->patient = new Patient;
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
}
