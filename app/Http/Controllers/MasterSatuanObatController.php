<?php

namespace App\Http\Controllers;

use App\Models\MasterMapmrLoinc;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\MasterSatuanObat;

class MasterSatuanObatController extends Controller
{
    public function index(Request $request)
    {
        $master_satuan_obat = MasterSatuanObat::paginate(25);
        return view('satuan-obat.index', compact('master_satuan_obat'));
    }

    public function create()
    {
        return view('satuan-obat.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_satuan_jual_mabar' => 'required',
            'nama_satuan_jual_mabar' => 'required',
            'kode_satu_sehat' => 'required',
            'kode_satu_sehat' => 'required',

        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $master_satuan_obat = new MasterSatuanObat;
        $master_satuan_obat->kode_satuan_jual_mabar = $request->kode_satuan_jual_mabar;
        $master_satuan_obat->nama_satuan_jual_mabar = $request->nama_satuan_jual_mabar;
        $master_satuan_obat->kode_satu_sehat = $request->kode_satu_sehat;
        $master_satuan_obat->nama_satu_sehat = $request->nama_satu_sehat;
        $master_satuan_obat->save();

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
