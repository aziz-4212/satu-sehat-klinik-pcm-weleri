<?php

namespace App\Http\Controllers;

use App\Models\MasterMapmrLoinc;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\MasterKfaObat;
use App\Models\Mabar;
use App\Services\Medication;

class MasterKfaObatController extends Controller
{
    public function __construct()
    {
        $this->medication = new Medication;
    }
    public function index(Request $request)
    {
        $master_kfa_obat = MasterKfaObat::orderBy('id', 'desc')->paginate(20);
        return view('kfa-obat.index', compact('master_kfa_obat'));
    }

    public function create()
    {
        $master_kfa_obat = MasterKfaObat::select('kode_barang_mabar')->get();
        $mabar = Mabar::orderBy('KODEBARANG', 'ASC')->whereNotIn('KODEBARANG', $master_kfa_obat)->select('KODEBARANG', 'NAMABARANG')->get();
        return view('kfa-obat.create', compact('mabar'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'obat' => 'required',
            'kode_kfa' => 'required',
            'nama_obat_kfa' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $mabar = Mabar::where('KODEBARANG', $request->obat)->first();
        $kode_obat = $mabar->master_satuan_obat->kode_satu_sehat;
        $display_obat = $mabar->master_satuan_obat->nama_satu_sehat;

        // dd($request->kode_kfa.' - '.$request->nama_obat_kfa.' - '.$kode_obat.' - '.$display_obat);
        try {
            $medication = $this->medication->create_medication($request->kode_kfa, $request->nama_obat_kfa, $kode_obat, $display_obat);
            $master_satuan_obat = new MasterKfaObat;
            $master_satuan_obat->kode_barang_mabar = $request->obat;
            $master_satuan_obat->kode_kfa = $request->kode_kfa;
            $master_satuan_obat->keterangan_kfa = $request->nama_obat_kfa;
            $master_satuan_obat->kode_satu_sehat = $medication->id;
            $master_satuan_obat->save();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Code Tidak Ditemukan')->withInput();
        }

        return redirect()->back()->with('success', 'Data Berhasil Ditambahkan');
    }

    public function edit($id)
    {
        $master_satuan_obat = MasterSatuanObat::find($id);
        return view('satuan-obat.edit', compact('master_satuan_obat'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'kode_satuan_jual_mabar' => 'required',
            'nama_satuan_jual_mabar' => 'required',
            'kode_satu_sehat' => 'required',
            'nama_satu_sehat' => 'required',

        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $master_satuan_obat = MasterSatuanObat::find($id);
        $master_satuan_obat->kode_satuan_jual_mabar = $request->kode_satuan_jual_mabar;
        $master_satuan_obat->nama_satuan_jual_mabar = $request->nama_satuan_jual_mabar;
        $master_satuan_obat->kode_satu_sehat = $request->kode_satu_sehat;
        $master_satuan_obat->nama_satu_sehat = $request->nama_satu_sehat;
        $master_satuan_obat->save();

        return redirect()->back()->with('success', 'Data Berhasil Diubah');
    }

    public function destroy($id)
    {
        $master_satuan_obat = MasterSatuanObat::find($id);
        $master_satuan_obat->delete();

        return redirect()->back()->with('success', 'Data Berhasil Dihapus');
    }
}
