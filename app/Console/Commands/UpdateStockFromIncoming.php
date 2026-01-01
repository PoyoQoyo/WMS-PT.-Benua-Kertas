<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Inbound;

class UpdateStockFromIncoming extends Command
{
    protected $signature = 'stock:update-from-incoming';
    protected $description = 'Update stock from existing incoming with Diterima status';

    public function handle()
    {
        $inbounds = Inbound::where('status', 'Diterima')->get();
        
        foreach ($inbounds as $inbound) {
            $this->info("Processing Incoming: {$inbound->incoming_id}");
            $inbound->updateStock();
        }
        
        $this->info("Stock updated successfully from {$inbounds->count()} incoming records!");
        return 0;
    }
}
