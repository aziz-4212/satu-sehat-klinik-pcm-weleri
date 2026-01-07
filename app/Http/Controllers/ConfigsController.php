<?php

namespace App\Http\Controllers;

use App\Models\Configs;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ConfigsController extends Controller
{
    public function index()
    {
        $config = Configs::first();
        // dd($config);
        return view('config.create', compact('config'));
    }

    public function create()
    {
        return view('config.create');
    }

    public function store(Request $request)
    {
        $config = Configs::first();
        $config->client_key_dev = $request->client_key_dev;
        $config->secret_key_dev = $request->secret_key_dev;
        $config->organization_id_dev = $request->organization_id_dev;
        $config->auth_url_dev = $request->auth_url_dev;
        $config->base_url_dev = $request->base_url_dev;
        $config->consent_url_dev = $request->consent_url_dev;

        $config->client_key_stag = $request->client_key_stag;
        $config->secret_key_stag = $request->secret_key_stag;
        $config->organization_id_stag = $request->organization_id_stag;
        $config->auth_url_stag = $request->auth_url_stag;
        $config->base_url_stag = $request->base_url_stag;
        $config->consent_url_stag = $request->consent_url_stag;

        $config->client_key_prod = $request->client_key_prod;
        $config->secret_key_prod = $request->secret_key_prod;
        $config->organization_id_prod = $request->organization_id_prod;
        $config->auth_url_prod = $request->auth_url_prod;
        $config->base_url_prod = $request->base_url_prod;
        $config->consent_url_prod = $request->consent_url_prod;

        $config->mode = $request->mode;
        $config->save();
		return redirect()->back()->with('success', 'Configurasi Berhasil Diubah');
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