<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Services\Config;
use App\Models\User;
use App\Models\LogKyc;
// ==================rawat jalan=======================
use App\Models\MappingKunjunganPoli;
use App\Models\LogEncounter;
use App\Models\RjMasukRuang;
use App\Models\RjMasukRuangLog;
use App\Models\Diagnosis;
use App\Models\LogDiagnosis;
use App\Models\Observation;
use App\Models\LogObservation;
use App\Models\ServiceRequest;
use App\Models\LogServiceRequest;
use App\Models\ProcedureEdukasiNutrisi;
use App\Models\LogProcedureEdukasiNutrisi;
use App\Models\MasterKfaObat;
use App\Models\MedicationRequestModel;
use App\Models\LogMedicationRequest;
use App\Models\MedicationStatementModel;
use App\Models\LogMedicationStatement;
use App\Models\QuestionnaireResponseModel;
use App\Models\LogQuestionnaireResponse;
use App\Models\CompositionModel;
use App\Models\LogComposition;
use App\Models\CareplanRencanaRawatPasienModel;
use App\Models\LogCareplanRencanaRawatPasien;
// ==================End rawat jalan=======================

// ==================rawat Inap=======================
use App\Models\MappingKunjunganInap;
use App\Models\LogEncounterInap;
use App\Models\RIRencanaRawatPasien;
use App\Models\RIRencanaRawatPasienLog;
use App\Models\RIDiagnosis;
use App\Models\RIDiagnosisLog;
// ==================End rawat Inap=======================

// ==================IGD=======================
use App\Models\MappingKunjunganIgd;
use App\Models\LogEncounterIgd;
use App\Models\IGDSaranaTransportasiKedatangan;
use App\Models\IGDSaranaTransportasiKedatanganLog;
// ==================End IGD=======================

// ==================Kyc=======================
use phpseclib3\Crypt\PublicKeyLoader;
use phpseclib3\Crypt\RSA;
use phpseclib3\Crypt\AES;
use phpseclib3\Crypt\Random;
// ==================End Kyc=======================
class AuthController extends Controller
{
	// protected $config;

    public function __construct()
    {
        $this->config = new Config;
    }

    public function dashboard_rawat_jalan()
    {
        return view('dashboard.rawat-jalan');
    }
    // ====================    KYC    ====================
        public function kyc()
        {
            // dd($user = auth()->user()->practioner->id_practitioner);
            // Load configuration from .env
            $client_id = 'enDEUjBUCQN3oJ432raBaGGIOvmPIvhKyyzmpcDbqokXRgTF';
            $client_secret = '9TUAkHqYzUy8CTAbdpmLY8WUSW5LBSNNymoPu0N1bHaXeAAL3Sd3sfKQiB72PCiU';
            $auth_url = 'https://api-satusehat.kemkes.go.id/oauth2/v1';
            $api_url = 'https://api-satusehat.kemkes.go.id/kyc/v1/generate-url';
            $environment = 'production';

            if ($user = auth()->user()->USERLOGNM == "ADMIN") {
                $logKyc = new LogKyc();
                $logKyc->nama = auth()->user()->USERLOGNM;
                $logKyc->save();

                // Nama dan NIK petugas
                $agent_name = 'ALFIN MAHADI';
                $agent_nik = '10004804437';
            }elseif (auth()->user()->practioner == null) {
                return response()->json(['message' => 'Anda tidak diperbolehkan mengisi KYC karena practioner Satusehat anda belum terdaftar, silahkan hubungi IT'], 403);
                $logKyc = new LogKyc();
                $logKyc->nama = auth()->user()->USERLOGNM;
                $logKyc->save();

            }else {
                $logKyc = new LogKyc();
                $logKyc->nama = auth()->user()->USERLOGNM;
                $logKyc->nopeg = auth()->user()->practioner->id_practitioner;
                $logKyc->save();

                // Nama dan NIK petugas
                $agent_name = $user = auth()->user()->user_log->USFULLNM;
                $agent_nik = $user = auth()->user()->practioner->id_practitioner;
            }

            // Authenticate with OAuth2
            $auth_result = $this->authenticateWithOAuth2($client_id, $client_secret, $auth_url);

            // Generate URL
            $json = $this->generateUrl($agent_name, $agent_nik, $auth_result, $api_url, $environment);
            $validation_web = json_decode($json, TRUE);

            // Pass data to view
            return view('kyc.kyc', ['validation_web' => $validation_web]);
        }

        private function authenticateWithOAuth2($clientId, $clientSecret, $tokenUrl)
        {
            $curl = curl_init();
            $params = [
                'grant_type' => 'client_credentials',
                'client_id' => $clientId,
                'client_secret' => $clientSecret
            ];

            curl_setopt_array($curl, [
                CURLOPT_URL => "${tokenUrl}/accesstoken?grant_type=client_credentials",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => http_build_query($params),
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/x-www-form-urlencoded'
                ],
            ]);

            $response = curl_exec($curl);
            curl_close($curl);

