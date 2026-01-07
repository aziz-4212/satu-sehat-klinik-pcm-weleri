<?php

namespace App\Http\Controllers;

use App\Models\MasterMapmrLoinc;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Services\Medication;

class MedicationController extends Controller
{
    public function __construct()
    {
        $this->medication = new Medication;
    }
    public function index(Request $request)
    {
        if (isset($request->id)) {
            $data = $this->medication->search_by_id($request->id);
            $id = isset($request->id);
            $mode_search = "id";
        }else {
            $data = null;
            $id = isset($request->id);
            $mode_search = null;
        }
        // dd($data);
        // $master_mapmr_loinc = MasterMapmrLoinc::paginate(25);
        return view('medication.index', compact('data', 'id', 'mode_search'));
    }

    public function create()
    {
        $mapmr = Mapmr::paginate(5);
        $master_loinc = MasterLoinc::paginate(25);
        return view('master-mapmr-loinc.create', compact('mapmr', 'master_loinc'));
    }

    public function getData_mapmr(Request $request)
    {
        // Mengambil data dengan paginasi
        $data = Mapmr::paginate(10); // Mengambil 10 data per halaman

        return response()->json($data);
    }

    // Fungsi untuk mencari data
    public function search_mapmr(Request $request)
    {
        // Mendapatkan keyword pencarian dari request
        $keyword = $request->query('keyword');

        // Melakukan pencarian berdasarkan keyword
        $data = Mapmr::where('NAMAPMR', 'like', "%$keyword%")
                            ->orWhere('KODEPMR', 'like', "%$keyword%")
                            ->paginate(10);

        return response()->json($data);
    }

    public function getData_loinc(Request $request)
    {
        // Mengambil data dengan paginasi
        $data = MasterLoinc::paginate(10); // Mengambil 10 data per halaman

        return response()->json($data);
    }

    // Fungsi untuk mencari data
    public function search_loinc(Request $request)
    {
        // Mendapatkan keyword pencarian dari request
        $keyword = $request->query('keyword');

        // Melakukan pencarian berdasarkan keyword
        $data = MasterLoinc::where('kategori_kelompok_pemeriksaan', 'like', "%$keyword%")
                            ->orWhere('nama_pemeriksaan', 'like', "%$keyword%")->orWhere('code', 'like', "%$keyword%")
                            ->paginate(10);

        return response()->json($data);
    }

    public function store(Request $request)
    {
        if ($request->kodepmr == null) {
            return redirect()->back()->with('error', 'Pilih PMR Terlebih Dahulu');
        }
        if ($request->id_master_loinc == null) {
            return redirect()->back()->with('error', 'Pilih LOINC Terlebih Dahulu');
        }
        $cek_master_mapmr_loinc = MasterMapmrLoinc::where('kode_mapmr', $request->kodepmr[0])->where('id_master_loinc', $request->id_master_loinc)->first();
        if ($cek_master_mapmr_loinc != null) {
            return redirect()->back()->with('error', 'Data Sudah Ada');
        }

        foreach ($request->id_master_loinc as $item) {
            $master_mapmr_loinc = new MasterMapmrLoinc();
            $master_mapmr_loinc->kode_mapmr = $request->kodepmr[0];
            $master_mapmr_loinc->id_master_loinc = $item;
            $master_mapmr_loinc->save();
        }
        return redirect()->back()->with('success', 'Data Berhasil Ditambahkan');
    }

    public function edit($id)
    {
        $config = Configs::find($id);
        return view('config.edit', compact('config'));
    }

    public function update(Request $request, $id)
    {
        $config = Configs::find($id);
        $config->keys = $request->key;
        $config->value = $request->value;
        $config->update();
		return redirect()->route('config.index')->with('success', 'Configurasi Berhasil Diubah');
    }

    public function destroy($id)
    {
        $config = Configs::find($id);
        $config->delete();
        return response()->json(["status"=>"success","data"=>"Data Berhasil Dihapus"], 200);
    }
}
