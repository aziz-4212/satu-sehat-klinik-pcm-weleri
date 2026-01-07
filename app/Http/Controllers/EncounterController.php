<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\Config;

class EncounterController extends Controller
{
    public function __construct()
    {
        $this->config = new Config;
        // $this->token = $this->config->auth()->access_token; //jika tidak menggunakan cache
        $this->token = $this->config->getAccessToken(); //jika menggunakan cache
    }

    public function index(Request $request){
        if (isset($request->id)) {
            $data = $this->config->encounter_by_id($this->token, $request->id);
            $responseData = json_decode($data);
            $id = isset($request->id);
            $subject = isset($request->subjek);
        }elseif (isset($request->subjek)) {
            $data = $this->config->encounter_by_subject($this->token, $request->subjek);
            $responseData = json_decode($data);
            $id = isset($request->id);
            $subject = isset($request->subjek);
        }else {
            $responseData = null;
            $data = null;
            $id = isset($request->id);
            $subject = isset($request->subjek);
        }
        return view('encounter.index', compact('responseData', 'id', 'subject', 'data'));
    }
}
