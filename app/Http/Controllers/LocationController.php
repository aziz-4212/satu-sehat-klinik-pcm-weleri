<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\Location;
use App\Services\Config;
use Illuminate\Support\Carbon;
use App\Models\Personel;
use App\Models\MappingLocation;

class LocationController extends Controller
{
    public function __construct()
    {
        $this->location = new Location;
        $this->config = new Config;
        $this->organization_id = $this->config->organization_id()->id;
    }

    public function index(Request $request){
        // if (isset($request->id)) {
            $data = $this->location->search_part_of($this->organization_id);
            $id = isset($request->id);
            $mode_search = "id";
        // }else {
        //     $data = null;
        //     $id = isset($request->id);
        //     $mode_search = null;
        // }

        return view('location.index', compact('data', 'mode_search'));
    }

    public function create(){
        $mapping_location = MappingLocation::where('kodesatusehat', NULL)->paginate(25);
        return view('location.create', compact('mapping_location'));
    }

    public function store(Request $request){
        set_time_limit((int) 90000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000);
        if ($request->koders != null) {
            foreach ($request->koders as $item){
                $mapping_location = MappingLocation::where('koders', $item)->first();
                $data = $this->location->create($mapping_location);
                $mapping_location->kodesatusehat = $data->id;
                $mapping_location->save();
            }
            return redirect()->back()->with('success', 'Data Berhasil Disimpan');
        }else {
            return redirect()->back()->with('error', 'Anda Belum Memilih Data');
        }
    }
}