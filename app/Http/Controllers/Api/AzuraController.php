<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

use App\Jobs\ProsesKirimAzuraJob;

use App\Models\AiAzura;
use App\Models\AiAzuraResponse;

use App\Models\RegistrasiPasien;
use App\Models\Pasien;
use App\Models\RegistrasiDokter;

class AzuraController extends Controller
{
    public function recomendation_diagnosis()
    {
        $ai_azura = AiAzura::orderBy('noreg', 'desc')->pluck('noreg')->first();
        $registrasi_pasien_terakhir = $ai_azura + 1;

        // Ambil 6 digit pertama dari $registrasi_pasien_terakhir sebagai yymmdd
        $tanggal_depan = substr($registrasi_pasien_terakhir, 0, 6);
        // Tambahkan 1 hari ke tanggal tersebut
        $tahun = substr($tanggal_depan, 0, 2);
        $bulan = substr($tanggal_depan, 2, 2);
        $tanggal = substr($tanggal_depan, 4, 2);

        // Buat objek Carbon dari tanggal yymmdd
        $carbonTanggal = Carbon::createFromFormat('ymd', $tahun . $bulan . $tanggal);
        $tanggal_noreg_sekarang = $carbonTanggal->format('ymd');
        // Tambahkan 1 hari
        $carbonTanggal->addDay();
        // Format kembali ke yymmdd
        $tanggal_depan_baru = $carbonTanggal->format('ymd');
        // Gabungkan dengan 0000 di belakang
        $noreg_tanggal_depan = $tanggal_depan_baru . "0001";

        $data_tanggal_terakhir = RegistrasiPasien::where('noreg', 'like', $tanggal_noreg_sekarang . '%')
            ->orderBy('noreg', 'desc')
            ->pluck('noreg')
            ->first();

        if ($registrasi_pasien_terakhir > $data_tanggal_terakhir) {
            $registrasi_pasien_terakhir = $noreg_tanggal_depan;
        }

        if (Carbon::now('Asia/Jakarta')->hour >= 22) {
            $batas_pengiriman = Carbon::now()->addDay()->setTimezone('Asia/Jakarta')->format('ymd')."0000";
        }else {
            $batas_pengiriman = Carbon::now()->setTimezone('Asia/Jakarta')->format('ymd')."0000";
        }

        if ($registrasi_pasien_terakhir >= $batas_pengiriman) {
            return response()->json([
                'noreg' => $registrasi_pasien_terakhir,
                'message' => "Noreg Belum Terdaftar",
                'nama schedule' => 'Update Antrean Online'
            ], 200);
        }
        $no_pasien = RegistrasiPasien::where('noreg', $registrasi_pasien_terakhir)->pluck('NOPASIEN')->first();
        if ($no_pasien == null) {
            $ai_azura = new AiAzura();
            $ai_azura->noreg = $registrasi_pasien_terakhir;
            $ai_azura->no_pasien = null;
            $ai_azura->nama = null;
            $ai_azura->usia = null;
            $ai_azura->jenis_kelamin = null;
            $ai_azura->berat_badan = null;
            $ai_azura->subjective = null;
            $ai_azura->objective = null;
            $ai_azura->assesment = null;
            $ai_azura->jenis_pelayanan = null;
            $ai_azura->creator = 'noreg tidak ditemukan';
            $ai_azura->no_pasien_ai_azura = null;
            $ai_azura->save();
            return response()->json([
                'noreg' => $registrasi_pasien_terakhir,
                'message' => "noreg tidak ditemukan",
                'status' => 'Berhasil',
                'data' => []
            ], 200);
        }
        $jenis_pelayanan = RegistrasiDokter::where('noreg', $registrasi_pasien_terakhir)->pluck('BAGREGDR')->first();
        $pasien = Pasien::where('NOPASIEN', $no_pasien)->select('NAMAPASIEN', 'TGLLAHIR', 'JNSKELAMIN')->first();

        $nama_pasien = '';
        if ($pasien && $pasien->NAMAPASIEN) {
            // Hilangkan koma dan karakter setelah koma
            $nama = explode(',', $pasien->NAMAPASIEN)[0];
            $nama_pasien = implode(' ', array_map(function($word) {
            $first = mb_substr($word, 0, 1);
            $masked = str_repeat('*', max(mb_strlen($word) - 1, 0));
            return $first . $masked;
            }, explode(' ', $nama)));
        }

        $umur = null;
        if ($pasien && $pasien->TGLLAHIR) {
            $umur = Carbon::parse($pasien->TGLLAHIR)->age;
        }
        $jenis_kelamin = null;
        if ($pasien && $pasien->JNSKELAMIN) {
            $jenis_kelamin = $pasien->JNSKELAMIN == 'L' ? 'Laki-laki' : 'Perempuan';
        }

        if (preg_match('/^[A-Za-z]/', $no_pasien)) {
            $no_pasien_filter = preg_replace('/^[A-Za-z]+/', '', $no_pasien);
        }else {
            $no_pasien_filter = $no_pasien;
        }

        if ($jenis_kelamin === 'Laki-laki') {
            $no_pasien_azura = ($no_pasien_filter * 3) + 7;
        } elseif ($jenis_kelamin === 'Perempuan') {
            $no_pasien_azura = ($no_pasien_filter * 3) + 8;
        }else {
            $no_pasien_azura = ($no_pasien_filter * 3) + 7;
        }

        if (strpos($jenis_pelayanan, '91') === 0) {
            $bb = DB::connection('sqlsrv5')
            ->table('rj_skrining_tb')
            ->where('noreg', $registrasi_pasien_terakhir)
            ->where('status', 1)
            ->pluck('bb')
            ->first();
            $bb = $bb ?? 0;

            $soap = DB::connection('sqlsrv2')
            ->table('RJUMUM')
            ->where('noreg', $registrasi_pasien_terakhir)
            ->first();

            $subjective = $soap->SUBJECTIV ?? null;
            $objective = $soap->OBJECTIV ?? null;
            $assesment = $soap->ASSESMENT ?? null;
            $jenis_pelayanan = 'Rawat Jalan';

            $ai_azura = new AiAzura();
            $ai_azura->noreg = $registrasi_pasien_terakhir;
            $ai_azura->no_pasien = $no_pasien;
            $ai_azura->nama = $nama_pasien;
            $ai_azura->usia = $umur;
            $ai_azura->jenis_kelamin = $jenis_kelamin;
            $ai_azura->berat_badan = $bb;
            $ai_azura->subjective = $subjective;
            $ai_azura->objective = $objective;
            $ai_azura->assesment = $assesment;
            $ai_azura->jenis_pelayanan = $jenis_pelayanan;
            $ai_azura->creator = 'AI';
            $ai_azura->no_pasien_ai_azura = $no_pasien_azura;
            $ai_azura->save();
        } elseif (strpos($jenis_pelayanan, '93') === 0) {
            $soap = DB::connection('sqlsrv5')
            ->table('ass_awal_medis_rawat_inap')
            ->where('NOREG', $registrasi_pasien_terakhir)
            ->select('anamnesis_keluhan_utama', 'hasil_pemeriksaan_penunjang', 'diagnosis_masalah_medis')
            ->first();
            $bb = $soap->pemeriksaan_fisik_bb ?? 0;

            $subjective = $soap->anamnesis_keluhan_utama ?? null;
            $objective = $soap->hasil_pemeriksaan_penunjang ?? null;
            $assesment = $soap->diagnosis_masalah_medis ?? null;
            $jenis_pelayanan = 'Rawat Inap';

            $ai_azura = new AiAzura();
            $ai_azura->noreg = $registrasi_pasien_terakhir;
            $ai_azura->no_pasien = $no_pasien;
            $ai_azura->nama = $nama_pasien;
            $ai_azura->usia = $umur;
            $ai_azura->jenis_kelamin = $jenis_kelamin;
            $ai_azura->berat_badan = $bb;
            $ai_azura->subjective = $subjective;
            $ai_azura->objective = $objective;
            $ai_azura->assesment = $assesment;
            $ai_azura->jenis_pelayanan = $jenis_pelayanan;
            $ai_azura->creator = 'AI';
            $ai_azura->no_pasien_ai_azura = $no_pasien_azura;
            $ai_azura->save();
        } else {
            $ai_azura = new AiAzura();
            $ai_azura->noreg = $registrasi_pasien_terakhir;
            $ai_azura->no_pasien = $no_pasien;
            $ai_azura->nama = $nama_pasien;
            $ai_azura->usia = $umur;
            $ai_azura->jenis_kelamin = $jenis_kelamin;
            $ai_azura->berat_badan = null;
            $ai_azura->subjective = null;
            $ai_azura->objective = null;
            $ai_azura->assesment = null;
            $ai_azura->jenis_pelayanan = null;
            $ai_azura->creator = 'tidak dikirim';
            $ai_azura->no_pasien_ai_azura = $no_pasien_azura;
            $ai_azura->save();
            return response()->json([
                'noreg' => $registrasi_pasien_terakhir,
                'message' => "bukan rawat jalan atau rawat inap",
                'status' => 'Berhasil',
                'data' => []
            ], 200);
        }

        $client = new \GuzzleHttp\Client([
            'headers' => [
            'Content-Type' => 'application/json',
            'User-Agent' => 'insomnia/11.2.0',
            ]
        ]);

        $payload = [
            'condition' => "INPUT DATA PEMERIKSAAN\nData Pasien:\nID Pasien: $no_pasien_azura\nNama: $nama_pasien\nUsia: $umur\nJenis Kelamin: $jenis_kelamin\nBerat Badan: $bb\nSubjective: $subjective\nObjective: $objective\nAssesment: $assesment\nJenis Pelayanan: $jenis_pelayanan\nCreator: AI"
        ];

        $response = $client->post('https://hospital-smart-efficiency.azuralabs.id/api/recomendation', [
            'json' => $payload
        ]);

        $result = json_decode($response->getBody(), true);

        // Variabel untuk menyimpan data hasil response
        $status = $result['status'] ?? null;
        $message = $result['message'] ?? null;
        $data = $result['data'] ?? [];

        $created_at = $data['created_at'] ?? null;
        $updated_at = $data['updated_at'] ?? null;
        $id = $data['id'] ?? null;
        $patient_id = $data['patient_id'] ?? null;
        $patient_name = $data['patient_name'] ?? null;
        $condition_raw = $data['condition_raw'] ?? null;
        $response_raw = $data['response_raw'] ?? null;
        $service_type = $data['service_type'] ?? null;
        $created_by = $data['created_by'] ?? null;
        $updatedAt = $data['updatedAt'] ?? null;
        $createdAt = $data['createdAt'] ?? null;

        $ai_azura_response = new AiAzuraResponse();
        $ai_azura_response->noreg = $registrasi_pasien_terakhir;
        $ai_azura_response->id_azura = $id;
        $ai_azura_response->patient_id = $patient_id;
        $ai_azura_response->patient_name = $patient_name;
        $ai_azura_response->condition_raw = $condition_raw;
        $ai_azura_response->response_raw = $response_raw;
        $ai_azura_response->service_type = $service_type;
        $ai_azura_response->created_by = $created_by;
        $ai_azura_response->status = $status;
        $ai_azura_response->message = $message;
        $ai_azura_response->no_pasien_rs = $no_pasien;
        $ai_azura_response->save();
        return response()->json([
            'noreg' => $registrasi_pasien_terakhir,
            'message' => $message,
            'status' => $status,
            'data' => [
                'created_at' => $created_at,
                'updated_at' => $updated_at,
                'id' => $id,
                'patient_id' => $patient_id,
                'patient_name' => $patient_name,
                'condition_raw' => $condition_raw,
                'response_raw' => $response_raw,
                'service_type' => $service_type,
                'created_by' => $created_by,
                'updatedAt' => $updatedAt,
                'createdAt' => $createdAt
            ]
        ], 200);
    }
}
