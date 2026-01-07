<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ScheduleQuestionnaireResponse extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:questionnaire-response';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'schedule-questionnaire-response-Pengkajian-resep';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        date_default_timezone_set('Asia/Jakarta');

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://10.10.6.13:9001/api/resume-medis-rawat-jalan/questionnaire-response/store',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        // info($response);
    }
}
