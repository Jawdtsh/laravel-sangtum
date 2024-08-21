<?php

namespace App\Console\Commands;

use App\Models\PersonalAccessToken;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DeleteExpiredTokenCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-expired-token-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command deletes expired tokens every 6 hours';

    /**
     * Execute the console command..
     */
    public function handle()
    {
        PersonalAccessToken::where('expires_at','<', Carbon::now())->delete();
    }
}
