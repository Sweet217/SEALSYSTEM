<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\licencias;

class DeleteExpiredLicenses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-expired-licenses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete licenses that have expired';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $this->info('Running DeleteExpiredLicenses command...');
        $now = Carbon::now();
        $expiredLicenses = Licencias::where('licencia_final', '<', $now)->get();

        foreach ($expiredLicenses as $licencia) {
            $licencia->delete();
            $this->info('Deleted license with ID: ' . $licencia->licencia_id);
            Log::info('Deleted license with ID: ' . $licencia->licencia_id);
        }

        $this->info('Expired licenses deleted successfully.');
        Log::info('Expired licenses deleted successfully.');
    }
}