            $data = json_decode($response, true);
            return $data['access_token'];
        }

        private function generateUrl($agen, $nik_agen, $accessToken, $apiUrl, $environment)
        {
            $keyPair = $this->generateRSAKeyPair();
            $publicKey = $keyPair['publicKey'];
            $privateKey = $keyPair['privateKey'];

            if ($environment == 'development') {
                $pubPEM = "-----BEGIN PUBLIC KEY-----
                MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAwqoicEXIYWYV3PvLIdvB
                qFkHn2IMhPGKTiB2XA56enpPb0UbI9oHoetRF41vfwMqfFsy5Yd5LABxMGyHJBbP
                +3fk2/PIfv+7+9/dKK7h1CaRTeT4lzJBiUM81hkCFlZjVFyHUFtaNfvQeO2OYb7U
                kK5JrdrB4sgf50gHikeDsyFUZD1o5JspdlfqDjANYAhfz3aam7kCjfYvjgneqkV8
                pZDVqJpQA3MHAWBjGEJ+R8y03hs0aafWRfFG9AcyaA5Ct5waUOKHWWV9sv5DQXmb
                EAoqcx0ZPzmHJDQYlihPW4FIvb93fMik+eW8eZF3A920DzuuFucpblWU9J9o5w+2
                oQIDAQAB
                -----END PUBLIC KEY-----";
            } elseif ($environment == 'production') {
                $pubPEM = "-----BEGIN PUBLIC KEY-----
                MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAxLwvebfOrPLIODIxAwFp
                4Qhksdtn7bEby5OhkQNLTdClGAbTe2tOO5Tiib9pcdruKxTodo481iGXTHR5033I
                A5X55PegFeoY95NH5Noj6UUhyTFfRuwnhtGJgv9buTeBa4pLgHakfebqzKXr0Lce
                /Ff1MnmQAdJTlvpOdVWJggsb26fD3cXyxQsbgtQYntmek2qvex/gPM9Nqa5qYrXx
                8KuGuqHIFQa5t7UUH8WcxlLVRHWOtEQ3+Y6TQr8sIpSVszfhpjh9+Cag1EgaMzk+
                HhAxMtXZgpyHffGHmPJ9eXbBO008tUzrE88fcuJ5pMF0LATO6ayXTKgZVU0WO/4e
                iQIDAQAB
                -----END PUBLIC KEY-----";
            }

            $data = [
                'agent_name' => $agen,
                'agent_nik' => $nik_agen,
                'public_key' => $publicKey
            ];

            $jsonData = json_encode($data);
            $encryptedPayload = $this->encryptMessage($jsonData, $pubPEM);

            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $apiUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $encryptedPayload,
                CURLOPT_HTTPHEADER => [
                    'X-Debug-Mode: 0',
                    'Content-Type: text/plain',
                    'Authorization: Bearer ' . $accessToken
                ],
            ]);

            $response = curl_exec($ch);
            curl_close($ch);

            return $this->decryptMessage($response, $privateKey);
        }

        private function generateRSAKeyPair()
        {
            $privateKey = RSA::createKey(2048);
            $publicKey = $privateKey->getPublicKey()->toString('PKCS8');

            return [
                'privateKey' => $privateKey,
                'publicKey' => $publicKey,
            ];
        }

        private function encryptMessage($message, $pubPEM)
        {
            $aesKey = Random::string(32);
            $serverKey = PublicKeyLoader::load($pubPEM)->withPadding(RSA::ENCRYPTION_OAEP);
            $wrappedAesKey = $serverKey->encrypt($aesKey);
            $encryptedMessage = $this->aesEncrypt($message, $aesKey);
            $payload = $wrappedAesKey . $encryptedMessage;

            return $this->formatMessage($payload);
        }

        private function decryptMessage($message, $privateKey)
        {
            $beginTag = "-----BEGIN ENCRYPTED MESSAGE-----";
            $endTag = "-----END ENCRYPTED MESSAGE-----";
            $messageContents = substr($message, strlen($beginTag) + 1, strlen($message) - strlen($endTag) - strlen($beginTag) - 2);
            $binaryDerString = base64_decode($messageContents);
            $wrappedKeyLength = 256;
            $wrappedKey = substr($binaryDerString, 0, $wrappedKeyLength);
            $encryptedMessage = substr($binaryDerString, $wrappedKeyLength);
            $key = PublicKeyLoader::load($privateKey);
            $aesKey = $key->decrypt($wrappedKey);

            return $this->aesDecrypt($encryptedMessage, $aesKey);
        }

        private function formatMessage($data)
        {
            $dataAsBase64 = chunk_split(base64_encode($data));
            return "-----BEGIN ENCRYPTED MESSAGE-----\r\n{$dataAsBase64}-----END ENCRYPTED MESSAGE-----";
        }

        private function aesEncrypt($data, $symmetricKey)
        {
            $ivLength = 12;
            $iv = random_bytes($ivLength);
            $cipher = new AES('gcm');
            $cipher->setKeyLength(256);
            $cipher->setKey($symmetricKey);
            $cipher->setNonce($iv);
            $ciphertext = $cipher->encrypt($data);
            $tag = $cipher->getTag();

            return $iv . $ciphertext . $tag;
        }

        private function aesDecrypt($encryptedData, $symmetricKey)
        {
            $ivLength = 12;
            $tagLength = 16;
            $iv = substr($encryptedData, 0, $ivLength);
            $tag = substr($encryptedData, -$tagLength);
            $ciphertext = substr($encryptedData, $ivLength, -$tagLength);
            $aes = new AES('gcm');
            $aes->setKey($symmetricKey);
            $aes->setNonce($iv);
            $aes->setTag($tag);

            return $aes->decrypt($ciphertext);
        }
    // ====================    End KYC    ====================

    // ====================    Auth    ====================
        public function login()
        {
            return view('auth.login');
        }

        public function post_login(Request $request)
        {
            $request->validate([
                'username' => 'required|string',
                'password' => 'required|string',
            ]);

            $user = User::where('username', $request->username)->first();

            if ($user && \Hash::check($request->password, $user->password)) {
                Auth::login($user);
                return redirect()->intended('/');
            }

            return redirect('/login')->with('error', 'Username atau password salah.');
        }

        public function logout()
        {
            Auth::logout();
            return redirect('/login');
        }
    // ====================    Auth    ====================
}
