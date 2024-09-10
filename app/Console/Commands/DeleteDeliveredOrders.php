<?php

namespace App\Console\Commands;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DeleteDeliveredOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:delete-delivered';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete orders that were delivered more than 3 days ago';

    /**
     * Execute the console command.
     */


    public function handle()
    {
        $threeDaysAgo = Carbon::now()->subDays(3);
        Order::where('status', 'delivered')
            ->where('updated_at', '<=', $threeDaysAgo)
            ->delete();

        $this->info('Delivered orders older than 3 days have been deleted.');
    }
}
