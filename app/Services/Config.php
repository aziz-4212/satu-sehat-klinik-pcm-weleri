<?php

namespace App\Services;
use App\Models\Configs;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class Config
{
    protected $client_key;
    protected $secret_key;
    protected $organization_id;
    protected $auth_url;
    protected $base_url;
    protected $consent_url;
    public function __construct()
    {
        $config_mode = Configs::first()->mode;
        if ($config_mode == "dev") {
            $this->client_key = Configs::first()->client_key_dev;
            $this->secret_key = Configs::first()->secret_key_dev;
            $this->organization_id = Configs::first()->organization_id_dev;
            $this->auth_url = Configs::first()->auth_url_dev;
            $this->base_url = Configs::first()->base_url_dev;
            $this->consent_url = Configs::first()->consent_url_dev;
        }elseif ($config_mode == "stag") {
            $this->client_key = Configs::first()->client_key_stag;
            $this->secret_key = Configs::first()->secret_key_stag;
            $this->organization_id = Configs::first()->organization_id_stag;
            $this->auth_url = Configs::first()->auth_url_stag;
            $this->base_url = Configs::first()->base_url_stag;
            $this->consent_url = Configs::first()->consent_url_stag;
        }elseif ($config_mode == "prod") {
            $this->client_key = Configs::first()->client_key_prod;
            $this->secret_key = Configs::first()->secret_key_prod;
            $this->organization_id = Configs::first()->organization_id_prod;
            $this->auth_url = Configs::first()->auth_url_prod;
            $this->base_url = Configs::first()->base_url_prod;
            $this->consent_url = Configs::first()->consent_url_prod;
        }
    }

    public function auth()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->auth_url.'/accesstoken?grant_type=client_credentials',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'client_id='.$this->client_key.'&client_secret='.$this->secret_key,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        $response = curl_exec($curl);
        // $response = json_decode($response); //aktifkan jika tidak menggunakan cache
        curl_close($curl);
        // return $response; //jika tanpa cache
        return json_decode($response, true); //jika menggunakan cache
    }

    // =================cache token=================
        public function cache_token()
        {
            $token = Cache::remember('token', 60, function () {
                return $this->auth();
            });
            return $token;
        } /**
        * Mendapatkan token akses, memperbarui jika kedaluwarsa
        */
        public function getAccessToken()
        {
            // Jika token masih ada di cache, gunakan token tersebut
            if (Cache::has('access_token')) {
                return Cache::get('access_token');
            }

            // Jika refresh token tersedia, coba perbarui token
            if (Cache::has('refresh_token')) {
                return $this->refreshAccessToken(Cache::get('refresh_token'));
            }

            // Jika tidak ada token, minta token baru
            return $this->requestNewAccessToken();
        }

        /**
            * Meminta token baru dari API
            */
        private function requestNewAccessToken()
        {
            $data = $this->auth();

            if (isset($data['access_token'])) {
                // Simpan token akses ke cache
                Cache::put('access_token', $data['access_token'], now()->addSeconds($data['expires_in']));

                // Simpan refresh token jika tersedia
                if (isset($data['refresh_token'])) {
                    Cache::put('refresh_token', $data['refresh_token'], now()->addDays(30)); // Refresh token lebih lama
                }

                return $data['access_token'];
            }

            throw new \Exception('Unable to obtain access token');
        }

        /**
            * Memperbarui token akses menggunakan refresh token
            */
        private function refreshAccessToken($refreshToken)
        {
            $response = Http::asForm()->post($this->auth_url . '/accesstoken', [
                'grant_type' => 'refresh_token',
                'refresh_token' => $refreshToken,
                'client_id' => $this->client_key,
                'client_secret' => $this->secret_key,
            ]);

            $data = $response->json();

            if (isset($data['access_token'])) {
                // Simpan token akses baru ke cache
                Cache::put('access_token', $data['access_token'], now()->addSeconds($data['expires_in']));

                // Simpan refresh token jika diperbarui
                if (isset($data['refresh_token'])) {
                    Cache::put('refresh_token', $data['refresh_token'], now()->addDays(30));
                }

                return $data['access_token'];
            }

            throw new \Exception('Unable to refresh access token');
        }
    // =================End cache token=============

    public function url()
    {
        $data = json_decode(json_encode([
            'auth_url' => $this->auth_url,
            'base_url' => $this->base_url,
            'consent_url' => $this->consent_url
        ]));
        return $data;
    }

    public function organization_id()
    {
        $data = json_decode(json_encode([
            'id' => $this->organization_id,
        ]));
        return $data;
    }

    public function encounter_by_id($token, $id)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->base_url.'/Encounter/'.$id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$token
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    public function encounter_by_subject($token, $subject)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->base_url.'/Encounter?subject='.$subject,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$token
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }
}
