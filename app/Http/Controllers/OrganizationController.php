<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\Organization;
use App\Services\Config;
use Illuminate\Support\Carbon;
use App\Models\Personel;
use App\Models\MappingOrganization;

class OrganizationController extends Controller
{
    public function __construct()
    {
        $this->organization = new Organization;
        $this->config = new Config;
        $this->organization_id = $this->config->organization_id()->id;
    }

    public function index(Request $request){
        // if (isset($request->id)) {
            $data = $this->organization->search_part_of($this->organization_id);
            $id = isset($request->id);
            $mode_search = "id";
        // }else {
        //     $data = null;
        //     $id = isset($request->id);
        //     $mode_search = null;
        // }

        return view('organization.index', compact('data','id', 'mode_search'));
    }

    public function create(){
        $mapping_organization = MappingOrganization::where('kodesatusehat', NULL)->paginate(25);
        return view('organization.create', compact('mapping_organization'));
    }

    public function store(Request $request){
        set_time_limit((int) 90000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000);
        if ($request->koders != null) {
            foreach ($request->koders as $item){
                $mapping_organization = MappingOrganization::where('koders', $item)->first();
                $data = $this->organization->create($mapping_organization);
                $mapping_organization->kodesatusehat = $data->id;
                $mapping_organization->save();
            }
            return redirect()->back()->with('success', 'Data Berhasil Disimpan');
        }else {
            return redirect()->back()->with('error', 'Anda Belum Memilih Data');
        }
    }
}
