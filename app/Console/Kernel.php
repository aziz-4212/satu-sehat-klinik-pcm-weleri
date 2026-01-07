<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Carbon;
use App\Console\Commands\SchedulePendaftaranPasien;
use App\Console\Commands\RawatJalanScheduleMasukRuang;
use App\Console\Commands\DiagnosisPrimary;
use App\Console\Commands\ScheduleHasilPemeriksaanFisik;
use App\Console\Commands\ScheduleMedicationRequest;
use App\Console\Commands\ScheduleMedicationStatement;
use App\Console\Commands\ScheduleServiceRequestLaborat;
use App\Console\Commands\ScheduleComposition;
use App\Console\Commands\ScheduleQuestionnaireResponse;
use App\Console\Commands\ScheduleCareplanRencanaRawatPasien;
use App\Console\Commands\ScheduleProcedureEdukasiNutrisi;

use App\Console\Commands\IgdSchedulePendaftaranPasien;
use App\Console\Commands\IgdScheduleSaranaTransportasiKedatangan;

use App\Console\Commands\RawatInapInternalSchedulePendaftaranPasien;
use App\Console\Commands\RawatInapScheduleRencanaRawatPasien;
use App\Console\Commands\RawatInapScheduleDiagnosis;
class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // ==========================Rawat Jalan================================
            $schedule->command(SchedulePendaftaranPasien::class)->everyTenSeconds();
            $schedule->command(RawatJalanScheduleMasukRuang::class)->everyTenSeconds();
            $schedule->command(DiagnosisPrimary::class)->everyTenSeconds();
            $schedule->command(ScheduleHasilPemeriksaanFisik::class)->everyTenSeconds();
            $schedule->command(ScheduleMedicationRequest::class)->everyTenSeconds();
            $schedule->command(ScheduleMedicationStatement::class)->everyTenSeconds();
            // $schedule->command(ScheduleServiceRequestLaborat::class)->everyTwentySeconds();
            $schedule->command(ScheduleServiceRequestLaborat::class)->everyTenSeconds();
            // $schedule->command(ScheduleComposition::class)->everyTwentySeconds();
            $schedule->command(ScheduleQuestionnaireResponse::class)->everyTenSeconds();
            $schedule->command(ScheduleCareplanRencanaRawatPasien::class)->everyTenSeconds();
            $schedule->command(ScheduleProcedureEdukasiNutrisi::class)->everyTenSeconds();
        // ==========================Rawat Jalan================================

        // ==========================IGD================================
            $schedule->command(IgdSchedulePendaftaranPasien::class)->everyTwentySeconds();
            $schedule->command(IgdScheduleSaranaTransportasiKedatangan::class)->everyTwentySeconds();
        // ==========================IGD================================

        // ==========================Rawat Inap================================
            $schedule->command(RawatInapInternalSchedulePendaftaranPasien::class)->everyTwentySeconds();
            $schedule->command(RawatInapScheduleRencanaRawatPasien::class)->everyThirtySeconds();
            $schedule->command(RawatInapScheduleDiagnosis::class)->everyThirtySeconds();
        // ==========================IGD================================

    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
